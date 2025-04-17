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
        // Define the start date (2025-04-08) and current date.
        $startDate   = Carbon::parse('2025-04-08')->startOfDay();
        $currentDate = Carbon::now();

        // Array to hold detailed reports for accounts and insufficient funds.
        $insufficientReport = [];
        $accountSummary = [];  // To hold each processed account's summary.

        DB::beginTransaction();
        try {
            // --------------------------------------------
            // 1. Get all Meta Deals with "time" >= 2025-04-08
            // --------------------------------------------
            $metaDeals = MetaDeal::where('time', '>=', $startDate)
                ->get();
//dd($metaDeals);
            // --------------------------------------------
            // 2. Get all IB Bonus Transactions with created_at >= 2025-04-08
            // --------------------------------------------
//            dd($startDate);
            $bonusTransactions = Transaction::where('type', 'ib_bonus')
                ->where('status', 'success') // Process only successful bonus transactions
                ->where('created_at', '>=', $startDate)
                ->get();
//dd($bonusTransactions);
            // Group IB bonus transactions by user and compute total bonus per user.
            $bonusSums = $bonusTransactions->groupBy('user_id')->map(function ($transactions) {
                return $transactions->sum('amount');
            });
//            dd($bonusSums);

            // --------------------------------------------
            // 3. Adjust each user's IB Wallet and record account information.
            // --------------------------------------------
            foreach ($bonusSums as $userId => $totalBonus) {
                // Retrieve the IB wallet account record.
                $account = Account::where('user_id', $userId)
                    ->where('balance', 'ib_wallet')
                    ->first();

                // Look up the user.
                $user = User::find($userId);

                if (!$account) {
                    if ($user) {
                        $insufficientReport[] = [
                            'user_id'        => $user->id,
                            'email'          => $user->email,
                            'expected_bonus' => $totalBonus,
                            'reason'         => 'IB wallet not found'
                        ];
                    }
                    continue;
                }

                if ($account->amount >= $totalBonus) {
                    // Sufficient funds: subtract full bonus amount.
                    $account->amount -= $totalBonus;
                    $account->save();
                } else {
                    // Insufficient funds: subtract available balance.
                    $subtractedAmount   = $account->amount;
                    $remainingToSubtract = $totalBonus - $subtractedAmount;

                    $account->amount = 0;
                    $account->save();

                    if ($user) {
                        $insufficientReport[] = [
                            'user_id'              => $user->id,
                            'email'                => $user->email,
                            'expected_bonus'       => $totalBonus,
                            'subtracted_amount'    => $subtractedAmount,
                            'remaining_to_subtract'=> $remainingToSubtract,
                            'reason'               => 'Insufficient IB wallet balance'
                        ];
                    }
                }

                // Record account summary details for later ledger update.
                $accountSummary[] = [
                    'user_id'  => $user ? $user->id : $userId,
                    'email'    => $user ? $user->email : 'N/A',
                    'account_id' => $account->id,
                    'wallet_amount' => $account->amount,
                ];
            }

            // --------------------------------------------
            // 4. First, delete ledger entries associated with all IB Bonus Transactions.
            // --------------------------------------------
            $bonusTransactionIds = $bonusTransactions->pluck('id')->toArray();
            Ledger::whereIn('transaction_id', $bonusTransactionIds)->delete();

            // Also delete the IB bonus transactions.
            Transaction::whereIn('id', $bonusTransactionIds)->delete();

            // --------------------------------------------
            // 5. Delete the processed Meta Deals.
            // --------------------------------------------
            $metaDealIds = $metaDeals->pluck('id')->toArray();
            MetaDeal::whereIn('id', $metaDealIds)->delete();

            // --------------------------------------------
            // 6. For each processed IB wallet account, update the last ledger entry with the wallet amount.
            //     The last ledger entry (from non-bonus entries) is updated so that it reflects the same balance as in the accounts table.
            // --------------------------------------------
            foreach ($accountSummary as &$summary) {
                $ledgerEntry = Ledger::where('account_id', $summary['account_id'])
                    ->orderBy('id', 'desc')
                    ->first();

                if ($ledgerEntry) {
                    $ledgerEntry->balance = $summary['wallet_amount'];
                    $ledgerEntry->save();
                    $summary['ledger_balance'] = $ledgerEntry->balance;
                }
            }

            DB::commit();

            // --------------------------------------------
            // 7. Output Summary Report on the same page.
            // --------------------------------------------
            $this->info("=== IB Wallet Adjustment Summary ===");
            foreach ($accountSummary as $summary) {
                $message = "User ID: {$summary['user_id']}, Email: {$summary['email']}, " .
                    "Final Wallet Amount: {$summary['wallet_amount']}, " .
                    "Last Ledger Balance: {$summary['ledger_balance']}";
                $this->line($message);
            }

            if (!empty($insufficientReport)) {
                $this->info("\n=== Users with Insufficient IB Wallet Funds ===");
                foreach ($insufficientReport as $reportItem) {
                    $message = "User ID: {$reportItem['user_id']}, Email: {$reportItem['email']}, " .
                        "Remaining To Subtract: {$reportItem['remaining_to_subtract']}"."\n" ;
                    $this->line($message);
                }
            } else {
                $this->info("\nNo insufficient funds issues detected.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error processing IB bonus adjustments: " . $e->getMessage());
        }
    }
}
