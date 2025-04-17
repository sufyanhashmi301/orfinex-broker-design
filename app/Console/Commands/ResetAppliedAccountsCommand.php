<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use App\Models\Ledger;

class ResetAppliedAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     * Resets a predefined list of account IDs and their last ledger balance to zero.
     *
     * @var string
     */
    protected $signature = 'reset:applied-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset specified account.amount and the latest ledger.balance to zero for predefined IDs';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Predefined account IDs where "set to" was applied
        $ids = [
            31, 51, 59, 61, 75, 80, 83, 89, 93, 100,
            143, 148, 150, 158, 160, 169, 294, 552, 672,
            777, 778, 798, 810
        ];

        $this->info('Resetting accounts: ' . implode(', ', $ids));

        foreach ($ids as $accountId) {
            $account = Account::find($accountId);
            if (! $account) {
                $this->warn("Account #{$accountId} not found.");
                continue;
            }

            $oldAmount = $account->amount;
            $account->amount = 0;
            $account->save();
            $this->line("✔ Account #{$accountId}: amount {$oldAmount} → 0");

            $latestLedger = Ledger::where('account_id', $accountId)
                ->orderBy('id', 'desc')
                ->first();

            if ($latestLedger) {
                $oldBalance = $latestLedger->balance;
                $latestLedger->balance = 0;
                $latestLedger->save();
                $this->line("✔ Ledger #{$latestLedger->id} for Account #{$accountId}: balance {$oldBalance} → 0");
            } else {
                $this->warn("⚠ No ledger entries found for Account #{$accountId}");
            }
        }

        $this->info('Reset complete.');
        return 0;
    }
}
