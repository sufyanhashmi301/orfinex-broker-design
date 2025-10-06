<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class AutoIBMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:auto-migration 
                            {--chunk-size=2000 : Records per chunk}
                            {--batch-size=500 : Records per batch insert}
                            {--memory-limit=2G : PHP memory limit}
                            {--max-iterations=50 : Maximum iterations to prevent infinite loop}
                            {--check-interval=30 : Seconds to wait between iterations}
                            {--dry-run : Test without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically run high-performance migration until all IB transactions are processed';

    private $stats = [
        'total_iterations' => 0,
        'total_migrated' => 0,
        'start_time' => null,
        'start_count' => 0
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->stats['start_time'] = microtime(true);
        
        $chunkSize = $this->option('chunk-size');
        $batchSize = $this->option('batch-size');
        $memoryLimit = $this->option('memory-limit');
        $maxIterations = $this->option('max-iterations');
        $checkInterval = $this->option('check-interval');
        $dryRun = $this->option('dry-run');
        
        $this->displayHeader($dryRun);
        
        try {
            // Initial count
            $initialCount = Transaction::where('type', 'ib_bonus')->count();
            $this->stats['start_count'] = $initialCount;
            
            if ($initialCount === 0) {
                $this->info("✅ No IB transactions to migrate. System is ready!");
                return Command::SUCCESS;
            }
            
            $this->info("📊 Starting automatic migration for " . number_format($initialCount) . " IB transactions");
            $this->info("Settings: Chunk={$chunkSize}, Batch={$batchSize}, Memory={$memoryLimit}");
            $this->info("Max iterations: {$maxIterations}, Check interval: {$checkInterval}s");
            $this->newLine();
            
            // Main migration loop
            while ($this->stats['total_iterations'] < $maxIterations) {
                $this->stats['total_iterations']++;
                
                // Check remaining transactions
                $remainingCount = Transaction::where('type', 'ib_bonus')->count();
                
                if ($remainingCount === 0) {
                    $this->info("🎉 ALL TRANSACTIONS MIGRATED SUCCESSFULLY!");
                    break;
                }
                
                $this->info("🔄 Iteration {$this->stats['total_iterations']}: Processing {$remainingCount} remaining transactions...");
                
                // Run the high-performance migration
                $result = $this->runMigrationIteration($chunkSize, $batchSize, $memoryLimit, $dryRun);
                
                if ($result !== 0) {
                    $this->error("❌ Migration iteration failed. Stopping automatic migration.");
                    return Command::FAILURE;
                }
                
                // Check progress
                $newRemainingCount = Transaction::where('type', 'ib_bonus')->count();
                $processed = $remainingCount - $newRemainingCount;
                $this->stats['total_migrated'] += $processed;
                
                $this->info("✅ Iteration {$this->stats['total_iterations']} completed: {$processed} records processed");
                $this->info("📈 Progress: " . number_format($this->stats['total_migrated']) . " / " . number_format($initialCount) . " (" . round(($this->stats['total_migrated'] / $initialCount) * 100, 2) . "%)");
                
                // If no progress made, something might be wrong
                if ($processed === 0) {
                    $this->warn("⚠️  No records processed in this iteration. Checking system...");
                    
                    if ($this->stats['total_iterations'] >= 3) {
                        $this->error("❌ No progress for 3 iterations. Stopping to prevent infinite loop.");
                        $this->error("Please check for errors or run manually: php artisan ib:high-performance-migration --chunk-size={$chunkSize}");
                        return Command::FAILURE;
                    }
                }
                
                // Break if all done
                if ($newRemainingCount === 0) {
                    break;
                }
                
                // Wait before next iteration (except for dry run)
                if (!$dryRun && $newRemainingCount > 0) {
                    $this->info("⏳ Waiting {$checkInterval} seconds before next iteration...");
                    sleep($checkInterval);
                }
                
                $this->newLine();
            }
            
            // Final statistics
            $this->displayFinalStats($dryRun);
            
            // Check if we hit max iterations
            if ($this->stats['total_iterations'] >= $maxIterations) {
                $remaining = Transaction::where('type', 'ib_bonus')->count();
                if ($remaining > 0) {
                    $this->warn("⚠️  Reached maximum iterations ({$maxIterations}). {$remaining} transactions still remain.");
                    $this->warn("Run the command again or increase --max-iterations to continue.");
                    return Command::FAILURE;
                }
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Automatic migration failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            
            // Show recovery instructions
            $this->newLine();
            $this->warn("🔄 Recovery Options:");
            $this->warn("1. Check remaining transactions: php artisan tinker --execute=\"echo 'Remaining: ' . App\\Models\\Transaction::where('type', 'ib_bonus')->count() . PHP_EOL;\"");
            $this->warn("2. Resume with manual migration: php artisan ib:high-performance-migration --chunk-size={$chunkSize} --resume");
            $this->warn("3. Fix any datetime issues: php artisan ib:fix-transaction-dates");
            $this->warn("4. Restart auto migration: php artisan ib:auto-migration");
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Display header information
     */
    private function displayHeader($dryRun)
    {
        $this->info("╔══════════════════════════════════════════════════════════════╗");
        $this->info("║                 Automatic IB Migration                      ║");
        $this->info("║            Continuous High-Performance Processing           ║");
        $this->info("╚══════════════════════════════════════════════════════════════╝");
        
        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No actual changes will be made");
        }
        
        $this->newLine();
    }
    
    /**
     * Run a single migration iteration
     */
    private function runMigrationIteration($chunkSize, $batchSize, $memoryLimit, $dryRun)
    {
        $command = 'ib:high-performance-migration';
        $parameters = [
            '--chunk-size' => $chunkSize,
            '--batch-size' => $batchSize,
            '--memory-limit' => $memoryLimit
        ];
        
        if ($dryRun) {
            $parameters['--dry-run'] = true;
        }
        
        return Artisan::call($command, $parameters);
    }
    
    /**
     * Display final statistics
     */
    private function displayFinalStats($dryRun)
    {
        $endTime = microtime(true);
        $totalTime = $endTime - $this->stats['start_time'];
        $finalCount = Transaction::where('type', 'ib_bonus')->count();
        
        $this->newLine();
        $this->info("📊 Automatic Migration Summary");
        $this->info("==============================");
        $this->info("Started with: " . number_format($this->stats['start_count']) . " transactions");
        $this->info("Total migrated: " . number_format($this->stats['total_migrated']) . " transactions");
        $this->info("Remaining: " . number_format($finalCount) . " transactions");
        $this->info("Total iterations: " . $this->stats['total_iterations']);
        $this->info("Total time: " . round($totalTime / 60, 2) . " minutes");
        
        if ($this->stats['total_migrated'] > 0) {
            $avgSpeed = round($this->stats['total_migrated'] / $totalTime, 2);
            $this->info("Average speed: {$avgSpeed} records/second");
        }
        
        if ($finalCount === 0) {
            $this->info("🎉 SUCCESS: All IB transactions have been migrated to quarter tables!");
            $this->info("✅ MultiLevelRebateDistribution will now save new transactions to current quarter table");
        } elseif (!$dryRun) {
            $this->warn("⚠️  {$finalCount} transactions still remain. You may need to:");
            $this->warn("   • Run the command again: php artisan ib:auto-migration");
            $this->warn("   • Check for errors in the migration process");
            $this->warn("   • Run manually: php artisan ib:high-performance-migration");
        }
    }
}
