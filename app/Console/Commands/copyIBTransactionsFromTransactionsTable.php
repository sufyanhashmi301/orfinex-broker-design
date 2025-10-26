<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\IBTransaction;
use App\Services\IBTransactionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class copyIBTransactionsFromTransactionsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:ib-transactions-from-transactions-table {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy IB transactions from transactions table to ib_transactions table and delete them from source';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currentYear = $this->argument('year') ?? Carbon::now()->year;
        
        // Create the yearly table if it doesn't exist
        $tableCreated = IBTransactionService::createIBTransactionTable($currentYear);
        if (!$tableCreated) {
            $this->error("Failed to create IB transactions table for {$currentYear}");
            return Command::FAILURE;
        }

        // Check if there are any transactions to process
        $totalTransactions = Transaction::where('type', 'ib_bonus')
            ->whereYear('created_at', $currentYear)
            ->count();

        if ($totalTransactions === 0) {
            $this->info("No IB transactions found for {$currentYear}.");
            return Command::SUCCESS;
        }

        $this->info("Found {$totalTransactions} IB transactions to process for {$currentYear}.");
        
        // Memory-optimized processing variables
        $chunkSize = 500; // Smaller chunk size for memory optimization
        $totalProcessed = 0;
        $totalCopied = 0;
        $totalSkipped = 0;
        $processedTransactionIds = [];

        // Create a progress bar for large datasets
        $progressBar = $this->output->createProgressBar($totalTransactions);
        $progressBar->start();

        // Process transactions in chunks to avoid memory issues
        Transaction::where('type', 'ib_bonus')
            ->whereYear('created_at', $currentYear)
            ->orderBy('id')
            ->chunk($chunkSize, function ($transactions) use ($currentYear, &$totalCopied, &$totalSkipped, &$totalProcessed, &$processedTransactionIds, $progressBar) {
                
                // Get transaction numbers from current chunk for duplication check
                $chunkTnxNumbers = $transactions->pluck('tnx')->toArray();
                
                // Check for existing duplicates in batch (more efficient than individual checks)
                $existingTnxNumbers = DB::table('ib_transactions_' . $currentYear)
                    ->whereIn('tnx', $chunkTnxNumbers)
                    ->pluck('tnx')
                    ->toArray();

                $insertData = [];
                $chunkTransactionIds = [];
                
                foreach ($transactions as $transaction) {
                    $totalProcessed++;
                    $progressBar->advance();
                    
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
                            DB::table('ib_transactions_' . $currentYear)->insert($subChunk);
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

        $progressBar->finish();
        $this->newLine();

        // Final summary
        $successMessage = "Successfully processed {$totalProcessed} transactions for {$currentYear}. ";
        $successMessage .= "Copied and deleted: {$totalCopied}, Skipped duplicates: {$totalSkipped}";
        
        $this->info($successMessage);
        
        // Memory cleanup
        unset($processedTransactionIds);
        gc_collect_cycles();

        return Command::SUCCESS;
    }
}
