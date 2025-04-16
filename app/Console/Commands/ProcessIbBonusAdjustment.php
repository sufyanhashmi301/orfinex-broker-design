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
        $startDate   = Carbon::parse('2025-04-08')->startOfDay();
        $currentDate = Carbon::now();

        $insufficientReport = [];
        $accountSummary     = [];

        // 1. Accumulate all MetaDeal IDs, in chunks
        $metaDealIds = [];
        MetaDeal::where('time', '>=', $startDate)
            ->chunkById(200, function ($deals) use (&$metaDealIds) {
                foreach ($deals as $deal) {
                    $metaDealIds[] = $deal->id;
                }
            });

        // 2. Accumulate per‐user bonus sums, in chunks
        $bonusSums = [];
        Transaction::where('type', 'ib_bonus')
            ->where('status', 'success')
            ->where('created_at', '>=', $startDate)
            ->chunkById(200, function ($txs) use (&$bonusSums) {
                foreach ($txs as $tx) {
                    $bonusSums[$tx->user_id] = ($bonusSums[$tx->user_id] ?? 0) + $tx->amount;
                }
            });
//dd($bonusSums);
        DB::beginTransaction();
        try {
            // 3. Process each user’s bonus adjustment
            foreach ($bonusSums as $userId => $totalBonus) {
                /** @var Account|null $account */
                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->first();
                $user = User::find($userId);

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
                    $subtractedAmount     = $account->amount;
                    $remainingToSubtract  = $totalBonus - $subtractedAmount;
                    $account->amount      = 0;

                    $insufficientReport[] = [
                        'user_id'              => $userId,
                        'email'                => $user->email ?? 'N/A',
                        'expected_bonus'       => $totalBonus,
                        'subtracted_amount'    => $subtractedAmount,
                        'remaining_to_subtract'=> $remainingToSubtract,
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

            // 4. Delete related ledgers & transactions in chunks
            if (! empty($bonusSums)) {
                $ids = array_keys($bonusSums);
                Transaction::whereIn('id', $ids)
                    ->chunkById(200, function ($txs) {
                        Ledger::whereIn('transaction_id', $txs->pluck('id'))->delete();
                        $txs->each->delete();
                    });
            }

            // 5. Delete MetaDeals in chunks
            if (! empty($metaDealIds)) {
                MetaDeal::whereIn('id', $metaDealIds)
                    ->chunkById(200, function ($deals) {
                        $deals->each->delete();
                    });
            }

            // 6. Update last ledger balance for each account
            foreach ($accountSummary as &$summary) {
                $ledgerEntry = Ledger::where('account_id', $summary['account_id'])
                    ->orderByDesc('id')
                    ->first();
                if ($ledgerEntry) {
                    $ledgerEntry->balance = $summary['wallet_amount'];
                    $ledgerEntry->save();
                    $summary['ledger_balance'] = $ledgerEntry->balance;
                }
            }

            DB::commit();

            // 7. Finally: show the consolidated report
            $this->info("=== IB Wallet Adjustment Summary ===");
            foreach ($accountSummary as $s) {
                $this->line(
                    "User ID: {$s['user_id']}, Email: {$s['email']}, " .
                    "Final Wallet: {$s['wallet_amount']}, Ledger Balance: {$s['ledger_balance']}"
                );
            }

            if ($insufficientReport) {
                $this->info("\n=== Users with Insufficient Funds ===");
                foreach ($insufficientReport as $r) {
                    $this->warn(
                        "User ID: {$r['user_id']}, Email: {$r['email']}, " .
                        "Remaining to Subtract: {$r['remaining_to_subtract']}"
                    );
                }
            } else {
                $this->info("\nNo users had insufficient IB wallet funds.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error: " . $e->getMessage());
        }
    }

}
