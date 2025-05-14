<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{MetaDeal, Transaction, Account, Ledger, User, Level};
use Carbon\Carbon;
use DB;

class ProcessMasterIbBonusAdjustment extends Command
{
    protected $signature = 'process:ibbonus-master {email}';
    protected $description = 'Reverses IB bonuses and updates wallet balances for all downline users under a master IB.';

    public function handle()
    {
        $email = $this->argument('email');
//        dd($email);
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
//dd($allUserIds);
            $insufficientReport = [];
            $accountSummary = [];

            MetaDeal::whereIn('user_id', $allUserIds)->where('time', '>=', $startDate)->delete();

            $bonusTransactions = Transaction::where('type', 'ib_bonus')
                ->whereIn('user_id', $allUserIds)
                ->where('created_at', '>=', $startDate)
                ->where('status', 'success')
                ->get();

            $bonusSums = $bonusTransactions->groupBy('user_id')->map(function ($txs) {
                return $txs->sum('amount');
            });

            foreach ($bonusSums as $userId => $totalBonus) {
                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->lockForUpdate()
                    ->first();

                $user = User::find($userId);

                if (!$account) {
                    $insufficientReport[] = [
                        'user_id' => $userId,
                        'email' => isset($user->email) ? $user->email : 'N/A',
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
                        'email' => isset($user->email) ? $user->email : 'N/A',
                        'expected_bonus' => $totalBonus,
                        'subtracted_amount' => $subtracted,
                        'remaining_to_subtract' => $totalBonus - $subtracted,
                        'reason' => 'Insufficient balance'
                    ];
                }

                $account->save();

                $accountSummary[] = [
                    'user_id' => $userId,
                    'email' => isset($user->email) ? $user->email : 'N/A',
                    'account_id' => $account->id,
                    'wallet_amount' => $account->amount,
                ];
            }

            $bonusIds = $bonusTransactions->pluck('id')->toArray();
            Ledger::whereIn('transaction_id', $bonusIds)->lockForUpdate()->delete();
            Transaction::whereIn('id', $bonusIds)->delete();

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

            $walletIds = Account::whereIn('user_id', $allUserIds)
                ->where('balance', 'ib_wallet')
                ->pluck('wallet_id')
                ->toArray();

            Transaction::whereIn('target_id', $walletIds)->delete();

            DB::commit();

            $this->info("=== Reversal Completed for Master IB: $email ===");
            foreach ($accountSummary as $summary) {
                $ledgerBalance = isset($summary['ledger_balance']) ? $summary['ledger_balance'] : 'N/A';
                $this->line("User: {$summary['user_id']} | Email: {$summary['email']} | Wallet: {$summary['wallet_amount']} | Ledger: {$ledgerBalance}");
            }

            if (!empty($insufficientReport)) {
                $this->info("=== Users with Insufficient Wallet Funds ===");
                foreach ($insufficientReport as $report) {
                    $remaining = isset($report['remaining_to_subtract']) ? $report['remaining_to_subtract'] : 'N/A';
                    $this->line("User ID: {$report['user_id']}, Email: {$report['email']}, Remaining: {$remaining}");
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error during processing: " . $e->getMessage());
        }
    }
}
