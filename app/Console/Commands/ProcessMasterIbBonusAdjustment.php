<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{MetaDeal, Transaction, Account, Ledger, User, Level};
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessMasterIbBonusAdjustment extends Command
{
    protected $signature = 'process:ibbonus-master {email}';
    protected $description = 'Reverses IB bonuses and updates wallet balances for all downline users under a master IB.';

    public function handle()
    {
        $email = $this->argument('email');
        $masterIb = User::where('email', $email)->first();

        if (!$masterIb) {
            $this->error("No user found with email: $email");
            return;
        }

        $startDate = Carbon::parse('2025-04-30')->startOfDay();

        DB::beginTransaction();
        try {
            $maxLevel = Level::max('level_order');
            $depth = $maxLevel + 1;

            $currentLevelUsers = [$masterIb->id];
            $allUserIds = [$masterIb->id];

            for ($i = 0; $i < $depth; $i++) {
                $nextLevelUsers = User::whereIn('ref_id', $currentLevelUsers)->pluck('id')->toArray();
                if (empty($nextLevelUsers)) break;
                $allUserIds = array_merge($allUserIds, $nextLevelUsers);
                $currentLevelUsers = $nextLevelUsers;
            }

            $insufficientReport = [];
            $accountSummary = [];

            // Delete MetaDeals in chunks
            MetaDeal::whereIn('user_id', $allUserIds)
                ->where('time', '>=', $startDate)
                ->chunkById(1000, function ($deals) {
                    $ids = $deals->pluck('id');
                    MetaDeal::whereIn('id', $ids)->delete();
                });

            // Sum bonus transactions by user via DB for performance
            $bonusSums = Transaction::select('user_id', DB::raw('SUM(amount) as total_bonus'))
                ->where('type', 'ib_bonus')
                ->whereIn('user_id', $allUserIds)
                ->where('created_at', '>=', $startDate)
                ->where('status', 'success')
                ->groupBy('user_id')
                ->get()
                ->keyBy('user_id');

            // Process bonuses in chunks for memory safety
            foreach ($bonusSums->chunk(500) as $chunk) {
                foreach ($chunk as $userId => $row) {
                    $totalBonus = $row->total_bonus;

                    $account = Account::where('user_id', $userId)
                        ->where('balance', 'ib_wallet')
                        ->lockForUpdate()
                        ->first();

                    $user = User::find($userId);

                    if (!$account) {
                        $insufficientReport[] = [
                            'user_id' => $userId,
                            'email' => $user->email ?? 'N/A',
                            'expected_bonus' => $totalBonus,
                            'reason' => 'IB wallet not found'
                        ];
                        continue;
                    }

                    if ($account->amount >= $totalBonus) {
                        $account->amount -= $totalBonus;
                    } else {
                        $subtracted = $account->amount;
                        $account->amount = 0;

                        $insufficientReport[] = [
                            'user_id' => $userId,
                            'email' => $user->email ?? 'N/A',
                            'expected_bonus' => $totalBonus,
                            'subtracted_amount' => $subtracted,
                            'remaining_to_subtract' => $totalBonus - $subtracted,
                            'reason' => 'Insufficient balance'
                        ];
                    }

                    $account->save();

                    $accountSummary[] = [
                        'user_id' => $userId,
                        'email' => $user->email ?? 'N/A',
                        'account_id' => $account->id,
                        'wallet_amount' => $account->amount,
                    ];
                }
            }

            // Retrieve all successful bonus transaction IDs for deletion
            $bonusTransactionIds = Transaction::where('type', 'ib_bonus')
                ->whereIn('user_id', $allUserIds)
                ->where('created_at', '>=', $startDate)
                ->where('status', 'success')
                ->pluck('id')
                ->toArray();

            // Delete Ledger entries in chunks
            collect($bonusTransactionIds)->chunk(1000)->each(function ($chunk) {
                Ledger::whereIn('transaction_id', $chunk)->lockForUpdate()->delete();
            });

            // Delete transactions in chunks
            collect($bonusTransactionIds)->chunk(1000)->each(function ($chunk) {
                Transaction::whereIn('id', $chunk)->delete();
            });

            // Update ledger balances
            foreach ($accountSummary as &$summary) {
                $ledger = Ledger::where('account_id', $summary['account_id'])
                    ->orderBy('id', 'desc')
                    ->lockForUpdate()
                    ->first();

                if ($ledger) {
                    $ledger->balance = $summary['wallet_amount'];
                    $ledger->save();
                    $summary['ledger_balance'] = $ledger->balance;
                }
            }

            DB::commit();

            $this->info("=== Reversal Completed for Master IB: $email ===");

            foreach ($accountSummary as $summary) {
                $ledgerBalance = $summary['ledger_balance'] ?? 'N/A';
                $this->line("User: {$summary['user_id']} | Email: {$summary['email']} | Wallet: {$summary['wallet_amount']} | Ledger: {$ledgerBalance}");
            }

            if (!empty($insufficientReport)) {
                $this->info("=== Users with Insufficient Wallet Funds ===");
                foreach ($insufficientReport as $report) {
                    $remaining = $report['remaining_to_subtract'] ?? 'N/A';
                    $this->line("User ID: {$report['user_id']}, Email: {$report['email']}, Remaining: {$remaining}");
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error during processing: " . $e->getMessage());
        }
    }
}
