<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionQueryService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DebugUserIBTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:debug-user-transactions {user_id : The user ID to debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug IB transaction loading for a specific user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $userId = (int) $this->argument('user_id');
        
        $this->info("🔍 Debugging IB Transactions for User ID: {$userId}");
        $this->info("================================================");
        
        try {
            // Check if user exists
            $user = User::find($userId);
            if (!$user) {
                $this->error("❌ User with ID {$userId} not found!");
                return Command::FAILURE;
            }
            
            $this->info("✅ User found: {$user->email}");
            $this->info("💰 User IB Balance: $" . number_format($user->ib_balance, 2));
            
            // Check for IB transactions in main table
            $mainTableCount = DB::table('transactions')
                ->where('user_id', $userId)
                ->where('type', 'ib_bonus')
                ->count();
            $this->info("📊 IB transactions in main table: {$mainTableCount}");
            
            // Check available quarter tables
            $this->info("\n🏗️  Available Quarter Tables:");
            $quarters = ['2024_q1', '2024_q2', '2024_q3', '2025_q1', '2025_q2', '2025_q3', '2025_q4'];
            $availableTables = [];
            
            foreach ($quarters as $quarter) {
                $tableName = "ib_transactions_{$quarter}";
                if (Schema::hasTable($tableName)) {
                    $count = DB::table($tableName)->where('user_id', $userId)->count();
                    $this->info("  ✅ {$tableName}: {$count} transactions");
                    if ($count > 0) {
                        $availableTables[] = $tableName;
                    }
                } else {
                    $this->warn("  ❌ {$tableName}: Table not found");
                }
            }
            
            // Test the IBTransactionQueryService
            $this->info("\n🧪 Testing IBTransactionQueryService:");
            
            try {
                $query = IBTransactionQueryService::getUserIBTransactions($userId);
                
                if ($query === null) {
                    $this->error("❌ getUserIBTransactions returned null!");
                } else {
                    $this->info("✅ Query object created successfully");
                    
                    // Try to get count
                    try {
                        $count = DB::query()->fromSub($query, 'ib_transactions')->count();
                        $this->info("📊 Query result count: {$count}");
                    } catch (\Exception $e) {
                        $this->error("❌ Error executing count query: " . $e->getMessage());
                    }
                    
                    // Try to get first few records
                    try {
                        $records = DB::query()->fromSub($query, 'ib_transactions')->limit(3)->get();
                        $this->info("📝 Sample records: " . $records->count());
                        
                        foreach ($records as $record) {
                            $this->info("  - ID: {$record->id}, Amount: {$record->amount}, Date: {$record->created_at}");
                        }
                    } catch (\Exception $e) {
                        $this->error("❌ Error fetching sample records: " . $e->getMessage());
                        $this->error("Stack trace: " . $e->getTraceAsString());
                    }
                }
            } catch (\Exception $e) {
                $this->error("❌ Error in getUserIBTransactions: " . $e->getMessage());
                $this->error("Stack trace: " . $e->getTraceAsString());
            }
            
            // Test summary
            $this->info("\n📈 Testing Summary Service:");
            try {
                $summary = IBTransactionQueryService::getUserIBTransactionsSummary($userId);
                $this->info("✅ Summary generated successfully");
                $this->info("  Total Amount: $" . number_format($summary['total_amount'], 2));
                $this->info("  Total Count: " . number_format($summary['total_count']));
            } catch (\Exception $e) {
                $this->error("❌ Error in getUserIBTransactionsSummary: " . $e->getMessage());
                $this->error("Stack trace: " . $e->getTraceAsString());
            }
            
            // Check if migration is needed
            if ($mainTableCount > 0 && empty($availableTables)) {
                $this->warn("\n⚠️  ISSUE FOUND: User has {$mainTableCount} IB transactions in main table but no quarter tables!");
                $this->warn("   Solution: Run the migration commands to move data to quarter tables");
                $this->info("   Commands to run:");
                $this->info("   1. php artisan ib:recalculate-balances");
                $this->info("   2. php artisan ib:create-transactions-table-4month --auto");
                $this->info("   3. php artisan ib:auto-migration");
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Debug failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
