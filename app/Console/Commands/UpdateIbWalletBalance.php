<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\Ledger;

class UpdateIbWalletBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:ib-wallet-update-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all IB Wallet account amounts based on the latest two ledger entries.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch all IB Wallet accounts
        $accounts = Account::where('balance', 'ib_wallet')->get();

        foreach ($accounts as $account) {
            DB::transaction(function () use ($account) {
                // Retrieve the last two ledger entries for this account
                $ledgers = Ledger::where('account_id', $account->id)
                    ->orderBy('id', 'desc')
                    ->take(2)
                    ->get();

                // Ensure at least two entries exist
                if ($ledgers->count() < 2) {
                    $this->info("Account ID {$account->id} skipped: not enough ledger entries.");
                    return;
                }

                // Identify the relevant entries
                $lastEntry = $ledgers->first();
                $secondLastEntry = $ledgers->last();

                // Handle null values by coalescing to zero
                $previousBalance = $secondLastEntry->balance ?? 0;
                $credit = $lastEntry->credit ?? 0;
                $debit = $lastEntry->debit ?? 0;

                // Calculate the new amount
                $newAmount = $previousBalance + $credit - $debit;

                // Update the account's amount
                $account->amount = $newAmount;
                $account->save();

                // Update the last ledger entry's balance
                $lastEntry->balance = $newAmount;
                $lastEntry->save();

                $this->info("Account ID {$account->id} updated: new IB Wallet amount is {$newAmount}.");
            });
        }

        return 0;
    }
}
