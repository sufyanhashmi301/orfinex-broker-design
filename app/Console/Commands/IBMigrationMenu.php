<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class IBMigrationMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:migration-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive menu for IB transactions migration with performance recommendations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->displayHeader();
        $this->analyzeCurrentState();
        $this->showMigrationOptions();
        $this->showPerformanceRecommendations();
        
        return Command::SUCCESS;
    }
    
    private function displayHeader()
    {
        $this->info("╔══════════════════════════════════════════════════════════════╗");
        $this->info("║              IB Transactions Migration Center                ║");
        $this->info("║          Optimized for Large Scale Migrations               ║");
        $this->info("╚══════════════════════════════════════════════════════════════╝");
        $this->newLine();
    }
    
    private function analyzeCurrentState()
    {
        $this->info("📊 Current System Analysis");
        $this->info("==========================");
        
        try {
            // Count transactions
            $totalTransactions = Transaction::count();
            $ibTransactions = Transaction::where('type', 'ib_bonus')->count();
            
            $this->info("Total Transactions: " . number_format($totalTransactions));
            $this->info("IB Bonus Transactions: " . number_format($ibTransactions));
            
            if ($ibTransactions === 0) {
                $this->info("✅ No IB transactions to migrate - system is ready!");
                return;
            }
            
            // Analyze by year
            $yearAnalysis = Transaction::where('type', 'ib_bonus')
                ->selectRaw('YEAR(created_at) as year, COUNT(*) as count')
                ->groupBy('year')
                ->orderBy('year')
                ->get();
                
            $this->info("\nIB Transactions by Year:");
            foreach ($yearAnalysis as $year) {
                $this->info("  {$year->year}: " . number_format($year->count) . " transactions");
            }
            
            // Performance category
            $this->newLine();
            if ($ibTransactions < 10000) {
                $this->info("📈 Migration Category: SMALL (< 10K records)");
                $this->info("   Recommended: Standard migration");
            } elseif ($ibTransactions < 100000) {
                $this->info("📈 Migration Category: MEDIUM (10K - 100K records)");
                $this->info("   Recommended: High-performance migration");
            } elseif ($ibTransactions < 1000000) {
                $this->info("📈 Migration Category: LARGE (100K - 1M records)");
                $this->info("   Recommended: High-performance or parallel migration");
            } else {
                $this->info("📈 Migration Category: ENTERPRISE (1M+ records)");
                $this->info("   Recommended: Parallel migration with multiple workers");
            }
            
        } catch (\Exception $e) {
            $this->error("Error analyzing system: " . $e->getMessage());
        }
    }
    
    private function showMigrationOptions()
    {
        $this->newLine();
        $this->info("🚀 Available Migration Commands");
        $this->info("==============================");
        
        $this->info("\n1. 📋 STANDARD MIGRATION (< 50K records)");
        $this->info("   Command: php artisan ib:migrate-all-transactions");
        $this->info("   Features: Basic migration with progress tracking");
        $this->info("   Time: ~2-5 minutes for 10K records");
        
        $this->info("\n2. ⚡ HIGH-PERFORMANCE MIGRATION (50K - 1M records)");
        $this->info("   Command: php artisan ib:high-performance-migration");
        $this->info("   Features: Database optimization, memory management, resume capability");
        $this->info("   Time: ~15-45 minutes for 500K records");
        $this->info("   Options:");
        $this->info("     --chunk-size=2000     (records per chunk)");
        $this->info("     --batch-size=500      (records per batch insert)");
        $this->info("     --memory-limit=1G     (PHP memory limit)");
        $this->info("     --dry-run             (test without changes)");
        $this->info("     --resume-from-id=ID   (resume interrupted migration)");
        
        $this->info("\n3. 🚄 PARALLEL MIGRATION (1M+ records)");
        $this->info("   Command: php artisan ib:parallel-migration");
        $this->info("   Features: Multiple worker processes, maximum throughput");
        $this->info("   Time: ~10-25 minutes for 2.5M records");
        $this->info("   Options:");
        $this->info("     --workers=4           (number of parallel workers)");
        $this->info("     --chunk-size=5000     (records per worker chunk)");
        $this->info("     --dry-run             (test without changes)");
        
        $this->info("\n4. 🔍 TESTING & VERIFICATION");
        $this->info("   Commands:");
        $this->info("     php artisan ib:test-quarter-integration    (test system integration)");
        $this->info("     php artisan ib:test-4month-system          (test 4-month system)");
        $this->info("     php artisan ib:schedule-4month-tasks       (manual maintenance)");
    }
    
    private function showPerformanceRecommendations()
    {
        $ibTransactions = Transaction::where('type', 'ib_bonus')->count();
        
        $this->newLine();
        $this->info("💡 Performance Recommendations");
        $this->info("==============================");
        
        if ($ibTransactions < 10000) {
            $this->info("✅ RECOMMENDED: Standard Migration");
            $this->info("   php artisan ib:migrate-all-transactions");
            
        } elseif ($ibTransactions < 100000) {
            $this->info("✅ RECOMMENDED: High-Performance Migration");
            $this->info("   php artisan ib:high-performance-migration --chunk-size=1500");
            
        } elseif ($ibTransactions < 500000) {
            $this->info("✅ RECOMMENDED: High-Performance Migration (Optimized)");
            $this->info("   php artisan ib:high-performance-migration \\");
            $this->info("     --chunk-size=2000 --batch-size=500 --memory-limit=1G");
            
        } elseif ($ibTransactions < 1000000) {
            $this->info("✅ RECOMMENDED: Parallel Migration (4 workers)");
            $this->info("   php artisan ib:parallel-migration --workers=4 --chunk-size=3000");
            
        } else {
            $this->info("✅ RECOMMENDED: Parallel Migration (8 workers)");
            $this->info("   php artisan ib:parallel-migration --workers=8 --chunk-size=5000");
            $this->info("\n🔧 ENTERPRISE SETUP:");
            $this->info("   1. Increase MySQL buffer sizes");
            $this->info("   2. Use SSD storage for better I/O");
            $this->info("   3. Schedule during low-traffic hours");
            $this->info("   4. Monitor system resources");
        }
        
        $this->newLine();
        $this->info("⚠️  IMPORTANT NOTES:");
        $this->info("   • Always run --dry-run first to test");
        $this->info("   • Backup your database before migration");
        $this->info("   • Monitor system resources during migration");
        $this->info("   • Use --resume-from-id if migration is interrupted");
        
        $this->newLine();
        $this->info("📚 Documentation:");
        $this->info("   • docs/LARGE_SCALE_MIGRATION_GUIDE.md");
        $this->info("   • docs/IB_TRANSACTIONS_4MONTH_SYSTEM.md");
        
        $this->newLine();
        $this->info("🎯 Quick Start for " . number_format($ibTransactions) . " records:");
        
        if ($ibTransactions >= 1000000) {
            $this->warn("   1. php artisan ib:parallel-migration --dry-run --workers=4");
            $this->warn("   2. php artisan ib:parallel-migration --workers=4");
        } elseif ($ibTransactions >= 100000) {
            $this->warn("   1. php artisan ib:high-performance-migration --dry-run");
            $this->warn("   2. php artisan ib:high-performance-migration");
        } else {
            $this->warn("   1. php artisan ib:migrate-all-transactions --dry-run");
            $this->warn("   2. php artisan ib:migrate-all-transactions");
        }
        
        $this->warn("   3. php artisan ib:test-quarter-integration");
    }
}
