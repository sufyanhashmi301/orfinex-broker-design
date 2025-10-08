<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Enums\TxnStatus;

class RecalculateIBBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:recalculate-balances 
                            {--dry-run : Show what would be updated without making changes}
                            {--chunk-size=1000 : Process users in chunks}
                            {--show-details : Show detailed progress for each user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all users ib_balance to 0, then recalculate from main transactions table (ib_bonus type)';

    private $stats = [
        'users_processed' => 0,
        'users_updated' => 0,
        'total_ib_amount' => 0,
        'transactions_counted' => 0,
        'users_with_ib' => 0
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $chunkSize = (int) $this->option('chunk-size');
        $showDetails = $this->option('show-details');

        $this->displayHeader($dryRun);

        try {
            // Step 1: Get initial statistics
            $this->getInitialStats();

            // Step 2: Reset all ib_balance to 0
            $this->resetAllIBBalances($dryRun);

            // Step 3: Recalculate IB balances from main transactions table
            $this->recalculateIBBalances($dryRun, $chunkSize, $showDetails);

            // Step 4: Display final results
            $this->displayFinalResults($dryRun);

            if (!$dryRun) {
                $this->info("✅ IB balance recalculation completed successfully!");
                $this->info("🚀 You can now run the quarter migration: php artisan ib:auto-migration");
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ IB balance recalculation failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Display header information
     */
    private function displayHeader($dryRun)
    {
        $this->info("╔══════════════════════════════════════════════════════════════╗");
        $this->info("║                IB Balance Recalculation Script              ║");
        $this->info("║          Reset & Rebuild from Main Transactions             ║");
        $this->info("╚══════════════════════════════════════════════════════════════╝");

        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No actual changes will be made");
        }

        $this->newLine();
    }

    /**
     * Get initial statistics
     */
    private function getInitialStats()
    {
        $this->info("📊 Getting Initial Statistics");
        $this->info("------------------------------");

        // Total users
        $totalUsers = User::count();
        $this->info("👥 Total users: " . number_format($totalUsers));

        // Users with current IB balance > 0
        $usersWithIB = User::where('ib_balance', '>', 0)->count();
        $this->info("💰 Users with IB balance > 0: " . number_format($usersWithIB));

        // Current total IB balance
        $currentTotalIB = User::sum('ib_balance');
        $this->info("💵 Current total IB balance: $" . number_format($currentTotalIB, 2));

        // Total IB transactions in main table
        $totalIBTransactions = Transaction::where('type', 'ib_bonus')->count();
        $this->info("📝 Total IB transactions in main table: " . number_format($totalIBTransactions));

        // Total IB amount in main table (successful transactions only)
        $totalIBAmount = Transaction::where('type', 'ib_bonus')
            ->where('status', TxnStatus::Success->value)
            ->sum('final_amount');
        $this->info("💎 Total successful IB amount in main table: $" . number_format($totalIBAmount, 2));

        $this->newLine();
    }

    /**
     * Reset all ib_balance to 0
     */
    private function resetAllIBBalances($dryRun)
    {
        $this->info("🔄 Resetting All IB Balances to Zero");
        $this->info("------------------------------------");

        if (!$dryRun) {
            $affectedRows = DB::table('users')->update(['ib_balance' => 0]);
            $this->info("✅ Reset {$affectedRows} users' ib_balance to 0");
        } else {
            $usersWithBalance = User::where('ib_balance', '>', 0)->count();
            $this->info("[DRY RUN] Would reset {$usersWithBalance} users' ib_balance to 0");
        }

        $this->newLine();
    }

    /**
     * Recalculate IB balances from main transactions table
     */
    private function recalculateIBBalances($dryRun, $chunkSize, $showDetails)
    {
        $this->info("🧮 Recalculating IB Balances from Main Transactions");
        $this->info("---------------------------------------------------");

        // Get IB balance totals per user from main transactions table
        $userIBTotals = DB::table('transactions')
            ->select('user_id', DB::raw('SUM(final_amount) as total_ib'))
            ->where('type', 'ib_bonus')
            ->where('status', TxnStatus::Success->value)
            ->whereNotNull('user_id')
            ->groupBy('user_id')
            ->havingRaw('SUM(final_amount) > 0')
            ->get();

        $this->info("👥 Found " . $userIBTotals->count() . " users with IB transactions");

        if ($userIBTotals->isEmpty()) {
            $this->warn("⚠️  No successful IB transactions found in main table");
            return;
        }

        // Process in chunks for better performance
        $chunks = $userIBTotals->chunk($chunkSize);
        $chunkNumber = 1;
        $totalChunks = $chunks->count();

        foreach ($chunks as $chunk) {
            $this->info("Processing chunk {$chunkNumber}/{$totalChunks} ({$chunk->count()} users)");

            if (!$dryRun) {
                // Update users in batch
                foreach ($chunk as $userTotal) {
                    DB::table('users')
                        ->where('id', $userTotal->user_id)
                        ->update(['ib_balance' => $userTotal->total_ib]);

                    $this->stats['users_updated']++;
                    $this->stats['total_ib_amount'] += $userTotal->total_ib;

                    if ($showDetails) {
                        $this->info("  ✅ User {$userTotal->user_id}: $" . number_format($userTotal->total_ib, 2));
                    }
                }
            } else {
                foreach ($chunk as $userTotal) {
                    $this->stats['users_processed']++;
                    $this->stats['total_ib_amount'] += $userTotal->total_ib;

                    if ($showDetails) {
                        $this->info("  [DRY RUN] User {$userTotal->user_id}: $" . number_format($userTotal->total_ib, 2));
                    }
                }
            }

            $chunkNumber++;
        }

        $this->stats['users_with_ib'] = $userIBTotals->count();
        $this->stats['transactions_counted'] = Transaction::where('type', 'ib_bonus')
            ->where('status', TxnStatus::Success->value)
            ->count();

        $this->newLine();
    }

    /**
     * Display final results
     */
    private function displayFinalResults($dryRun)
    {
        $this->info("📈 Final Results Summary");
        $this->info("========================");

        if ($dryRun) {
            $this->info("🔍 DRY RUN RESULTS:");
            $this->info("Users that would be updated: " . number_format($this->stats['users_with_ib']));
            $this->info("Total IB amount that would be set: $" . number_format($this->stats['total_ib_amount'], 2));
            $this->info("IB transactions counted: " . number_format($this->stats['transactions_counted']));
        } else {
            $this->info("✅ ACTUAL RESULTS:");
            $this->info("Users updated: " . number_format($this->stats['users_updated']));
            $this->info("Total IB amount redistributed: $" . number_format($this->stats['total_ib_amount'], 2));
            $this->info("IB transactions processed: " . number_format($this->stats['transactions_counted']));

            // Verify the results
            $this->verifyResults();
        }

        $this->newLine();
    }

    /**
     * Verify the recalculation results
     */
    private function verifyResults()
    {
        $this->info("🔍 Verification Check");
        $this->info("---------------------");

        // Check total IB balance in users table
        $totalUserIB = User::sum('ib_balance');
        $this->info("💰 New total IB balance in users table: $" . number_format($totalUserIB, 2));

        // Check total successful IB transactions in main table
        $totalMainIB = Transaction::where('type', 'ib_bonus')
            ->where('status', TxnStatus::Success->value)
            ->sum('final_amount');
        $this->info("📝 Total successful IB in main transactions: $" . number_format($totalMainIB, 2));

        // Compare
        $difference = abs($totalUserIB - $totalMainIB);
        if ($difference < 0.01) { // Allow for minor floating point differences
            $this->info("✅ VERIFICATION PASSED: Balances match!");
        } else {
            $this->warn("⚠️  VERIFICATION WARNING: Difference of $" . number_format($difference, 2));
            $this->warn("This might be due to pending/failed transactions or rounding.");
        }

        // Show users with highest IB balances
        $topUsers = User::where('ib_balance', '>', 0)
            ->orderBy('ib_balance', 'desc')
            ->limit(5)
            ->get(['id', 'email', 'ib_balance']);

        if ($topUsers->isNotEmpty()) {
            $this->info("\n🏆 Top 5 Users by IB Balance:");
            foreach ($topUsers as $user) {
                $this->info("  User {$user->id} ({$user->email}): $" . number_format($user->ib_balance, 2));
            }
        }

        $this->newLine();
    }
}
