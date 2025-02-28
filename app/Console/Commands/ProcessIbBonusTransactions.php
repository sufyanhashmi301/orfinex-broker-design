<?php

namespace App\Console\Commands;

use App\Models\Ledger;
use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Services\WalletService;
use Brick\Math\BigDecimal;

class ProcessIbBonusTransactions extends Command
{
    protected $signature = 'transactions:process-ib-bonus';
    protected $description = 'Process IB Bonus transactions and deduct from user wallets';

    public function handle()
    {
        $transactions = Transaction::where('type', 'ib_bonus')
            ->whereColumn('user_id', 'from_user_id')
            ->where('status', 'success')
            ->get();
//        dd($transactions);

        $walletService = new WalletService();

        foreach ($transactions as $transaction) {
//            dd($transaction);
            $userAccount = get_user_account_by_wallet_id($transaction->target_id);
//            dd($userAccount);

            if (!$userAccount) {
                continue; // Skip if user account is not found
            }

            $ledgerBalance = $walletService->getLedgerBalance($userAccount->id);
//            dd($ledgerBalance);

            if (BigDecimal::of($userAccount->amount)->isGreaterThanOrEqualTo(BigDecimal::of($transaction->amount))) {
                // Deduct the amount
                $ledger = Ledger::where('account_id',$userAccount->id)->orderBy('id','desc')->first();
                $ledger->balance = BigDecimal::of($ledger->balance)->minus(BigDecimal::of($transaction->amount));
                $ledger->save();
                $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($transaction->amount));
                $userAccount->save();

                // Remove the transaction
                $transaction->delete();

                $this->info("Transaction ID {$transaction->id} of user {$transaction->user->full_name} with amount {$transaction->amount} processed successfully.");
            } else {
                // Show user info
                $this->warn("User: {$transaction->id} - {$transaction->user->full_name} ({$transaction->user->email}) - Insufficient Funds: {$transaction->amount}");
            }
        }
    }
}
