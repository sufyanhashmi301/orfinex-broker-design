<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionQueryService;
use App\Services\IBTransactionPeriodService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DebugCRMFiltering extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:debug-crm-filtering {user_id : User ID to debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug exactly what the CRM filtering is doing vs raw SQL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $this->info("🔍 Debugging CRM Filtering Logic for User ID: {$userId}");
        $this->newLine();
        
        // 1. Test CRM query with full year filter
        $filters = [
            'created_at' => '2025-01-01 to 2025-12-31'
        ];
        
        $this->info("🔧 Testing CRM Query with Full Year Filter:");
        $this->info("   Filter: created_at = '2025-01-01 to 2025-12-31'");
        
        // Get the actual query that CRM builds
        $crmQuery = IBTransactionQueryService::getUserIBTransactions($userId, $filters);
        
        if ($crmQuery) {
            // Get the SQL that will be executed
            $sql = $crmQuery->toSql();
            $bindings = $crmQuery->getBindings();
            
            $this->info("📝 Generated SQL Query:");
            $this->line($sql);
            $this->newLine();
            
            $this->info("🔗 Query Bindings:");
            foreach ($bindings as $i => $binding) {
                $this->line("   [{$i}] = {$binding}");
            }
            $this->newLine();
            
            // Execute and get results
            $results = DB::query()->fromSub($crmQuery, 'ib_transactions')
                ->select([
                    DB::raw('COUNT(*) as total_count'),
                    DB::raw('SUM(CAST(final_amount AS DECIMAL(15,2))) as total_amount'),
                ])
                ->first();
            
            $this->info("📊 CRM Query Results:");
            $this->info("   Total Amount: " . ($results->total_amount ?? 0));
            $this->info("   Total Count: " . ($results->total_count ?? 0));
        } else {
            $this->error("❌ CRM Query returned null");
        }
        
        $this->newLine();
        
        // 2. Check what quarter tables are being selected
        $startDate = Carbon::parse('2025-01-01')->startOfDay();
        $endDate = Carbon::parse('2025-12-31')->endOfDay();
        
        $this->info("📅 Date Range Analysis:");
        $this->info("   Start Date: " . $startDate->format('Y-m-d H:i:s'));
        $this->info("   End Date: " . $endDate->format('Y-m-d H:i:s'));
        $this->newLine();
        
        // Use reflection to call the private method
        $reflection = new \ReflectionClass(IBTransactionQueryService::class);
        $method = $reflection->getMethod('getQuarterTablesForDateRange');
        $method->setAccessible(true);
        
        $quarterTables = $method->invoke(null, $startDate, $endDate);
        
        $this->info("📊 Quarter Tables Selected by CRM:");
        foreach ($quarterTables as $table) {
            $this->info("   - {$table}");
        }
        $this->newLine();
        
        // 3. Check each quarter table individually with CRM logic
        $totalCrmAmount = 0;
        $totalCrmCount = 0;
        
        $this->info("🔍 Individual Quarter Table Analysis (with CRM date filtering):");
        
        foreach ($quarterTables as $tableName) {
            if (!\Illuminate\Support\Facades\Schema::hasTable($tableName)) {
                $this->warn("   {$tableName}: Table doesn't exist");
                continue;
            }
            
            // Build query exactly like CRM does
            $query = DB::table($tableName)
                ->where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate]);
            
            $count = $query->count();
            $sum = $query->sum('final_amount');
            
            $totalCrmAmount += $sum;
            $totalCrmCount += $count;
            
            $this->info("   {$tableName}:");
            $this->info("     Count: {$count}");
            $this->info("     Sum: \${$sum}");
            
            // Check date range of records in this table
            $dateRange = DB::table($tableName)
                ->where('user_id', $userId)
                ->select([
                    DB::raw('MIN(created_at) as earliest'),
                    DB::raw('MAX(created_at) as latest'),
                    DB::raw('COUNT(*) as total_records')
                ])
                ->first();
            
            if ($dateRange && $dateRange->total_records > 0) {
                $this->info("     Date Range: {$dateRange->earliest} to {$dateRange->latest}");
                $this->info("     Total Records in Table: {$dateRange->total_records}");
            }
        }
        
        $this->newLine();
        $this->info("📊 CRM Total (Manual Calculation):");
        $this->info("   Total Amount: \${$totalCrmAmount}");
        $this->info("   Total Count: {$totalCrmCount}");
        $this->newLine();
        
        // 4. Compare with raw SQL
        $rawSqlResult = DB::select("
            SELECT 
                SUM(final_amount) as total_amount,
                COUNT(*) as total_count
            FROM (
                SELECT final_amount FROM ib_transactions_2025_q1 
                WHERE user_id = ? AND type = 'ib_bonus'
                
                UNION ALL
                
                SELECT final_amount FROM ib_transactions_2025_q2 
                WHERE user_id = ? AND type = 'ib_bonus'
                
                UNION ALL
                
                SELECT final_amount FROM ib_transactions_2025_q3 
                WHERE user_id = ? AND type = 'ib_bonus'
            ) as all_quarters
        ", [$userId, $userId, $userId]);
        
        $rawAmount = $rawSqlResult[0]->total_amount ?? 0;
        $rawCount = $rawSqlResult[0]->total_count ?? 0;
        
        $this->info("📊 Raw SQL Results:");
        $this->info("   Total Amount: \${$rawAmount}");
        $this->info("   Total Count: {$rawCount}");
        $this->newLine();
        
        // 5. Analysis
        $this->info("🔍 Final Analysis:");
        $crmAmount = $results->total_amount ?? 0;
        $difference = $crmAmount - $rawAmount;
        
        if (abs($difference) > 0.01) {
            $this->error("❌ DISCREPANCY FOUND!");
            $this->error("   CRM Amount: \${$crmAmount}");
            $this->error("   Raw SQL Amount: \${$rawAmount}");
            $this->error("   Difference: \${$difference}");
            $this->newLine();
            
            if ($difference > 0) {
                $this->warn("🔍 CRM is showing MORE than raw SQL. Possible causes:");
                $this->warn("   1. CRM includes records with different 'type' values");
                $this->warn("   2. CRM includes records outside quarter date ranges");
                $this->warn("   3. CRM has different date filtering logic");
                $this->warn("   4. CRM includes records from additional tables");
            } else {
                $this->warn("🔍 CRM is showing LESS than raw SQL. Possible causes:");
                $this->warn("   1. CRM excludes some records due to date filtering");
                $this->warn("   2. CRM has additional WHERE conditions");
                $this->warn("   3. Quarter table selection logic is filtering out records");
            }
        } else {
            $this->info("✅ Amounts match! The issue might be elsewhere.");
        }
        
        return Command::SUCCESS;
    }
}
