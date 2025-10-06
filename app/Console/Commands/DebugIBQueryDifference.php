<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionQueryService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DebugIBQueryDifference extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:debug-query-difference {user_id : User ID to debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug the difference between raw SQL and CRM query results';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $this->info("🔍 Debugging Query Difference for User ID: {$userId}");
        $this->newLine();
        
        // 1. Raw SQL Query (like your manual query)
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
        
        $this->info("📊 Raw SQL Query Results:");
        $this->info("   Total Amount: " . ($rawSqlResult[0]->total_amount ?? 0));
        $this->info("   Total Count: " . ($rawSqlResult[0]->total_count ?? 0));
        $this->newLine();
        
        // 2. CRM Query with NO filters (default 3 months)
        $noFilters = [];
        $crmNoFilters = IBTransactionQueryService::getUserIBTransactions($userId, $noFilters);
        
        if ($crmNoFilters) {
            $crmNoFiltersResult = DB::query()->fromSub($crmNoFilters, 'ib_transactions')
                ->select([
                    DB::raw('COUNT(*) as total_count'),
                    DB::raw('SUM(CAST(final_amount AS DECIMAL(15,2))) as total_amount'),
                ])
                ->first();
            
            $this->info("📊 CRM Query (No Filters - Default 3 months):");
            $this->info("   Total Amount: " . ($crmNoFiltersResult->total_amount ?? 0));
            $this->info("   Total Count: " . ($crmNoFiltersResult->total_count ?? 0));
        } else {
            $this->warn("📊 CRM Query (No Filters): NULL result");
        }
        $this->newLine();
        
        // 3. CRM Query with date range filter (full year)
        $fullYearFilters = [
            'created_at' => '2025-01-01 to 2025-12-31'
        ];
        $crmFullYear = IBTransactionQueryService::getUserIBTransactions($userId, $fullYearFilters);
        
        if ($crmFullYear) {
            $crmFullYearResult = DB::query()->fromSub($crmFullYear, 'ib_transactions')
                ->select([
                    DB::raw('COUNT(*) as total_count'),
                    DB::raw('SUM(CAST(final_amount AS DECIMAL(15,2))) as total_amount'),
                ])
                ->first();
            
            $this->info("📊 CRM Query (2025 Full Year Filter):");
            $this->info("   Total Amount: " . ($crmFullYearResult->total_amount ?? 0));
            $this->info("   Total Count: " . ($crmFullYearResult->total_count ?? 0));
        } else {
            $this->warn("📊 CRM Query (Full Year): NULL result");
        }
        $this->newLine();
        
        // 4. Check what date range CRM is actually using
        $this->info("📅 Date Range Analysis:");
        $endDate = Carbon::now();
        $startDate3Months = $endDate->copy()->subMonths(3);
        $startDate1Year = $endDate->copy()->subYear();
        
        $this->info("   Current Date: " . $endDate->format('Y-m-d H:i:s'));
        $this->info("   3 Months Ago: " . $startDate3Months->format('Y-m-d H:i:s'));
        $this->info("   1 Year Ago: " . $startDate1Year->format('Y-m-d H:i:s'));
        $this->newLine();
        
        // 5. Check records by date ranges
        $q1Count = DB::table('ib_transactions_2025_q1')
            ->where('user_id', $userId)
            ->where('type', 'ib_bonus')
            ->count();
        
        $q2Count = DB::table('ib_transactions_2025_q2')
            ->where('user_id', $userId)
            ->where('type', 'ib_bonus')
            ->count();
        
        $q3Count = DB::table('ib_transactions_2025_q3')
            ->where('user_id', $userId)
            ->where('type', 'ib_bonus')
            ->count();
        
        $this->info("📊 Records by Quarter:");
        $this->info("   Q1 2025 (Jan-Apr): {$q1Count} records");
        $this->info("   Q2 2025 (May-Aug): {$q2Count} records");
        $this->info("   Q3 2025 (Sep-Dec): {$q3Count} records");
        $this->newLine();
        
        // 6. Check if there are records outside the 3-month window
        $recentRecords = DB::select("
            SELECT 
                COUNT(*) as total_count,
                SUM(final_amount) as total_amount
            FROM (
                SELECT final_amount FROM ib_transactions_2025_q1 
                WHERE user_id = ? AND type = 'ib_bonus' AND created_at >= ?
                
                UNION ALL
                
                SELECT final_amount FROM ib_transactions_2025_q2 
                WHERE user_id = ? AND type = 'ib_bonus' AND created_at >= ?
                
                UNION ALL
                
                SELECT final_amount FROM ib_transactions_2025_q3 
                WHERE user_id = ? AND type = 'ib_bonus' AND created_at >= ?
            ) as recent_quarters
        ", [$userId, $startDate3Months, $userId, $startDate3Months, $userId, $startDate3Months]);
        
        $this->info("📊 Records in Last 3 Months:");
        $this->info("   Total Amount: " . ($recentRecords[0]->total_amount ?? 0));
        $this->info("   Total Count: " . ($recentRecords[0]->total_count ?? 0));
        $this->newLine();
        
        // 7. Analysis
        $rawAmount = $rawSqlResult[0]->total_amount ?? 0;
        $recentAmount = $recentRecords[0]->total_amount ?? 0;
        
        $this->info("🔍 Analysis:");
        if ($rawAmount != $recentAmount) {
            $difference = $rawAmount - $recentAmount;
            $this->warn("⚠️  Date filtering is causing the difference!");
            $this->warn("   Full year: \${$rawAmount}");
            $this->warn("   Last 3 months: \${$recentAmount}");
            $this->warn("   Difference: \${$difference}");
            $this->newLine();
            $this->info("💡 Solution: Apply date filter in CRM to see full year data");
        } else {
            $this->info("✅ Date filtering is not the issue");
        }
        
        return Command::SUCCESS;
    }
}
