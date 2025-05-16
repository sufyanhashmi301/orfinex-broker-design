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
    protected $signature = 'transactions:clean-ib-bonus-duplicates';
    protected $description = 'Delete duplicate ib_bonus transactions and track remaining amount per user for future manual deduction.';

    public function handle()
    {
        $this->info('🔍 Scanning for duplicate IB Bonus transactions...');
        $remainingReport = [];

        $duplicates = Transaction::select(
            'user_id',
            'from_user_id',
            'description',
            DB::raw('COUNT(*) as total')
        )
            ->where('type', 'ib_bonus')
            ->groupBy('user_id', 'from_user_id', 'description')
            ->having('total', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicates found.');
            return;
        }

        foreach ($duplicates as $dup) {
            DB::transaction(function () use ($dup, &$remainingReport) {
                $transactions = Transaction::where('type', 'ib_bonus')
                    ->where('user_id', $dup->user_id)
                    ->where('from_user_id', $dup->from_user_id)
                    ->where('description', $dup->description)
//                    ->where('amount', $dup->amount)
                    ->orderBy('id')
                    ->get();

                if ($transactions->count() <= 1) return;

                $keep = $transactions->first();
                $toDeleteList = $transactions->skip(1);

                foreach ($toDeleteList as $toDelete) {
                    $amount = $toDelete->amount;
                    $userId = $toDelete->user_id;

                    $account = Account::where('user_id', $userId)
                        ->where('balance', 'ib_wallet')
                        ->lockForUpdate()
                        ->first();

                    $deleted = $toDelete->delete();

                    if (!$account || $account->amount < $amount) {
                        $shortfall = $amount - ($account->amount ?? 0);
                        $remainingReport[$userId] = ($remainingReport[$userId] ?? 0) + $shortfall;
                        $this->warn("⚠️  Deleted Transaction ID {$toDelete->id}, but insufficient balance for User {$userId}");
                        return;
                    }

                    // Deduct from IB Wallet
                    $account->amount -= $amount;
                    $account->save();

                    // Deduct from latest ledger
                    $ledger = Ledger::where('account_id', $account->id)
                        ->orderByDesc('id')
                        ->lockForUpdate()
                        ->first();

                    if ($ledger) {
                        $ledger->balance -= $amount;
                        $ledger->save();
                    }

                    $this->info("🗑 Deleted Transaction ID: {$toDelete->id}, deducted {$amount} from User {$userId}");
                }
            }, 5); // Retry if lock wait timeout
        }

        // Generate collective shortfall report
        if (!empty($remainingReport)) {
            $this->warn("\n⚠️ Collective shortfall report (manual deduction needed):");
            foreach ($remainingReport as $userId => $remaining) {
                $email = User::find($userId)?->email ?? 'Unknown Email';
                $this->line("User {$userId} ({$email}): total remaining to subtract " . number_format($remaining, 3));
            }
        }

        $this->info("\n✅ IB Bonus deduplication process complete.");
    }
}
