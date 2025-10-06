<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionQueryService;
use Illuminate\Support\Facades\DB;

class DebugSummaryCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:debug-summary {user_id : User ID to debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug the summary calculation specifically';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        
        $this->info("🔍 Debugging Summary Calculation for User ID: {$userId}");
        $this->newLine();
        
        $filters = [
            'created_at' => '2025-01-01 to 2025-12-31'
        ];
        
        // 1. Get the main query
        $mainQuery = IBTransactionQueryService::getUserIBTransactions($userId, $filters);
        
        if (!$mainQuery) {
            $this->error("❌ Main query returned null");
            return Command::FAILURE;
        }
        
        $this->info("📝 Main Query SQL:");
        $this->line($mainQuery->toSql());
        $this->newLine();
        
        $this->info("🔗 Main Query Bindings:");
        foreach ($mainQuery->getBindings() as $i => $binding) {
            $this->line("   [{$i}] = {$binding}");
        }
        $this->newLine();
        
        // 2. Test the summary calculation step by step
        $this->info("🔧 Testing Summary Calculation:");
        
        // Method 1: Direct fromSub (current method)
        $summaryMethod1 = DB::query()->fromSub($mainQuery, 'ib_transactions')
            ->select([
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(CAST(final_amount AS DECIMAL(15,2))) as total_amount'),
            ])
            ->first();
        
        $this->info("📊 Method 1 (fromSub - Current):");
        $this->info("   Total Amount: " . ($summaryMethod1->total_amount ?? 0));
        $this->info("   Total Count: " . ($summaryMethod1->total_count ?? 0));
        $this->newLine();
        
        // Method 2: Get all records first, then calculate
        $allRecords = $mainQuery->get();
        $method2Amount = $allRecords->sum('final_amount');
        $method2Count = $allRecords->count();
        
        $this->info("📊 Method 2 (Get All Records First):");
        $this->info("   Total Amount: {$method2Amount}");
        $this->info("   Total Count: {$method2Count}");
        $this->newLine();
        
        // Method 3: Use the actual service method
        $serviceResult = IBTransactionQueryService::getUserIBTransactionsSummary($userId, $filters);
        
        $this->info("📊 Method 3 (Service Method):");
        $this->info("   Total Amount: " . $serviceResult['total_amount']);
        $this->info("   Total Count: " . $serviceResult['total_count']);
        $this->newLine();
        
        // 4. Check for any differences
        $this->info("🔍 Analysis:");
        
        $amounts = [
            'Method 1 (fromSub)' => $summaryMethod1->total_amount ?? 0,
            'Method 2 (get->sum)' => $method2Amount,
            'Method 3 (service)' => $serviceResult['total_amount']
        ];
        
        $allSame = count(array_unique($amounts)) === 1;
        
        if ($allSame) {
            $this->info("✅ All methods return the same amount: \$" . $amounts['Method 1 (fromSub)']);
        } else {
            $this->error("❌ Methods return different amounts:");
            foreach ($amounts as $method => $amount) {
                $this->error("   {$method}: \${$amount}");
            }
        }
        
        // 5. Test a simple direct query for comparison
        $directQuery = DB::select("
            SELECT 
                SUM(final_amount) as total_amount,
                COUNT(*) as total_count
            FROM (
                SELECT final_amount FROM ib_transactions_2025_q1 
                WHERE user_id = ? AND type = 'ib_bonus'
                AND created_at BETWEEN '2025-01-01 00:00:00' AND '2025-12-31 23:59:59'
                
                UNION ALL
                
                SELECT final_amount FROM ib_transactions_2025_q2 
                WHERE user_id = ? AND type = 'ib_bonus'
                AND created_at BETWEEN '2025-01-01 00:00:00' AND '2025-12-31 23:59:59'
                
                UNION ALL
                
                SELECT final_amount FROM ib_transactions_2025_q3 
                WHERE user_id = ? AND type = 'ib_bonus'
                AND created_at BETWEEN '2025-01-01 00:00:00' AND '2025-12-31 23:59:59'
            ) as all_quarters
        ", [$userId, $userId, $userId]);
        
        $directAmount = $directQuery[0]->total_amount ?? 0;
        $directCount = $directQuery[0]->total_count ?? 0;
        
        $this->info("📊 Direct SQL Query:");
        $this->info("   Total Amount: \${$directAmount}");
        $this->info("   Total Count: {$directCount}");
        $this->newLine();
        
        // Final comparison
        if (abs($amounts['Method 1 (fromSub)'] - $directAmount) < 0.01) {
            $this->info("✅ CRM calculation matches direct SQL!");
        } else {
            $this->error("❌ CRM calculation differs from direct SQL!");
            $this->error("   Difference: \$" . ($amounts['Method 1 (fromSub)'] - $directAmount));
        }
        
        return Command::SUCCESS;
    }
}
