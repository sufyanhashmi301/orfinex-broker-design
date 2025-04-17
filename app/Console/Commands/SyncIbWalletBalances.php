<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\Ledger;

class SyncIbWalletBalances extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ib-wallet-balances';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate each IB Wallet account.amount with the latest ledger.balance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting IB Wallet balance sync…');

        // Process in chunks to avoid memory issues
        Account::where('balance', 'ib_wallet')
            ->chunkById(100, function ($accounts) {
                foreach ($accounts as $acct) {
                    // Get the most recent ledger entry for this account
                    $latest = Ledger::where('account_id', $acct->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    if ($latest) {
                        // Only update if it’s different
                        if ($acct->amount !== $latest->balance) {
                            $acct->amount = $latest->balance;
                            $acct->save();
                            $this->line("✔ Account #{$acct->id}: set to {$latest->balance}");
                        } else {
                            $this->line("→ Account #{$acct->id}: already up‑to‑date");
                        }
                    } else {
                        $this->warn("⚠ No ledger rows found for account #{$acct->id}");
                    }
                }
            });

        $this->info('IB Wallet balance sync complete.');
    }
}
