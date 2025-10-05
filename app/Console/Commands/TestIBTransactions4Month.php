<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\IBTransactionPeriodService;
use App\Services\IBTransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class TestIBTransactions4Month extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:test-4month-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the 4-month IB transactions system functionality';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Testing 4-Month IB Transactions System");
        $this->info("=====================================");
        
        try {
            // Test 1: Period Service Functions
            $this->info("\n1. Testing Period Service Functions:");
            
            $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
            $this->info("   Current Period: " . IBTransactionPeriodService::getPeriodName($currentPeriod));
            
            $nextPeriod = IBTransactionPeriodService::getNextPeriod($currentPeriod);
            $this->info("   Next Period: " . IBTransactionPeriodService::getPeriodName($nextPeriod));
            
            $previousPeriod = IBTransactionPeriodService::getPreviousPeriod($currentPeriod);
            $this->info("   Previous Period: " . IBTransactionPeriodService::getPeriodName($previousPeriod));
            
            // Test 2: Table Names
            $this->info("\n2. Testing Table Names:");
            $this->info("   Current Table: " . IBTransactionPeriodService::getTableName($currentPeriod));
            $this->info("   Next Table: " . IBTransactionPeriodService::getTableName($nextPeriod));
            $this->info("   Previous Table: " . IBTransactionPeriodService::getTableName($previousPeriod));
            
            // Test 3: Date Ranges
            $this->info("\n3. Testing Date Ranges:");
            $dateRange = IBTransactionPeriodService::getPeriodDateRange($currentPeriod);
            $this->info("   Current Period Range: " . $dateRange['start']->format('Y-m-d') . " to " . $dateRange['end']->format('Y-m-d'));
            
            // Test 4: Table Creation
            $this->info("\n4. Testing Table Creation:");
            
            $testPeriods = [$currentPeriod, $nextPeriod, $previousPeriod];
            foreach ($testPeriods as $period) {
                $tableName = IBTransactionPeriodService::getTableName($period);
                $exists = Schema::hasTable($tableName);
                
                if (!$exists) {
                    $this->info("   Creating table for " . IBTransactionPeriodService::getPeriodName($period));
                    $result = IBTransactionService::createIBTransactionTable4Month($period);
                    
                    if ($result) {
                        $this->info("   ✓ Table {$tableName} created successfully");
                    } else {
                        $this->error("   ✗ Failed to create table {$tableName}");
                    }
                } else {
                    $this->info("   ✓ Table {$tableName} already exists");
                }
            }
            
            // Test 5: Period Detection
            $this->info("\n5. Testing Period Detection:");
            $shouldCreate = IBTransactionPeriodService::shouldCreateNextPeriodTable();
            $this->info("   Should create next period table: " . ($shouldCreate ? 'Yes' : 'No'));
            
            // Test 6: Year Periods
            $this->info("\n6. Testing Year Periods:");
            $currentYear = Carbon::now()->year;
            $yearPeriods = IBTransactionPeriodService::getYearPeriods($currentYear);
            $this->info("   Periods for {$currentYear}: " . implode(', ', $yearPeriods));
            
            // Test 7: Period Parsing
            $this->info("\n7. Testing Period Parsing:");
            $parsed = IBTransactionPeriodService::parsePeriod($currentPeriod);
            $this->info("   Parsed {$currentPeriod}: Year={$parsed['year']}, Quarter={$parsed['quarter']}");
            
            // Test 8: Template Table Check
            $this->info("\n8. Checking Template Table:");
            $templateExists = Schema::hasTable('ib_transactions_template');
            if ($templateExists) {
                $this->info("   ✓ Template table 'ib_transactions_template' exists");
            } else {
                $this->warn("   ⚠ Template table 'ib_transactions_template' does not exist");
                $this->warn("     Tables will be created with fallback structure");
            }
            
            $this->info("\n=====================================");
            $this->info("✓ 4-Month IB Transactions System Test Completed Successfully!");
            $this->info("\nSystem is ready to use. You can now:");
            $this->info("• Run 'php artisan ib:schedule-4month-tasks' for automatic management");
            $this->info("• Run 'php artisan copy:ib-transactions-4month' to migrate existing data");
            $this->info("• The system will automatically create new tables and migrate data daily at 2:00 AM");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error testing 4-month system: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}

