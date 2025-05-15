<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\Ledger;
use Throwable;

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
    protected $description = 'Update IB Wallet ledger balances with the account amount (wallet balance), using row-level locking.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Account::where('balance', 'ib_wallet')
            ->chunkById(100, function ($accounts) {
                foreach ($accounts as $account) {
                    try {
                        DB::beginTransaction();

                        // Lock the latest ledger entry for this account
                        $lastLedger = Ledger::where('account_id', $account->id)
                            ->orderByDesc('id')
                            ->limit(1)
                            ->lockForUpdate()
                            ->first();

                        if (!$lastLedger) {
                            $this->warn("No ledger entry found for Account ID {$account->id}");
                            DB::rollBack();
                            continue;
                        }

                        // Update the ledger balance with the account amount
                        $lastLedger->balance = $account->amount;
                        $lastLedger->save();

                        DB::commit();

                        $this->info("Ledger updated for Account ID {$account->id} with balance: {$account->amount}");

                    } catch (Throwable $e) {
                        DB::rollBack();
                        $this->error("Failed for Account ID {$account->id}: " . $e->getMessage());
                    }
                }
            });

        return 0;
    }
}
