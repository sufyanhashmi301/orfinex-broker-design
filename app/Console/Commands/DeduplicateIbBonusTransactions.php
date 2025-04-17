<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\User;

class DeduplicateIbBonusTransactions extends Command
{
    protected $signature = 'transactions:deduplicate-ib-bonus';
    protected $description = 'Detect duplicate ib_bonus transactions, delete one, and deduct from accounts and ledgers.';

    public function handle()
    {
        $this->info('🔍 Scanning for duplicate IB Bonus transactions...');

        $report = [];

        $duplicates = Transaction::select(
            'user_id',
            'from_user_id',
            'description',
            'amount',
            DB::raw('COUNT(*) as total')
        )
            ->where('type', 'ib_bonus')
            ->groupBy('user_id', 'from_user_id', 'description', 'amount')
            ->having('total', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicates found.');
            return;
        }

        foreach ($duplicates as $dup) {
            // Retry transaction in case of lock
            DB::transaction(function () use ($dup, &$report) {
                // Get all matching transactions
                $transactions = Transaction::where('type', 'ib_bonus')
                    ->where('user_id', $dup->user_id)
                    ->where('from_user_id', $dup->from_user_id)
                    ->where('description', $dup->description)
                    ->where('amount', $dup->amount)
                    ->orderBy('id')
                    ->get();

                if ($transactions->count() <= 1) {
                    return;
                }

                // Pick one to delete
                $toDelete = $transactions->skip(1)->first();
                $amount = $toDelete->amount;
                $userId = $toDelete->user_id;

                // Fetch account with IB Wallet
                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->lockForUpdate()
                    ->first();

                // If account missing or insufficient balance, add to report
                if (!$account || $account->amount < $amount) {
                    $user = User::find($userId);
                    $email = $user?->email ?? 'Unknown';
                    $remaining = number_format($amount - ($account->amount ?? 0), 3);
                    $report[] = "User {$userId} ({$email}): remaining to subtract {$remaining}";
                    return;
                }

                // Proceed with deletion and deduction
                $toDelete->delete();
                $this->info("🗑 Deleted transaction ID: {$toDelete->id}");

                $account->amount -= $amount;
                $account->save();
                $this->info("💸 Deducted {$amount} from IB Wallet (Account ID: {$account->id})");

                // Deduct from latest ledger
                $ledger = Ledger::where('account_id', $account->id)
                    ->orderByDesc('id')
                    ->lockForUpdate()
                    ->first();

                if ($ledger) {
                    $ledger->balance -= $amount;
                    $ledger->save();
                    $this->info("📉 Updated Ledger ID {$ledger->id} with new balance.");
                }
            }, 5); // Retry transaction up to 5 times on lock wait
        }

        // Show final report for insufficient funds
        if (!empty($report)) {
            $this->warn("\n⚠️ Report of users with insufficient IB Wallet balance:");
            foreach ($report as $line) {
                $this->line($line);
            }
        }

        $this->info("\n✅ IB Bonus deduplication process completed.");
    }
}
