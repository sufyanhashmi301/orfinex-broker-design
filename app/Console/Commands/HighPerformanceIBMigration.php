<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\IBTransactionService;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HighPerformanceIBMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:high-performance-migration 
                            {--chunk-size=1000 : Number of records to process per chunk}
                            {--batch-size=250 : Number of records to insert per batch}
                            {--memory-limit=512M : Memory limit for the process}
                            {--dry-run : Run without making actual changes}
                            {--resume-from-id= : Resume migration from specific transaction ID}
                            {--parallel-workers=1 : Number of parallel workers (future enhancement)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'High-performance migration for large volumes of IB transactions (optimized for 25+ lakh records)';

    private $stats = [
        'total_processed' => 0,
        'total_migrated' => 0,
        'total_skipped' => 0,
        'start_time' => null,
        'memory_peak' => 0,
        'chunks_processed' => 0
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->stats['start_time'] = microtime(true);
        
        // Set memory limit
        $memoryLimit = $this->option('memory-limit');
        ini_set('memory_limit', $memoryLimit);
        
        $chunkSize = (int) $this->option('chunk-size');
        $batchSize = (int) $this->option('batch-size');
        $dryRun = $this->option('dry-run');
        $resumeFromId = $this->option('resume-from-id');
        
        $this->info("🚀 High-Performance IB Transactions Migration");
        $this->info("============================================");
        $this->info("Memory Limit: {$memoryLimit}");
        $this->info("Chunk Size: {$chunkSize}");
        $this->info("Batch Size: {$batchSize}");
        
        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No actual changes will be made");
        }
        
        if ($resumeFromId) {
            $this->info("📍 Resuming from Transaction ID: {$resumeFromId}");
        }
        
        try {
            // Step 1: Pre-migration analysis
            $this->performPreMigrationAnalysis($resumeFromId);
            
            // Step 2: Create all necessary quarter tables
            $this->createQuarterTables($dryRun);
            
            // Step 3: Optimize database settings for bulk operations
            $this->optimizeDatabaseSettings();
            
            // Step 4: Perform high-performance migration
            $this->performHighPerformanceMigration($chunkSize, $batchSize, $dryRun, $resumeFromId);
            
            // Step 5: Restore database settings
            $this->restoreDatabaseSettings();
            
            // Step 6: Final verification and statistics
            $this->displayFinalStatistics();
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Migration failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            $this->restoreDatabaseSettings();
            return Command::FAILURE;
        }
    }
    
    /**
     * Perform pre-migration analysis
     */
    private function performPreMigrationAnalysis($resumeFromId = null)
    {
        $this->info("\n📊 Pre-Migration Analysis");
        $this->info("-------------------------");
        
        // Count total IB transactions
        $query = Transaction::where('type', 'ib_bonus');
        if ($resumeFromId) {
            $query->where('id', '>=', $resumeFromId);
        }
        
        $totalTransactions = $query->count();
        $this->info("Total IB transactions to migrate: " . number_format($totalTransactions));
        
        if ($totalTransactions === 0) {
            $this->info("✅ No IB transactions to migrate.");
            exit(0);
        }
        
        // Analyze by year and period
        $yearAnalysis = $query->selectRaw('YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year')
            ->orderBy('year')
            ->get();
            
        $this->info("\nTransactions by Year:");
        foreach ($yearAnalysis as $year) {
            $this->info("  {$year->year}: " . number_format($year->count) . " transactions");
        }
        
        // Memory estimation
        $estimatedMemoryPerRecord = 2048; // bytes (conservative estimate)
        $chunkSize = (int) $this->option('chunk-size');
        $estimatedMemoryUsage = ($chunkSize * $estimatedMemoryPerRecord) / 1024 / 1024; // MB
        
        $this->info("\nMemory Estimation:");
        $this->info("  Estimated memory per chunk: " . round($estimatedMemoryUsage, 2) . " MB");
        
        // Time estimation
        $recordsPerSecond = 500; // Conservative estimate
        $estimatedTimeSeconds = $totalTransactions / $recordsPerSecond;
        $estimatedTimeMinutes = round($estimatedTimeSeconds / 60, 2);
        
        $this->info("  Estimated processing time: {$estimatedTimeMinutes} minutes");
        
        // Database size analysis
        $this->analyzeTableSizes();
    }
    
    /**
     * Analyze current table sizes
     */
    private function analyzeTableSizes()
    {
        try {
            $tableSize = DB::select("
                SELECT 
                    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'size_mb'
                FROM information_schema.tables 
                WHERE table_schema = DATABASE() 
                AND table_name = 'transactions'
            ");
            
            if (!empty($tableSize)) {
                $this->info("  Current transactions table size: {$tableSize[0]->size_mb} MB");
            }
        } catch (\Exception $e) {
            $this->warn("  Could not analyze table size: " . $e->getMessage());
        }
    }
    
    /**
     * Create all necessary quarter tables
     */
    private function createQuarterTables($dryRun = false)
    {
        $this->info("\n🏗️  Creating Quarter Tables");
        $this->info("---------------------------");
        
        // Get all years with IB transactions
        $years = Transaction::where('type', 'ib_bonus')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year')
            ->pluck('year');
            
        $tablesCreated = 0;
        
        foreach ($years as $year) {
            $periods = IBTransactionPeriodService::getYearPeriods($year);
            
            foreach ($periods as $period) {
                $tableName = IBTransactionPeriodService::getTableName($period);
                $periodName = IBTransactionPeriodService::getPeriodName($period);
                
                if (!$dryRun) {
                    if (!Schema::hasTable($tableName)) {
                        $result = IBTransactionService::createIBTransactionTable4Month($period);
                        if ($result) {
                            $this->info("  ✅ Created: {$tableName} ({$periodName})");
                            $tablesCreated++;
                        } else {
                            $this->error("  ❌ Failed to create: {$tableName}");
                        }
                    } else {
                        $this->info("  ✅ Exists: {$tableName} ({$periodName})");
                    }
                } else {
                    $this->info("  [DRY RUN] Would create: {$tableName} ({$periodName})");
                }
            }
        }
        
        if (!$dryRun) {
            $this->info("Quarter tables ready: {$tablesCreated} created/verified");
        }
    }
    
    /**
     * Optimize database settings for bulk operations
     */
    private function optimizeDatabaseSettings()
    {
        $this->info("\n⚡ Optimizing Database Settings");
        $this->info("------------------------------");
        
        try {
            // Disable foreign key checks for faster inserts
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Optimize for bulk inserts
            DB::statement('SET SESSION sql_mode = ""');
            DB::statement('SET SESSION innodb_autoinc_lock_mode = 2');
            
            // Increase bulk insert buffer
            DB::statement('SET SESSION bulk_insert_buffer_size = 256 * 1024 * 1024'); // 256MB
            
            $this->info("✅ Database optimized for bulk operations");
            
        } catch (\Exception $e) {
            $this->warn("⚠️  Could not optimize database settings: " . $e->getMessage());
        }
    }
    
    /**
     * Restore database settings
     */
    private function restoreDatabaseSettings()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->info("✅ Database settings restored");
        } catch (\Exception $e) {
            $this->warn("⚠️  Could not restore database settings: " . $e->getMessage());
        }
    }
    
    /**
     * Perform high-performance migration
     */
    private function performHighPerformanceMigration($chunkSize, $batchSize, $dryRun, $resumeFromId)
    {
        $this->info("\n🚄 High-Performance Migration");
        $this->info("-----------------------------");
        
        // Build base query
        $query = Transaction::where('type', 'ib_bonus')->orderBy('id');
        
        if ($resumeFromId) {
            $query->where('id', '>=', $resumeFromId);
        }
        
        $totalTransactions = $query->count();
        
        if ($totalTransactions === 0) {
            $this->info("No transactions to migrate.");
            return;
        }
        
        // Create progress bar
        $progressBar = $this->output->createProgressBar($totalTransactions);
        $progressBar->setFormat('verbose');
        $progressBar->start();
        
        // Process in chunks with optimized memory management
        $query->chunk($chunkSize, function ($transactions) use ($batchSize, $dryRun, $progressBar) {
            $this->processChunk($transactions, $batchSize, $dryRun, $progressBar);
            
            // Memory management
            $this->manageMemory();
        });
        
        $progressBar->finish();
        $this->newLine();
    }
    
    /**
     * Process a chunk of transactions
     */
    private function processChunk($transactions, $batchSize, $dryRun, $progressBar)
    {
        $this->stats['chunks_processed']++;
        
        // Group transactions by period for efficient batch processing
        $transactionsByPeriod = [];
        $transactionIdsToDelete = [];
        
        foreach ($transactions as $transaction) {
            $this->stats['total_processed']++;
            $progressBar->advance();
            
            // Determine period for this transaction
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
        
        // Process each period's transactions
        foreach ($transactionsByPeriod as $tableName => $periodTransactions) {
            $this->processPeriodTransactions($tableName, $periodTransactions, $batchSize, $dryRun);
        }
    }
    
    /**
     * Process transactions for a specific period
     */
    private function processPeriodTransactions($tableName, $periodTransactions, $batchSize, $dryRun)
    {
        if (empty($periodTransactions)) {
            return;
        }
        
        // Check for existing duplicates in batch
        $tnxNumbers = array_column(array_column($periodTransactions, 'data'), 'tnx');
        $existingTnxNumbers = [];
        
        if (!$dryRun) {
            $existingTnxNumbers = DB::table($tableName)
                ->whereIn('tnx', $tnxNumbers)
                ->pluck('tnx')
                ->toArray();
        }
        
        // Filter out duplicates and prepare for insertion
        $insertData = [];
        $deleteIds = [];
        
        foreach ($periodTransactions as $transaction) {
            if (in_array($transaction['data']['tnx'], $existingTnxNumbers)) {
                $this->stats['total_skipped']++;
                continue;
            }
            
            $insertData[] = $transaction['data'];
            $deleteIds[] = $transaction['id'];
        }
        
        if (empty($insertData)) {
            return;
        }
        
        if (!$dryRun) {
            // Process in batches using transaction for safety
            DB::beginTransaction();
            
            try {
                // Insert in smaller batches
                foreach (array_chunk($insertData, $batchSize) as $batch) {
                    DB::table($tableName)->insert($batch);
                }
                
                // Delete from main table in batches
                foreach (array_chunk($deleteIds, $batchSize) as $batch) {
                    Transaction::whereIn('id', $batch)->delete();
                }
                
                DB::commit();
                $this->stats['total_migrated'] += count($insertData);
                
            } catch (\Exception $e) {
                DB::rollback();
                throw new \Exception("Failed to process batch for {$tableName}: " . $e->getMessage());
            }
        } else {
            $this->stats['total_migrated'] += count($insertData);
        }
    }
    
    /**
     * Manage memory usage
     */
    private function manageMemory()
    {
        // Track peak memory usage
        $currentMemory = memory_get_peak_usage(true);
        if ($currentMemory > $this->stats['memory_peak']) {
            $this->stats['memory_peak'] = $currentMemory;
        }
        
        // Force garbage collection every 10 chunks
        if ($this->stats['chunks_processed'] % 10 === 0) {
            gc_collect_cycles();
            
            // Display memory status
            $memoryMB = round($currentMemory / 1024 / 1024, 2);
            $this->newLine();
            $this->info("Memory usage: {$memoryMB} MB | Chunks processed: {$this->stats['chunks_processed']}");
        }
    }
    
    /**
     * Display final statistics
     */
    private function displayFinalStatistics()
    {
        $endTime = microtime(true);
        $totalTime = $endTime - $this->stats['start_time'];
        $peakMemoryMB = round($this->stats['memory_peak'] / 1024 / 1024, 2);
        $recordsPerSecond = round($this->stats['total_processed'] / $totalTime, 2);
        
        $this->info("\n📈 Migration Statistics");
        $this->info("======================");
        $this->info("Total processed: " . number_format($this->stats['total_processed']));
        $this->info("Total migrated: " . number_format($this->stats['total_migrated']));
        $this->info("Total skipped: " . number_format($this->stats['total_skipped']));
        $this->info("Chunks processed: " . number_format($this->stats['chunks_processed']));
        $this->info("Processing time: " . round($totalTime / 60, 2) . " minutes");
        $this->info("Peak memory usage: {$peakMemoryMB} MB");
        $this->info("Processing speed: {$recordsPerSecond} records/second");
        
        // Check remaining transactions
        $remaining = Transaction::where('type', 'ib_bonus')->count();
        $this->info("Remaining in main table: " . number_format($remaining));
        
        if ($remaining === 0) {
            $this->info("🎉 ALL TRANSACTIONS SUCCESSFULLY MIGRATED!");
        } else {
            $this->warn("⚠️  {$remaining} transactions still need migration");
        }
    }
}
