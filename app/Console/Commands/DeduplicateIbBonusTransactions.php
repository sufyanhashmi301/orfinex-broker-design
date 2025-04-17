<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeduplicateIbBonusTransactions extends Command
{
    protected $signature = 'transactions:deduplicate-ib-bonus';
    protected $description = 'Delete duplicated IB Bonus transactions and adjust accounts and ledger balances accordingly';

    public function handle()
    {
        $this->info('🔍 Checking for duplicate IB Bonus transactions...');
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
            $this->info('✅ No duplicate IB Bonus transactions found.');
            return;
        }

        foreach ($duplicates as $dup) {
            DB::transaction(function () use ($dup, &$report) {
                $transactions = Transaction::where('type', 'ib_bonus')
                    ->where('user_id', $dup->user_id)
                    ->where('from_user_id', $dup->from_user_id)
                    ->where('description', $dup->description)
                    ->where('amount', $dup->amount)
                    ->orderBy('id')
                    ->get();

                if ($transactions->count() <= 1) return;

                // Get one duplicate to delete
                $toDelete = $transactions->skip(1)->first();
                $amount = $toDelete->amount;
                $userId = $toDelete->user_id;

                // Load user's IB wallet account
                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->first();

                if (!$account || $account->amount < $amount) {
                    $userEmail = User::find($userId)?->email ?? 'Unknown Email';
                    $remaining = $amount - ($account->amount ?? 0);
                    $report[] = "User {$userId} ({$userEmail}): remaining to subtract " . number_format($remaining, 3);
                    return;
                }

                // Proceed with deletion and deductions
                $toDelete->delete();
                $account->amount -= $amount;
                $account->save();

                $this->info("✅ Deleted Transaction ID {$toDelete->id}, deducted {$amount} from IB Wallet (Account ID: {$account->id})");

                $ledger = Ledger::where('account_id', $account->id)
                    ->orderByDesc('id')
                    ->first();

                if ($ledger) {
                    $ledger->balance -= $amount;
                    $ledger->save();
                    $this->info("📉 Updated Ledger ID {$ledger->id} with new balance.");
                }
            });
        }

        // Show final report for insufficient balance users
        if (!empty($report)) {
            $this->warn("\n⚠️  Report of users with insufficient IB Wallet balance:");
            foreach ($report as $line) {
                $this->line($line);
            }
        }

        $this->info("\n🎉 Duplicate IB Bonus transaction check completed.");
    }
}
