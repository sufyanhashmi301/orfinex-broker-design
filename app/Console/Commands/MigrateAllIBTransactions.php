<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\IBTransactionService;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MigrateAllIBTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:migrate-all-transactions {--force} {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all existing IB transactions from main table to quarter-based tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        
        $this->info("IB Transactions Migration to Quarter Tables");
        $this->info("==========================================");
        
        if ($dryRun) {
            $this->warn("DRY RUN MODE - No actual changes will be made");
        }
        
        try {
            // Step 1: Check existing transactions
            $totalTransactions = Transaction::where('type', 'ib_bonus')->count();
            $this->info("Found {$totalTransactions} IB transactions in main table");
            
            if ($totalTransactions === 0) {
                $this->info("No IB transactions to migrate.");
                return Command::SUCCESS;
            }
            
            // Step 2: Get all years with IB transactions
            $years = Transaction::where('type', 'ib_bonus')
                ->selectRaw('YEAR(created_at) as year')
                ->distinct()
                ->orderBy('year')
                ->pluck('year');
                
            $this->info("Found IB transactions in years: " . $years->implode(', '));
            
            // Step 3: Create all necessary quarter tables
            $this->info("\nCreating quarter tables...");
            $createdTables = [];
            
            foreach ($years as $year) {
                $periods = IBTransactionPeriodService::getYearPeriods($year);
                
                foreach ($periods as $period) {
                    $tableName = IBTransactionPeriodService::getTableName($period);
                    
                    if (!$dryRun) {
                        $result = IBTransactionService::createIBTransactionTable4Month($period);
                        if ($result) {
                            $createdTables[] = $tableName;
                            $this->info("  ✓ Created/verified table: {$tableName}");
                        } else {
                            $this->error("  ✗ Failed to create table: {$tableName}");
                        }
                    } else {
                        $this->info("  [DRY RUN] Would create table: {$tableName}");
                    }
                }
            }
            
            // Step 4: Migrate transactions by period
            $this->info("\nMigrating transactions by period...");
            $totalMigrated = 0;
            $totalSkipped = 0;
            
            foreach ($years as $year) {
                $periods = IBTransactionPeriodService::getYearPeriods($year);
                
                foreach ($periods as $period) {
                    $result = $this->migratePeriodTransactions($period, $dryRun);
                    $totalMigrated += $result['migrated'];
                    $totalSkipped += $result['skipped'];
                }
            }
            
            // Step 5: Summary
            $this->info("\n==========================================");
            $this->info("Migration Summary:");
            $this->info("  Total transactions found: {$totalTransactions}");
            $this->info("  Total migrated: {$totalMigrated}");
            $this->info("  Total skipped (duplicates): {$totalSkipped}");
            $this->info("  Quarter tables created: " . count($createdTables));
            
            if (!$dryRun) {
                $remainingTransactions = Transaction::where('type', 'ib_bonus')->count();
                $this->info("  Remaining in main table: {$remainingTransactions}");
                
                if ($remainingTransactions === 0) {
                    $this->info("✓ All IB transactions successfully migrated to quarter tables!");
                } else {
                    $this->warn("⚠ {$remainingTransactions} transactions remain in main table");
                }
            }
            
            // Step 6: Verify MultiLevelRebateDistribution integration
            $this->info("\nVerifying system integration...");
            $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
            $currentTable = IBTransactionPeriodService::getTableName($currentPeriod);
            $this->info("  Current period: " . IBTransactionPeriodService::getPeriodName($currentPeriod));
            $this->info("  Current table: {$currentTable}");
            $this->info("  ✓ MultiLevelRebateDistribution will now save to: {$currentTable}");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Migration failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
    
    /**
     * Migrate transactions for a specific period
     *
     * @param string $period
     * @param bool $dryRun
     * @return array
     */
    private function migratePeriodTransactions($period, $dryRun = false)
    {
        $dateRange = IBTransactionPeriodService::getPeriodDateRange($period);
        $tableName = IBTransactionPeriodService::getTableName($period);
        $periodName = IBTransactionPeriodService::getPeriodName($period);
        
        // Count transactions for this period
        $periodTransactions = Transaction::where('type', 'ib_bonus')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();
            
        if ($periodTransactions === 0) {
            $this->info("  No transactions for {$periodName}");
            return ['migrated' => 0, 'skipped' => 0];
        }
        
        $this->info("  Processing {$periodTransactions} transactions for {$periodName}");
        
        if ($dryRun) {
            $this->info("    [DRY RUN] Would migrate {$periodTransactions} transactions to {$tableName}");
            return ['migrated' => $periodTransactions, 'skipped' => 0];
        }
        
        $migrated = 0;
        $skipped = 0;
        $chunkSize = 500;
        
        // Create progress bar
        $progressBar = $this->output->createProgressBar($periodTransactions);
        $progressBar->start();
        
        Transaction::where('type', 'ib_bonus')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->orderBy('id')
            ->chunk($chunkSize, function ($transactions) use ($tableName, &$migrated, &$skipped, $progressBar) {
                
                // Check for existing duplicates
                $chunkTnxNumbers = $transactions->pluck('tnx')->toArray();
                $existingTnxNumbers = DB::table($tableName)
                    ->whereIn('tnx', $chunkTnxNumbers)
                    ->pluck('tnx')
                    ->toArray();
                
                $insertData = [];
                $deleteIds = [];
                
                foreach ($transactions as $transaction) {
                    $progressBar->advance();
                    
                    if (in_array($transaction->tnx, $existingTnxNumbers)) {
                        $skipped++;
                        continue;
                    }
                    
                    $deleteIds[] = $transaction->id;
                    $insertData[] = [
                        'user_id' => $transaction->user_id,
                        'from_user_id' => $transaction->from_user_id,
                        'from_model' => $transaction->from_model,
                        'target_id' => $transaction->target_id,
                        'target_type' => $transaction->target_type,
                        'is_level' => $transaction->is_level,
                        'tnx' => $transaction->tnx,
                        'description' => $transaction->description,
                        'amount' => $transaction->amount,
                        'type' => $transaction->type,
                        'charge' => $transaction->charge,
                        'final_amount' => $transaction->final_amount,
                        'method' => $transaction->method,
                        'pay_currency' => $transaction->pay_currency,
                        'pay_amount' => $transaction->pay_amount,
                        'manual_field_data' => $transaction->manual_field_data,
                        'approval_cause' => $transaction->approval_cause,
                        'action_by' => $transaction->action_by,
                        'status' => $transaction->status,
                        'created_at' => Carbon::parse($transaction->created_at)->toDateTimeString(),
                        'updated_at' => Carbon::parse($transaction->updated_at)->toDateTimeString(),
                    ];
                }
                
                if (!empty($insertData)) {
                    DB::beginTransaction();
                    try {
                        // Insert in sub-chunks
                        foreach (array_chunk($insertData, 250) as $subChunk) {
                            DB::table($tableName)->insert($subChunk);
                        }
                        
                        // Delete from main table
                        foreach (array_chunk($deleteIds, 250) as $subChunk) {
                            Transaction::whereIn('id', $subChunk)->delete();
                        }
                        
                        DB::commit();
                        $migrated += count($insertData);
                        
                    } catch (\Exception $e) {
                        DB::rollback();
                        throw $e;
                    }
                }
                
                // Memory cleanup
                unset($insertData, $deleteIds, $existingTnxNumbers, $chunkTnxNumbers);
                gc_collect_cycles();
            });
            
        $progressBar->finish();
        $this->newLine();
        $this->info("    ✓ Migrated {$migrated} transactions, skipped {$skipped} duplicates");
        
        return ['migrated' => $migrated, 'skipped' => $skipped];
    }
}
