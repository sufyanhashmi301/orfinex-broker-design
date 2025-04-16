<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MetaDeal;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Ledger;
use App\Models\User;
use Carbon\Carbon;
use DB;

class ProcessIbBonusAdjustment extends Command
{
    protected $signature = 'process:ibbonus';
    protected $description = 'Process IB Bonus: subtract bonus amounts, delete bonus ledgers, update the last remaining ledger with the wallet balance, and remove records since 2025-04-08';

    public function handle()
    {
        $startDate = Carbon::parse('2025-04-08')->startOfDay();

        $insufficientReport   = [];
        $accountSummary       = [];

        // 1. Gather MetaDeal IDs
        $metaDealIds = [];
        MetaDeal::where('time', '>=', $startDate)
            ->chunkById(200, function ($deals) use (&$metaDealIds) {
                $metaDealIds = array_merge($metaDealIds, $deals->pluck('id')->all());
            });

        // 2. Gather bonus sums _and_ transaction IDs
        $bonusSums           = [];
        $bonusTransactionIds = [];

        Transaction::where('type', 'ib_bonus')
            ->where('status', 'success')
            ->where('created_at', '>=', $startDate)
            ->chunkById(200, function ($txs) use (&$bonusSums, &$bonusTransactionIds) {
                foreach ($txs as $tx) {
                    $bonusSums[$tx->user_id] = ($bonusSums[$tx->user_id] ?? 0) + $tx->amount;
                    $bonusTransactionIds[]   = $tx->id;
                }
            });

        DB::beginTransaction();
        try {
            // 3. Process each user’s wallet
            foreach ($bonusSums as $userId => $totalBonus) {
                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->first();
                $user    = User::find($userId);

                if (! $account) {
                    $insufficientReport[] = [
                        'user_id'        => $userId,
                        'email'          => $user->email ?? 'N/A',
                        'expected_bonus' => $totalBonus,
                        'reason'         => 'IB wallet not found',
                    ];
                    continue;
                }

                if ($account->amount >= $totalBonus) {
                    $account->amount -= $totalBonus;
                } else {
                    $sub        = $account->amount;
                    $remaining  = $totalBonus - $sub;
                    $account->amount = 0;

                    $insufficientReport[] = [
                        'user_id'              => $userId,
                        'email'                => $user->email ?? 'N/A',
                        'expected_bonus'       => $totalBonus,
                        'subtracted_amount'    => $sub,
                        'remaining_to_subtract'=> $remaining,
                        'reason'               => 'Insufficient IB wallet balance',
                    ];
                }

                $account->save();

                $accountSummary[] = [
                    'user_id'       => $userId,
                    'email'         => $user->email ?? 'N/A',
                    'account_id'    => $account->id,
                    'wallet_amount' => $account->amount,
                ];
            }

            // 4. Delete ledgers & transactions by _transaction_ IDs
            if ($bonusTransactionIds) {
                foreach (array_chunk($bonusTransactionIds, 200) as $chunk) {
                    Ledger::whereIn('transaction_id', $chunk)->delete();
                    Transaction::whereIn('id', $chunk)->delete();
                }
            }

            // 5. Delete MetaDeals
            if ($metaDealIds) {
                foreach (array_chunk($metaDealIds, 200) as $chunk) {
                    MetaDeal::whereIn('id', $chunk)->delete();
                }
            }

            // 6. Patch last ledger balances
            foreach ($accountSummary as &$s) {
                $ledger = Ledger::where('account_id', $s['account_id'])
                    ->latest('id')
                    ->first();
                if ($ledger) {
                    $ledger->balance = $s['wallet_amount'];
                    $ledger->save();
                    $s['balance'] = $ledger->balance;
                }
            }

            DB::commit();

            // 7. Output
            $this->info("=== IB Wallet Adjustment Summary ===");
            foreach ($accountSummary as $s) {
                $this->line("User {$s['user_id']} ({$s['email']}): final wallet {$s['wallet_amount']}, ledger {$s['balance']}");
            }

            if ($insufficientReport) {
                $this->info("\n=== Insufficient Funds ===");
                foreach ($insufficientReport as $r) {
                    $this->warn("User {$r['user_id']} ({$r['email']}): remaining to subtract {$r['remaining_to_subtract']}");
                }
            } else {
                $this->info("\nNo insufficient‑funds cases.");
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
        }
    }

}
