<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\IBTransactionService;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CopyIBTransactions4Month extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:ib-transactions-4month {period?} {--auto} {--all-periods}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy IB transactions from transactions table to 4-month based ib_transactions table and delete them from source';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $period = $this->argument('period');
            $isAuto = $this->option('auto');
            $allPeriods = $this->option('all-periods');

            if ($allPeriods) {
                return $this->processAllPeriods();
            }

            // If no period provided, use current period
            if (!$period) {
                $period = IBTransactionPeriodService::getCurrentPeriod();
                $this->info("No period specified, using current period: {$period}");
            }

            // Validate period format
            if (!$this->isValidPeriod($period)) {
                $this->error('Invalid period format. Use format like: 2025_q1, 2025_q2, or 2025_q3');
                return Command::FAILURE;
            }

            return $this->processPeriod($period, $isAuto);

        } catch (\Exception $e) {
            $this->error("Error processing IB transactions: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Process all available periods
     *
     * @return int
     */
    private function processAllPeriods()
    {
        $this->info("Processing all available periods...");
        
        // Get all years that have IB transactions
        $years = Transaction::where('type', 'ib_bonus')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year')
            ->pluck('year');

        $totalProcessed = 0;
        $totalFailed = 0;

        foreach ($years as $year) {
            $periods = IBTransactionPeriodService::getYearPeriods($year);
            
            foreach ($periods as $period) {
                $this->info("Processing period: " . IBTransactionPeriodService::getPeriodName($period));
                
                $result = $this->processPeriod($period, false, false);
                
                if ($result === Command::SUCCESS) {
                    $totalProcessed++;
                } else {
                    $totalFailed++;
                }
            }
        }

        $this->info("Completed processing all periods. Processed: {$totalProcessed}, Failed: {$totalFailed}");
        return $totalFailed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Process a specific period
     *
     * @param string $period
     * @param bool $isAuto
     * @param bool $showMessages
     * @return int
     */
    private function processPeriod($period, $isAuto = false, $showMessages = true)
    {
        // Create the period table if it doesn't exist
        $tableCreated = IBTransactionService::createIBTransactionTable4Month($period);
        if (!$tableCreated) {
            if ($showMessages) {
                $this->error("Failed to create IB transactions table for {$period}");
            }
            return Command::FAILURE;
        }

        // Get date range for the period
        $dateRange = IBTransactionPeriodService::getPeriodDateRange($period);
        $tableName = IBTransactionPeriodService::getTableName($period);

        // Check if there are any transactions to process
        $totalTransactions = Transaction::where('type', 'ib_bonus')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();

        if ($totalTransactions === 0) {
            if ($showMessages) {
                $periodName = IBTransactionPeriodService::getPeriodName($period);
                $this->info("No IB transactions found for {$periodName}.");
            }
            return Command::SUCCESS;
        }

        if ($showMessages) {
            $periodName = IBTransactionPeriodService::getPeriodName($period);
            $this->info("Found {$totalTransactions} IB transactions to process for {$periodName}.");
        }
        
        // Memory-optimized processing variables
        $chunkSize = 500; // Smaller chunk size for memory optimization
        $totalProcessed = 0;
        $totalCopied = 0;
        $totalSkipped = 0;
        $processedTransactionIds = [];

        // Create a progress bar for large datasets
        $progressBar = null;
        if ($showMessages) {
            $progressBar = $this->output->createProgressBar($totalTransactions);
            $progressBar->start();
        }

        // Process transactions in chunks to avoid memory issues
        Transaction::where('type', 'ib_bonus')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->orderBy('id')
            ->chunk($chunkSize, function ($transactions) use ($tableName, &$totalCopied, &$totalSkipped, &$totalProcessed, &$processedTransactionIds, $progressBar) {
                
                // Get transaction numbers from current chunk for duplication check
                $chunkTnxNumbers = $transactions->pluck('tnx')->toArray();
                
                // Check for existing duplicates in batch (more efficient than individual checks)
                $existingTnxNumbers = DB::table($tableName)
                    ->whereIn('tnx', $chunkTnxNumbers)
                    ->pluck('tnx')
                    ->toArray();

                $insertData = [];
                $chunkTransactionIds = [];
                
                foreach ($transactions as $transaction) {
                    $totalProcessed++;
                    if ($progressBar) {
                        $progressBar->advance();
                    }
                    
                    // Check for duplication
                    if (in_array($transaction->tnx, $existingTnxNumbers)) {
                        $totalSkipped++;
                        continue;
                    }

                    $chunkTransactionIds[] = $transaction->id;
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

                // Process the chunk if there's data to insert
                if (!empty($insertData)) {
                    DB::beginTransaction();
                    
                    try {
                        // Insert data in smaller sub-chunks for better memory management
                        foreach (array_chunk($insertData, 250) as $subChunk) {
                            DB::table($tableName)->insert($subChunk);
                        }
                        
                        // Delete the copied transactions from source table in sub-chunks
                        foreach (array_chunk($chunkTransactionIds, 250) as $subChunk) {
                            Transaction::whereIn('id', $subChunk)->delete();
                        }
                        
                        // Commit the chunk transaction
                        DB::commit();
                        
                        $totalCopied += count($insertData);
                        $processedTransactionIds = array_merge($processedTransactionIds, $chunkTransactionIds);
                        
                    } catch (\Exception $e) {
                        // Rollback the chunk transaction on error
                        DB::rollback();
                        throw new \Exception("Error processing chunk: " . $e->getMessage());
                    }
                }

                // Clear memory after each chunk
                unset($insertData, $chunkTransactionIds, $existingTnxNumbers, $chunkTnxNumbers);
                
                // Force garbage collection for large datasets
                if ($totalProcessed % 5000 === 0) {
                    gc_collect_cycles();
                }
            });

        if ($progressBar) {
            $progressBar->finish();
            $this->newLine();
        }

        // Final summary
        if ($showMessages) {
            $periodName = IBTransactionPeriodService::getPeriodName($period);
            $successMessage = "Successfully processed {$totalProcessed} transactions for {$periodName}. ";
            $successMessage .= "Copied and deleted: {$totalCopied}, Skipped duplicates: {$totalSkipped}";
            
            $this->info($successMessage);
        }
        
        // Memory cleanup
        unset($processedTransactionIds);
        gc_collect_cycles();

        // If auto mode and we should create next period table, create it
        if ($isAuto && IBTransactionPeriodService::shouldCreateNextPeriodTable()) {
            $nextPeriod = IBTransactionPeriodService::getNextPeriod($period);
            $nextTableCreated = IBTransactionService::createIBTransactionTable4Month($nextPeriod);
            
            if ($nextTableCreated && $showMessages) {
                $nextPeriodName = IBTransactionPeriodService::getPeriodName($nextPeriod);
                $this->info("Next period table for {$nextPeriodName} created successfully.");
            } elseif (!$nextTableCreated && $showMessages) {
                $this->warn("Failed to create next period table for {$nextPeriod}.");
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Validate period format
     *
     * @param string $period
     * @return bool
     */
    private function isValidPeriod($period)
    {
        return preg_match('/^\d{4}_q[1-3]$/', $period);
    }
}

