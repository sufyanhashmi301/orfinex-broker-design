<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Txn;
use App\Enums\TxnType;
use App\Enums\TxnStatus;

class SyncOldTransactions extends Command
{
    protected $signature = 'sync:old-transactions';
    protected $description = 'Sync transactions from deposits and withdrawals worksheets to old_transactions table';

    public function handle()
    {
        $this->syncDeposits();
        $this->syncWithdrawals();
    }

    protected function syncDeposits()
    {
        $missingCount = 0;  // To keep track of missing user transactions
        DB::table('primex_deposits_worksheet')->orderBy('deposit_number')->chunk(100, function ($transactions) use (&$missingCount) {
            foreach ($transactions as $transaction) {
                $user = DB::table('users')->where('email', $transaction->email)->first();
                if ($user) {
                    Txn::newOld(
                        $transaction->final_amount,
                        0,
                        $transaction->final_amount,
                        $transaction->payment_method,
                        'Deposit With ' . $transaction->payment_name,
                        TxnType::Deposit,
                        TxnStatus::Success,
                        $transaction->currency,
                        $transaction->final_amount,
                        $user->id,
                        null,
                        'User',
                        [],
                        'none',
                        $transaction->account_number,
                        TxnType::Deposit,
                        false,
                        $transaction->processed
                    );
                } else {
                    $missingCount++;
                    $this->error("Missing user: " . $transaction->email . " with deposit number: " . $transaction->deposit_number);
                }
            }
            $this->info("Total missing deposit transactions: $missingCount");
        });
    }

    protected function syncWithdrawals()
    {
        $missingCount = 0;  // To keep track of missing user transactions
        DB::table('primex_withdrawals_worksheet')
            ->where('status', 'Done')
            ->orderBy('withdraw_number')
            ->chunk(100, function ($transactions) use (&$missingCount) {
                foreach ($transactions as $transaction) {
                    // Convert the processed date string to a Carbon instance
                    $processedDate = \Carbon\Carbon::createFromFormat('m/d/Y g:i A', $transaction->processed);

                    $user = DB::table('users')->where('email', $transaction->email)->first();
                    if ($user) {
                    // Calculate the charge as the difference between the amount and the final amount
                    $charge = 0;

                    Txn::newOld(
                        $transaction->amount,  // Original amount
                        $charge,  // Charge calculated
                        $transaction->amount,  // Final amount after charge
                        $transaction->payment_method,
                        'Withdrawal With ' . $transaction->payment_name,
                        TxnType::Withdraw,
                        TxnStatus::Success,
                        $transaction->currency,
                        $transaction->final_amount,  // Pay amount might be the same as the final amount
                        $user->id,
                        null,  // from_user_id is assumed to be null
                        'User',
                        [],  // manual_data is empty as not specified
                        'none',  // approval_cause
                        $transaction->withdraw_number,  // Assuming this is the target_id
                        TxnType::Withdraw,
                        false,
                        $processedDate
                    );
                } else {
                    $missingCount++;
                    $this->error("Missing user: " . $transaction->email . " with withdraw number: " . $transaction->withdraw_number);
                }
            }
            $this->info("Total missing withdrawal transactions: $missingCount");
        });
    }

}
