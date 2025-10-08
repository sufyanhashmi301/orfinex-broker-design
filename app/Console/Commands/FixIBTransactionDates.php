<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;

class FixIBTransactionDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:fix-transaction-dates {--dry-run : Show what would be fixed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix invalid datetime entries in IB quarter tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info("🔧 Fixing IB Transaction Dates");
        $this->info("==============================");
        
        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No changes will be made");
        }
        
        $this->newLine();
        
        try {
            $quarters = ['2024_q1', '2024_q2', '2024_q3', '2025_q1', '2025_q2', '2025_q3', '2025_q4'];
            $totalFixed = 0;
            
            foreach ($quarters as $quarter) {
                $tableName = 'ib_transactions_' . $quarter;
                
                if (!Schema::hasTable($tableName)) {
                    $this->info("⏭️  Skipping {$tableName} (table not found)");
                    continue;
                }
                
                // Find records with invalid dates
                $invalidRecords = DB::table($tableName)
                    ->where('created_at', '0000-00-00 00:00:00')
                    ->orWhere('updated_at', '0000-00-00 00:00:00')
                    ->count();
                
                if ($invalidRecords > 0) {
                    $this->info("🔍 Found {$invalidRecords} invalid date records in {$tableName}");
                    
                    if (!$dryRun) {
                        // Fix the invalid dates by setting them to current timestamp
                        $fixed = DB::table($tableName)
                            ->where('created_at', '0000-00-00 00:00:00')
                            ->orWhere('updated_at', '0000-00-00 00:00:00')
                            ->update([
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        
                        $totalFixed += $fixed;
                        $this->info("✅ Fixed {$fixed} records in {$tableName}");
                    } else {
                        $this->info("[DRY RUN] Would fix {$invalidRecords} records in {$tableName}");
                        $totalFixed += $invalidRecords;
                    }
                } else {
                    $this->info("✅ No invalid dates found in {$tableName}");
                }
            }
            
            $this->newLine();
            if ($totalFixed > 0) {
                if ($dryRun) {
                    $this->info("🔍 DRY RUN SUMMARY: Would fix {$totalFixed} total records");
                } else {
                    $this->info("🎉 SUCCESS: Fixed {$totalFixed} total records");
                }
            } else {
                $this->info("✅ No invalid dates found in any quarter tables");
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("❌ Error fixing transaction dates: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
