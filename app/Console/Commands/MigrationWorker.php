<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MigrationWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:migration-worker {start_id} {end_id} {worker_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Worker process for parallel IB transactions migration';

    private $statusFile;
    private $stats = [
        'processed' => 0,
        'migrated' => 0,
        'skipped' => 0
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startId = (int) $this->argument('start_id');
        $endId = (int) $this->argument('end_id');
        $workerId = (int) $this->argument('worker_id');
        
        $this->statusFile = storage_path("app/migration_worker_{$workerId}.json");
        
        try {
            $this->updateStatus('running');
            
            // Optimize database settings for this worker
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('SET SESSION sql_mode = ""');
            
            // Process transactions in the assigned ID range
            $chunkSize = 500;
            
            Transaction::where('type', 'ib_bonus')
                ->whereBetween('id', [$startId, $endId])
                ->orderBy('id')
                ->chunk($chunkSize, function ($transactions) {
                    $this->processChunk($transactions);
                    $this->updateStatus('running');
                });
            
            // Restore database settings
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            $this->updateStatus('completed');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->updateStatus('failed', $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Process a chunk of transactions
     */
    private function processChunk($transactions)
    {
        // Group by period for efficient batch processing
        $transactionsByPeriod = [];
        
        foreach ($transactions as $transaction) {
            $this->stats['processed']++;
            
            $transactionDate = Carbon::parse($transaction->created_at);
            $period = IBTransactionPeriodService::getCurrentPeriod($transactionDate);
            $tableName = IBTransactionPeriodService::getTableName($period);
            
            if (!isset($transactionsByPeriod[$tableName])) {
                $transactionsByPeriod[$tableName] = [];
            }
            
            $transactionsByPeriod[$tableName][] = [
                'id' => $transaction->id,
                'data' => [
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
                ]
            ];
        }
        
        // Process each period
        foreach ($transactionsByPeriod as $tableName => $periodTransactions) {
            $this->processPeriodTransactions($tableName, $periodTransactions);
        }
        
        // Memory cleanup
        unset($transactionsByPeriod);
        gc_collect_cycles();
    }
    
    /**
     * Process transactions for a specific period
     */
    private function processPeriodTransactions($tableName, $periodTransactions)
    {
        if (empty($periodTransactions)) {
            return;
        }
        
        // Check for duplicates
        $tnxNumbers = array_column(array_column($periodTransactions, 'data'), 'tnx');
        $existingTnxNumbers = DB::table($tableName)
            ->whereIn('tnx', $tnxNumbers)
            ->pluck('tnx')
            ->toArray();
        
        $insertData = [];
        $deleteIds = [];
        
        foreach ($periodTransactions as $transaction) {
            if (in_array($transaction['data']['tnx'], $existingTnxNumbers)) {
                $this->stats['skipped']++;
                continue;
            }
            
            $insertData[] = $transaction['data'];
            $deleteIds[] = $transaction['id'];
        }
        
        if (empty($insertData)) {
            return;
        }
        
        // Process in transaction for safety
        DB::beginTransaction();
        
        try {
            // Insert in batches
            $batchSize = 250;
            foreach (array_chunk($insertData, $batchSize) as $batch) {
                DB::table($tableName)->insert($batch);
            }
            
            // Delete from main table
            foreach (array_chunk($deleteIds, $batchSize) as $batch) {
                Transaction::whereIn('id', $batch)->delete();
            }
            
            DB::commit();
            $this->stats['migrated'] += count($insertData);
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Update worker status
     */
    private function updateStatus($status, $error = null)
    {
        $statusData = [
            'status' => $status,
            'processed' => $this->stats['processed'],
            'migrated' => $this->stats['migrated'],
            'skipped' => $this->stats['skipped'],
            'timestamp' => time()
        ];
        
        if ($error) {
            $statusData['error'] = $error;
        }
        
        file_put_contents($this->statusFile, json_encode($statusData));
    }
}
