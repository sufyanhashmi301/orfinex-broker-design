<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\IBTransactionService;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ParallelIBMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:parallel-migration 
                            {--workers=4 : Number of parallel workers}
                            {--chunk-size=2000 : Records per worker chunk}
                            {--dry-run : Run without making actual changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parallel processing migration for maximum performance with large datasets (25+ lakh records)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $workers = (int) $this->option('workers');
        $chunkSize = (int) $this->option('chunk-size');
        $dryRun = $this->option('dry-run');
        
        $this->info("🚀 Parallel IB Transactions Migration");
        $this->info("====================================");
        $this->info("Workers: {$workers}");
        $this->info("Chunk Size: {$chunkSize}");
        
        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE");
        }
        
        try {
            // Step 1: Analyze and prepare
            $totalTransactions = Transaction::where('type', 'ib_bonus')->count();
            
            if ($totalTransactions === 0) {
                $this->info("✅ No IB transactions to migrate.");
                return Command::SUCCESS;
            }
            
            $this->info("Total transactions to migrate: " . number_format($totalTransactions));
            
            // Step 2: Create quarter tables
            $this->createQuarterTables($dryRun);
            
            // Step 3: Calculate work distribution
            $workChunks = $this->calculateWorkDistribution($totalTransactions, $workers, $chunkSize);
            
            $this->info("Work distribution:");
            foreach ($workChunks as $i => $chunk) {
                $this->info("  Worker " . ($i + 1) . ": ID {$chunk['start_id']} to {$chunk['end_id']} (" . number_format($chunk['count']) . " records)");
            }
            
            // Step 4: Execute parallel migration
            if (!$dryRun) {
                $this->executeParallelMigration($workChunks);
            } else {
                $this->info("\n[DRY RUN] Would execute parallel migration with {$workers} workers");
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Parallel migration failed: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Create quarter tables
     */
    private function createQuarterTables($dryRun)
    {
        $this->info("\n🏗️  Preparing Quarter Tables");
        $this->info("----------------------------");
        
        $years = Transaction::where('type', 'ib_bonus')
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year');
            
        foreach ($years as $year) {
            $periods = IBTransactionPeriodService::getYearPeriods($year);
            
            foreach ($periods as $period) {
                if (!$dryRun) {
                    IBTransactionService::createIBTransactionTable4Month($period);
                }
                $tableName = IBTransactionPeriodService::getTableName($period);
                $this->info("  ✅ {$tableName}");
            }
        }
    }
    
    /**
     * Calculate work distribution for parallel processing
     */
    private function calculateWorkDistribution($totalTransactions, $workers, $chunkSize)
    {
        // Get min and max IDs for range-based distribution
        $minMaxIds = Transaction::where('type', 'ib_bonus')
            ->selectRaw('MIN(id) as min_id, MAX(id) as max_id')
            ->first();
            
        $minId = $minMaxIds->min_id;
        $maxId = $minMaxIds->max_id;
        $idRange = $maxId - $minId + 1;
        
        $recordsPerWorker = ceil($totalTransactions / $workers);
        $idRangePerWorker = ceil($idRange / $workers);
        
        $workChunks = [];
        
        for ($i = 0; $i < $workers; $i++) {
            $startId = $minId + ($i * $idRangePerWorker);
            $endId = min($startId + $idRangePerWorker - 1, $maxId);
            
            // Count actual records in this range
            $actualCount = Transaction::where('type', 'ib_bonus')
                ->whereBetween('id', [$startId, $endId])
                ->count();
                
            if ($actualCount > 0) {
                $workChunks[] = [
                    'worker_id' => $i + 1,
                    'start_id' => $startId,
                    'end_id' => $endId,
                    'count' => $actualCount
                ];
            }
        }
        
        return $workChunks;
    }
    
    /**
     * Execute parallel migration
     */
    private function executeParallelMigration($workChunks)
    {
        $this->info("\n🚄 Executing Parallel Migration");
        $this->info("-------------------------------");
        
        $startTime = microtime(true);
        
        // Create temporary files for worker communication
        $statusFiles = [];
        foreach ($workChunks as $chunk) {
            $statusFile = storage_path("app/migration_worker_{$chunk['worker_id']}.json");
            $statusFiles[$chunk['worker_id']] = $statusFile;
            
            // Initialize status file
            file_put_contents($statusFile, json_encode([
                'status' => 'starting',
                'processed' => 0,
                'migrated' => 0,
                'skipped' => 0,
                'start_time' => time()
            ]));
        }
        
        // Start workers
        $processes = [];
        foreach ($workChunks as $chunk) {
            $command = "php artisan ib:migration-worker {$chunk['start_id']} {$chunk['end_id']} {$chunk['worker_id']}";
            
            if (PHP_OS_FAMILY === 'Windows') {
                $process = popen("start /B {$command}", 'r');
            } else {
                $process = popen("{$command} > /dev/null 2>&1 &", 'r');
            }
            
            $processes[$chunk['worker_id']] = $process;
            $this->info("  🚀 Started Worker {$chunk['worker_id']} (ID range: {$chunk['start_id']}-{$chunk['end_id']})");
        }
        
        // Monitor progress
        $this->monitorWorkerProgress($statusFiles, $workChunks);
        
        // Cleanup
        foreach ($processes as $process) {
            if (is_resource($process)) {
                pclose($process);
            }
        }
        
        foreach ($statusFiles as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        
        $endTime = microtime(true);
        $totalTime = round(($endTime - $startTime) / 60, 2);
        
        $this->info("\n✅ Parallel migration completed in {$totalTime} minutes");
    }
    
    /**
     * Monitor worker progress
     */
    private function monitorWorkerProgress($statusFiles, $workChunks)
    {
        $totalRecords = array_sum(array_column($workChunks, 'count'));
        $progressBar = $this->output->createProgressBar($totalRecords);
        $progressBar->start();
        
        $allCompleted = false;
        $lastTotalProcessed = 0;
        
        while (!$allCompleted) {
            sleep(2); // Check every 2 seconds
            
            $totalProcessed = 0;
            $totalMigrated = 0;
            $totalSkipped = 0;
            $completedWorkers = 0;
            
            foreach ($statusFiles as $workerId => $statusFile) {
                if (file_exists($statusFile)) {
                    $status = json_decode(file_get_contents($statusFile), true);
                    
                    if ($status) {
                        $totalProcessed += $status['processed'];
                        $totalMigrated += $status['migrated'];
                        $totalSkipped += $status['skipped'];
                        
                        if ($status['status'] === 'completed') {
                            $completedWorkers++;
                        }
                    }
                } else {
                    // File doesn't exist, assume worker completed
                    $completedWorkers++;
                }
            }
            
            // Update progress bar
            $progressIncrease = $totalProcessed - $lastTotalProcessed;
            if ($progressIncrease > 0) {
                $progressBar->advance($progressIncrease);
                $lastTotalProcessed = $totalProcessed;
            }
            
            // Check if all workers completed
            if ($completedWorkers >= count($workChunks)) {
                $allCompleted = true;
            }
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("Final Statistics:");
        $this->info("  Processed: " . number_format($totalProcessed));
        $this->info("  Migrated: " . number_format($totalMigrated));
        $this->info("  Skipped: " . number_format($totalSkipped));
    }
}
