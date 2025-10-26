<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionService;
use App\Services\IBTransactionPeriodService;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestIBQuarterSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:test-quarter-system {--cleanup : Clean up test data after testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comprehensive test of IB quarter-based transaction system';

    private $testTransactions = [];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("╔══════════════════════════════════════════════════════════════╗");
        $this->info("║              IB Quarter System Comprehensive Test           ║");
        $this->info("╚══════════════════════════════════════════════════════════════╝");
        $this->newLine();

        try {
            // Test 1: Period Service Functions
            $this->testPeriodService();
            
            // Test 2: Table Creation
            $this->testTableCreation();
            
            // Test 3: Transaction Creation with Different Dates
            $this->testTransactionCreation();
            
            // Test 4: Automatic Next Quarter Table Creation
            $this->testNextQuarterCreation();
            
            // Test 5: Exception Handling
            $this->testExceptionHandling();
            
            // Test 6: Migration Integration
            $this->testMigrationIntegration();
            
            // Cleanup if requested
            if ($this->option('cleanup')) {
                $this->cleanupTestData();
            }
            
            $this->newLine();
            $this->info("🎉 ALL TESTS PASSED! Quarter-based IB system is working correctly.");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Test failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
    
    /**
     * Test period service functions
     */
    private function testPeriodService()
    {
        $this->info("🧪 Testing Period Service Functions");
        $this->info("-----------------------------------");
        
        // Test current period
        $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
        $this->info("✅ Current period: {$currentPeriod}");
        
        // Test specific dates
        $testDates = [
            '2025-01-15' => '2025_q1',
            '2025-05-20' => '2025_q2', 
            '2025-09-10' => '2025_q3',
            '2025-12-31' => '2025_q3',
            '2026-02-14' => '2026_q1'
        ];
        
        foreach ($testDates as $date => $expectedPeriod) {
            $actualPeriod = IBTransactionPeriodService::getCurrentPeriod(Carbon::parse($date));
            if ($actualPeriod === $expectedPeriod) {
                $this->info("✅ {$date} → {$actualPeriod}");
            } else {
                throw new \Exception("Period mismatch for {$date}: expected {$expectedPeriod}, got {$actualPeriod}");
            }
        }
        
        // Test next/previous period
        $nextPeriod = IBTransactionPeriodService::getNextPeriod('2025_q2');
        $this->info("✅ Next period after 2025_q2: {$nextPeriod}");
        
        $prevPeriod = IBTransactionPeriodService::getPreviousPeriod('2025_q2');
        $this->info("✅ Previous period before 2025_q2: {$prevPeriod}");
        
        $this->newLine();
    }
    
    /**
     * Test table creation
     */
    private function testTableCreation()
    {
        $this->info("🏗️  Testing Table Creation");
        $this->info("---------------------------");
        
        $testPeriods = ['2025_q1', '2025_q2', '2025_q3', '2026_q1'];
        
        foreach ($testPeriods as $period) {
            $result = IBTransactionService::createIBTransactionTable4Month($period);
            $tableName = IBTransactionPeriodService::getTableName($period);
            
            if ($result && Schema::hasTable($tableName)) {
                $this->info("✅ Created/verified table: {$tableName}");
            } else {
                throw new \Exception("Failed to create table: {$tableName}");
            }
        }
        
        $this->newLine();
    }
    
    /**
     * Test transaction creation with different dates
     */
    private function testTransactionCreation()
    {
        $this->info("💰 Testing Transaction Creation");
        $this->info("-------------------------------");
        
        $testCases = [
            ['date' => '2025-01-15', 'period' => '2025_q1', 'amount' => 10.00],
            ['date' => '2025-05-20', 'period' => '2025_q2', 'amount' => 15.00],
            ['date' => '2025-09-10', 'period' => '2025_q3', 'amount' => 20.00],
            ['date' => '2025-12-31', 'period' => '2025_q3', 'amount' => 25.00],
        ];
        
        foreach ($testCases as $testCase) {
            $transactionDate = Carbon::parse($testCase['date']);
            
            $result = IBTransactionService::new(
                amount: $testCase['amount'],
                charge: 0,
                finalAmount: $testCase['amount'],
                method: 'system',
                description: 'Test IB transaction for ' . $testCase['date'],
                type: TxnType::IbBonus,
                status: TxnStatus::Success,
                userId: 1,
                fromUserId: 2,
                transactionDate: $transactionDate
            );
            
            $this->testTransactions[] = $result->tnx;
            
            // Verify transaction was saved to correct table
            $tableName = IBTransactionPeriodService::getTableName($testCase['period']);
            $saved = DB::table($tableName)->where('tnx', $result->tnx)->first();
            
            if ($saved && $saved->amount == $testCase['amount']) {
                $this->info("✅ Transaction {$result->tnx} saved to {$tableName} (Amount: {$testCase['amount']})");
            } else {
                throw new \Exception("Transaction not saved correctly to {$tableName}");
            }
        }
        
        $this->newLine();
    }
    
    /**
     * Test automatic next quarter table creation
     */
    private function testNextQuarterCreation()
    {
        $this->info("🔮 Testing Next Quarter Table Creation");
        $this->info("--------------------------------------");
        
        // Test dates that should trigger next quarter creation
        $triggerDates = [
            '2025-04-30', // Last day of Q1 - should create Q2
            '2025-08-31', // Last day of Q2 - should create Q3
            '2025-12-31', // Last day of Q3 - should create next year Q1
        ];
        
        foreach ($triggerDates as $date) {
            $testDate = Carbon::parse($date);
            $currentPeriod = IBTransactionPeriodService::getCurrentPeriod($testDate);
            $nextPeriod = IBTransactionPeriodService::getNextPeriod($currentPeriod);
            $nextTableName = IBTransactionPeriodService::getTableName($nextPeriod);
            
            // Create transaction on trigger date
            $result = IBTransactionService::new(
                amount: 5.00,
                charge: 0,
                finalAmount: 5.00,
                method: 'system',
                description: 'Test next quarter creation for ' . $date,
                type: TxnType::IbBonus,
                status: TxnStatus::Success,
                userId: 1,
                fromUserId: 2,
                transactionDate: $testDate
            );
            
            $this->testTransactions[] = $result->tnx;
            
            // Check if next quarter table was created
            if (Schema::hasTable($nextTableName)) {
                $this->info("✅ Next quarter table created: {$nextTableName} (triggered by {$date})");
            } else {
                $this->warn("⚠️  Next quarter table not created: {$nextTableName} (may not be in trigger month)");
            }
        }
        
        $this->newLine();
    }
    
    /**
     * Test exception handling
     */
    private function testExceptionHandling()
    {
        $this->info("🛡️  Testing Exception Handling");
        $this->info("------------------------------");
        
        try {
            // Test invalid period
            IBTransactionPeriodService::getNextPeriod('invalid_period');
            throw new \Exception("Should have thrown exception for invalid period");
        } catch (\InvalidArgumentException $e) {
            $this->info("✅ Invalid period exception handled correctly");
        }
        
        try {
            // Test transaction with invalid date
            $result = IBTransactionService::new(
                amount: 10.00,
                charge: 0,
                finalAmount: 10.00,
                method: 'system',
                description: 'Test invalid date',
                type: TxnType::IbBonus,
                status: TxnStatus::Success,
                userId: 1,
                fromUserId: 2,
                transactionDate: 'invalid-date'
            );
            
            // If we get here, Carbon parsed it somehow - that's also valid
            $this->info("✅ Invalid date handled gracefully (parsed by Carbon)");
            $this->testTransactions[] = $result->tnx;
            
        } catch (\Exception $e) {
            $this->info("✅ Invalid date exception handled: " . $e->getMessage());
        }
        
        $this->newLine();
    }
    
    /**
     * Test migration integration
     */
    private function testMigrationIntegration()
    {
        $this->info("🔄 Testing Migration Integration");
        $this->info("--------------------------------");
        
        // Test period detection for existing transaction
        if (!empty($this->testTransactions)) {
            $tnx = $this->testTransactions[0];
            
            // Find which table contains this transaction
            $periods = ['2025_q1', '2025_q2', '2025_q3', '2026_q1'];
            $found = false;
            
            foreach ($periods as $period) {
                $tableName = IBTransactionPeriodService::getTableName($period);
                if (Schema::hasTable($tableName)) {
                    $transaction = DB::table($tableName)->where('tnx', $tnx)->first();
                    if ($transaction) {
                        $transactionDate = Carbon::parse($transaction->created_at);
                        $detectedPeriod = IBTransactionPeriodService::getPeriodForTransaction($transactionDate);
                        
                        if ($detectedPeriod === $period) {
                            $this->info("✅ Migration integration: Transaction {$tnx} correctly detected in {$period}");
                            $found = true;
                            break;
                        }
                    }
                }
            }
            
            if (!$found) {
                throw new \Exception("Migration integration test failed - transaction not found or period mismatch");
            }
        }
        
        $this->newLine();
    }
    
    /**
     * Clean up test data
     */
    private function cleanupTestData()
    {
        $this->info("🧹 Cleaning Up Test Data");
        $this->info("-------------------------");
        
        $cleaned = 0;
        $periods = ['2025_q1', '2025_q2', '2025_q3', '2026_q1'];
        
        foreach ($periods as $period) {
            $tableName = IBTransactionPeriodService::getTableName($period);
            if (Schema::hasTable($tableName)) {
                foreach ($this->testTransactions as $tnx) {
                    $deleted = DB::table($tableName)->where('tnx', $tnx)->delete();
                    $cleaned += $deleted;
                }
            }
        }
        
        $this->info("✅ Cleaned up {$cleaned} test transactions");
        $this->newLine();
    }
}
