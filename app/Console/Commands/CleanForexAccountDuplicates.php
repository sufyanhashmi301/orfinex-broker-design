<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanForexAccountDuplicates extends Command
{
    protected $signature = 'forex:clean-duplicates';
    protected $description = 'Remove duplicate forex_accounts (same login, trader_type) and report cross-user conflicts.';

    public function handle()
    {
        $this->info("🔍 Checking for duplicates in forex_accounts...");

        // Step 1: Detect duplicates
        $duplicates = DB::table('forex_accounts')
            ->select('login', 'trader_type')
            ->groupBy('login', 'trader_type')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $dup) {
            $accounts = DB::table('forex_accounts')
                ->where('login', $dup->login)
                ->where('trader_type', $dup->trader_type)
                ->orderBy('id', 'asc')
                ->get();

            $userIds = $accounts->pluck('user_id')->unique();

            // Step 2: Report if multiple users share same login
            if ($userIds->count() > 1) {
                $this->warn("⚠️ Conflict: login {$dup->login} ({$dup->trader_type}) used by multiple users: " . $userIds->implode(', '));
                continue; // Skip deleting this set to avoid data loss
            }

            // Step 3: Delete duplicates, keep first
            $accountsToDelete = $accounts->slice(1); // skip the first (oldest)
            $deletedCount = 0;
            foreach ($accountsToDelete as $account) {
                DB::table('forex_accounts')->where('id', $account->id)->delete();
                $deletedCount++;
            }

            $this->info("✅ Cleaned {$deletedCount} duplicate(s) for login {$dup->login} ({$dup->trader_type})");
        }

        $this->info("🎯 Duplicate cleanup completed.");
    }
}
