<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{MetaDeal, Account, User, ForexAccount};
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class ReverseIbBonusDistribution extends Command
{
    protected $signature = 'rebate:reverse 
                            {email : The email of the user}
                            {date : The start date from which to reverse bonuses (Y-m-d format)}
                            {--dry-run : Run without making actual changes}';
    
    protected $description = 'Reverse IB bonus distributions based on user forex accounts → meta deals → IB transactions. Reverses from all users who received bonuses.';

    private $insufficientBalanceUsers = [];
    private $processedUsers = [];
    private $ibBalanceAdjustments = [];
    private $deletedTransactions = 0;
    private $deletedMetaDeals = 0;
    private $totalReversedAmount = 0;
    private $totalIbBalanceAdjusted = 0;
    private $metaDealIds = [];
    private $forexLogins = [];

    public function handle()
    {
        $email = $this->argument('email');
        $dateInput = $this->argument('date');
        $isDryRun = $this->option('dry-run');

        // Validate date format
        try {
            $startDate = Carbon::parse($dateInput)->startOfDay();
        } catch (\Exception $e) {
            $this->error("Invalid date format. Please use Y-m-d format (e.g., 2025-01-01)");
            return 1;
        }

        // Find the user
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No user found with email: {$email}");
            return 1;
        }

        $this->info("=".str_repeat("=", 70));
        $this->info(" IB BONUS REVERSAL PROCESS");
        $this->info("=".str_repeat("=", 70));
        $this->info("User: {$user->full_name} ({$email})");
        $this->info("User ID: {$user->id}");
        $this->info("Start Date: {$startDate->format('Y-m-d')}");
        $this->info("Mode: " . ($isDryRun ? "DRY RUN (No changes will be made)" : "LIVE"));
        $this->info("=".str_repeat("=", 70));
        $this->newLine();

        if ($isDryRun) {
            $this->warn("⚠️  DRY RUN MODE - No actual changes will be made to the database.");
            $this->newLine();
        }

        // Step 1: Get forex accounts (logins) for the provided user ONLY
        $this->info("Step 1: Getting forex accounts for user...");
        $this->forexLogins = ForexAccount::where('user_id', $user->id)
            ->whereNotNull('login')
            ->pluck('login')
            ->unique()
            ->toArray();
        
        $this->info("  Forex accounts (logins) found: " . count($this->forexLogins));
        if (!empty($this->forexLogins)) {
            $this->line("  Logins: " . implode(', ', $this->forexLogins));
        }
        $this->newLine();

        if (empty($this->forexLogins)) {
            $this->warn("No forex accounts found for this user. Nothing to reverse.");
            return 0;
        }

        // Step 2: Get meta deals for these logins from the start date
        $this->info("Step 2: Getting meta deals for forex accounts...");
        $metaDeals = MetaDeal::whereIn('login', $this->forexLogins)
            ->where('time', '>=', $startDate)
            ->get();
        
        $this->metaDealIds = $metaDeals->pluck('id')->toArray();
        $dealNumbers = $metaDeals->pluck('deal')->unique()->toArray();
        
        $this->info("  Meta deals found: " . count($this->metaDealIds));
        $this->info("  Unique deal numbers: " . count($dealNumbers));
        $this->newLine();

        if (empty($this->metaDealIds)) {
            $this->warn("No meta deals found for the forex accounts from the specified date. Nothing to reverse.");
            return 0;
        }

        // Step 3: Get quarter tables for the date range
        $endDate = Carbon::now()->endOfDay();
        $quarterTables = $this->getQuarterTablesForDateRange($startDate, $endDate);

        if (empty($quarterTables)) {
            $this->warn("No quarter tables found for the specified date range.");
            return 0;
        }

        $this->info("Step 3: Quarter tables to search: " . implode(', ', $quarterTables));
        $this->newLine();

        // Step 4: Find ALL IB transactions that reference these meta deals
        // Get ALL users who received bonuses from these deals
        $this->info("Step 4: Finding IB transactions for these meta deals...");
        $transactionData = $this->findTransactionsForMetaDeals($dealNumbers, $this->forexLogins, $startDate, $quarterTables);
        
        $this->info("  IB transactions found: " . $transactionData['total_count']);
        $this->info("  Users who received bonuses: " . count($transactionData['bonus_sums']));
        
        // Show breakdown of recipients
        if (!empty($transactionData['bonus_sums'])) {
            $this->newLine();
            $this->info("  Bonus Recipients Breakdown:");
            foreach ($transactionData['bonus_sums'] as $recipientId => $amount) {
                $recipient = User::find($recipientId);
                $recipientEmail = $recipient->email ?? 'N/A';
                $this->line("    - User {$recipientId} ({$recipientEmail}): " . number_format($amount, 2));
            }
        }
        $this->newLine();

        if (empty($transactionData['bonus_sums'])) {
            $this->warn("No IB transactions found for the meta deals. Nothing to reverse.");
            return 0;
        }

        if (!$isDryRun) {
            DB::beginTransaction();
        }

        try {
            // Step 5: Process balance reversals for ALL recipients
            $this->info("Step 5: Processing balance reversals for all recipients...");
            $progressBar = $this->output->createProgressBar(count($transactionData['bonus_sums']));
            $progressBar->start();

            foreach ($transactionData['bonus_sums'] as $recipientId => $totalBonus) {
                $this->processUserReversal($recipientId, $totalBonus, $isDryRun);
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            // Step 6: Delete IB transactions from quarter tables
            $this->info("Step 6: Deleting IB transactions from quarter tables...");
            foreach ($quarterTables as $tableName) {
                $this->deleteTransactionsFromTable($tableName, $dealNumbers, $this->forexLogins, $startDate, $isDryRun);
            }
            $this->newLine();

            // Step 7: Delete meta deals for the provided user's forex accounts
            $this->info("Step 7: Deleting meta deals...");
            $this->deleteMetaDeals($this->metaDealIds, $isDryRun);
            $this->newLine();

            // Step 8: Update user ib_balance column for all recipients
            $this->info("Step 8: Updating user IB balance columns...");
            $this->updateUserIbBalances($isDryRun);
            $this->newLine();

            if (!$isDryRun) {
                DB::commit();
                $this->info("✅ Database changes committed successfully.");
            }

            // Generate and display report
            $this->generateReport($user, $startDate, $transactionData, $isDryRun);

            return 0;

        } catch (\Exception $e) {
            if (!$isDryRun) {
                DB::rollBack();
            }
            $this->error("Error during reversal process: " . $e->getMessage());
            Log::error("ReverseIbBonusDistribution error: " . $e->getMessage(), [
                'email' => $email,
                'date' => $dateInput,
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Get quarter tables that contain data for the date range
     */
    private function getQuarterTablesForDateRange($startDate, $endDate): array
    {
        $tables = [];
        $startYear = $startDate->year;
        $endYear = $endDate->year;

        for ($year = $startYear; $year <= $endYear; $year++) {
            $yearPeriods = IBTransactionPeriodService::getYearPeriods($year);

            foreach ($yearPeriods as $period) {
                $periodRange = IBTransactionPeriodService::getPeriodDateRange($period);

                if ($periodRange['start']->lte($endDate) && $periodRange['end']->gte($startDate)) {
                    $tableName = IBTransactionPeriodService::getTableName($period);
                    if (Schema::hasTable($tableName)) {
                        $tables[] = $tableName;
                    }
                }
            }
        }

        return $tables;
    }

    /**
     * Find IB transactions that reference the meta deals
     * Returns ALL users who received bonuses from these deals
     */
    private function findTransactionsForMetaDeals(array $dealNumbers, array $logins, $startDate, array $quarterTables): array
    {
        $bonusSums = [];
        $totalCount = 0;

        foreach ($quarterTables as $tableName) {
            // Find transactions matching the deals by deal number or login in manual_field_data
            $query = DB::table($tableName)
                ->where('type', 'ib_bonus')
                ->where('status', 'success')
                ->where('created_at', '>=', $startDate)
                ->where(function ($q) use ($dealNumbers, $logins) {
                    // Match by deal number in manual_field_data JSON
                    foreach ($dealNumbers as $deal) {
                        $q->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.deal')) = ?", [(string)$deal]);
                    }
                    // Also match by login in manual_field_data
                    foreach ($logins as $login) {
                        $q->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.login')) = ?", [(string)$login]);
                    }
                });

            $transactions = $query->get();

            foreach ($transactions as $txn) {
                $totalCount++;
                
                // Sum bonuses per recipient (user_id = who received the bonus)
                if (!isset($bonusSums[$txn->user_id])) {
                    $bonusSums[$txn->user_id] = 0;
                }
                $bonusSums[$txn->user_id] += (float) $txn->amount;
            }
        }

        return [
            'bonus_sums' => $bonusSums,
            'total_count' => $totalCount
        ];
    }

    /**
     * Process balance reversal for a single user
     */
    private function processUserReversal($userId, $totalBonus, $isDryRun): void
    {
        $user = User::find($userId);
        $account = Account::where('user_id', $userId)
            ->where('balance', 'ib_wallet')
            ->first();

        if (!$account) {
            $this->insufficientBalanceUsers[] = [
                'user_id' => $userId,
                'email' => $user->email ?? 'N/A',
                'full_name' => $user->full_name ?? 'N/A',
                'expected_reversal' => $totalBonus,
                'current_balance' => 0,
                'subtracted_amount' => 0,
                'remaining_to_subtract' => $totalBonus,
                'reason' => 'IB wallet not found'
            ];
            return;
        }

        $currentBalance = (float) $account->amount;
        $subtractedAmount = 0;
        $remainingToSubtract = 0;

        if ($currentBalance >= $totalBonus) {
            $subtractedAmount = $totalBonus;
            $newBalance = $currentBalance - $totalBonus;

            if (!$isDryRun) {
                $account->amount = $newBalance;
                $account->save();
            }
        } else {
            $subtractedAmount = $currentBalance;
            $remainingToSubtract = $totalBonus - $currentBalance;

            if (!$isDryRun) {
                $account->amount = 0;
                $account->save();
            }

            $this->insufficientBalanceUsers[] = [
                'user_id' => $userId,
                'email' => $user->email ?? 'N/A',
                'full_name' => $user->full_name ?? 'N/A',
                'expected_reversal' => $totalBonus,
                'current_balance' => $currentBalance,
                'subtracted_amount' => $subtractedAmount,
                'remaining_to_subtract' => $remainingToSubtract,
                'reason' => 'Insufficient balance'
            ];
        }

        $this->processedUsers[] = [
            'user_id' => $userId,
            'email' => $user->email ?? 'N/A',
            'full_name' => $user->full_name ?? 'N/A',
            'original_balance' => $currentBalance,
            'reversed_amount' => $subtractedAmount,
            'expected_reversal' => $totalBonus,
            'new_balance' => $isDryRun ? ($currentBalance - $subtractedAmount) : $account->amount,
            'ib_balance_adjustment' => $subtractedAmount,
        ];

        $this->ibBalanceAdjustments[$userId] = $subtractedAmount;
        $this->totalReversedAmount += $subtractedAmount;
        $this->totalIbBalanceAdjusted += $subtractedAmount;
    }

    /**
     * Delete transactions from a quarter table that match the meta deals
     */
    private function deleteTransactionsFromTable($tableName, array $dealNumbers, array $logins, $startDate, $isDryRun): void
    {
        $query = DB::table($tableName)
            ->where('type', 'ib_bonus')
            ->where('status', 'success')
            ->where('created_at', '>=', $startDate)
            ->where(function ($q) use ($dealNumbers, $logins) {
                foreach ($dealNumbers as $deal) {
                    $q->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.deal')) = ?", [(string)$deal]);
                }
                foreach ($logins as $login) {
                    $q->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.login')) = ?", [(string)$login]);
                }
            });

        $count = $query->count();

        $this->info("  - {$tableName}: {$count} transactions to delete");
        $this->deletedTransactions += $count;

        if (!$isDryRun && $count > 0) {
            $ids = $query->pluck('id')->toArray();
            collect($ids)->chunk(1000)->each(function ($chunk) use ($tableName) {
                DB::table($tableName)->whereIn('id', $chunk->toArray())->delete();
            });
        }
    }

    /**
     * Delete meta deals by IDs
     */
    private function deleteMetaDeals(array $metaDealIds, $isDryRun): void
    {
        $count = count($metaDealIds);

        $this->info("  - Meta deals to delete: {$count}");
        $this->deletedMetaDeals = $count;

        if (!$isDryRun && $count > 0) {
            collect($metaDealIds)->chunk(1000)->each(function ($chunk) {
                MetaDeal::whereIn('id', $chunk->toArray())->delete();
            });
        }
    }

    /**
     * Update user ib_balance columns based on actual subtracted amounts
     */
    private function updateUserIbBalances($isDryRun): void
    {
        if ($isDryRun) {
            $this->info("  - IB balance adjustments to apply: " . count($this->ibBalanceAdjustments) . " users");
            $this->info("  - Total IB balance to adjust: " . number_format($this->totalIbBalanceAdjusted, 2));
            return;
        }

        $adjustedCount = 0;
        foreach ($this->ibBalanceAdjustments as $userId => $actualAmount) {
            if ($actualAmount > 0) {
                DB::table('users')
                    ->where('id', $userId)
                    ->decrement('ib_balance', $actualAmount);
                $adjustedCount++;
            }
        }

        $this->info("  - IB balance adjusted for {$adjustedCount} users");
        $this->info("  - Total IB balance adjusted: " . number_format($this->totalIbBalanceAdjusted, 2));
    }

    /**
     * Generate detailed report
     */
    private function generateReport($user, $startDate, $transactionData, $isDryRun): void
    {
        $this->newLine();
        $this->info("=".str_repeat("=", 70));
        $this->info(" REVERSAL REPORT" . ($isDryRun ? " (DRY RUN)" : ""));
        $this->info("=".str_repeat("=", 70));
        $this->newLine();

        // Summary Section
        $this->info("📊 SUMMARY");
        $this->info("-".str_repeat("-", 40));
        $this->line("  User: {$user->full_name} ({$user->email})");
        $this->line("  User ID: {$user->id}");
        $this->line("  Reversal Start Date: {$startDate->format('Y-m-d')}");
        $this->line("  Forex Accounts (Logins): " . count($this->forexLogins));
        $this->line("  Meta Deals Found: " . count($this->metaDealIds));
        $this->line("  IB Transactions Found: " . $transactionData['total_count']);
        $this->line("  Bonus Recipients: " . count($transactionData['bonus_sums']));
        $this->line("  Total Wallet Amount Reversed: " . number_format($this->totalReversedAmount, 2) . " " . base_currency());
        $this->line("  Total IB Balance Adjusted: " . number_format($this->totalIbBalanceAdjusted, 2) . " " . base_currency());
        $this->line("  Transactions Deleted: {$this->deletedTransactions}");
        $this->line("  Meta Deals Deleted: {$this->deletedMetaDeals}");
        $this->newLine();

        // Successfully Processed Users (Bonus Recipients)
        if (!empty($this->processedUsers)) {
            $this->info("✅ BONUS RECIPIENTS - BALANCE REVERSED");
            $this->info("-".str_repeat("-", 40));
            
            $headers = ['User ID', 'Email', 'Name', 'Original Bal', 'Expected', 'Reversed', 'New Bal', 'IB Adj'];
            $rows = array_map(function ($u) {
                return [
                    $u['user_id'],
                    $u['email'],
                    substr($u['full_name'], 0, 15),
                    number_format($u['original_balance'], 2),
                    number_format($u['expected_reversal'], 2),
                    number_format($u['reversed_amount'], 2),
                    number_format($u['new_balance'], 2),
                    number_format($u['ib_balance_adjustment'], 2),
                ];
            }, $this->processedUsers);

            $this->table($headers, $rows);
            $this->newLine();
        }

        // Insufficient Balance Users
        if (!empty($this->insufficientBalanceUsers)) {
            $this->warn("⚠️  USERS WITH INSUFFICIENT BALANCE");
            $this->info("-".str_repeat("-", 40));
            
            $headers = ['User ID', 'Email', 'Name', 'Expected', 'Balance', 'Subtracted', 'Remaining', 'Reason'];
            $rows = array_map(function ($u) {
                return [
                    $u['user_id'],
                    $u['email'],
                    substr($u['full_name'], 0, 15),
                    number_format($u['expected_reversal'], 2),
                    number_format($u['current_balance'], 2),
                    number_format($u['subtracted_amount'], 2),
                    number_format($u['remaining_to_subtract'], 2),
                    $u['reason']
                ];
            }, $this->insufficientBalanceUsers);

            $this->table($headers, $rows);
            
            $totalRemaining = array_sum(array_column($this->insufficientBalanceUsers, 'remaining_to_subtract'));
            $this->error("Total unrecoverable amount: " . number_format($totalRemaining, 2) . " " . base_currency());
            $this->newLine();
        } else {
            $this->info("✅ No users with insufficient balance issues.");
            $this->newLine();
        }

        // Footer
        $this->info("=".str_repeat("=", 70));
        if ($isDryRun) {
            $this->warn("This was a DRY RUN. No actual changes were made.");
            $this->info("Run without --dry-run to execute the reversal.");
        } else {
            $this->info("Reversal completed successfully at " . Carbon::now()->format('Y-m-d H:i:s'));
        }
        $this->info("=".str_repeat("=", 70));

        // Log the operation
        Log::info("IB Bonus Reversal completed", [
            'user_email' => $user->email,
            'user_id' => $user->id,
            'start_date' => $startDate->format('Y-m-d'),
            'forex_logins' => $this->forexLogins,
            'meta_deals_count' => count($this->metaDealIds),
            'ib_transactions_count' => $transactionData['total_count'],
            'bonus_recipients_count' => count($transactionData['bonus_sums']),
            'processed_users' => count($this->processedUsers),
            'insufficient_balance_users' => count($this->insufficientBalanceUsers),
            'total_wallet_reversed' => $this->totalReversedAmount,
            'total_ib_balance_adjusted' => $this->totalIbBalanceAdjusted,
            'deleted_transactions' => $this->deletedTransactions,
            'deleted_meta_deals' => $this->deletedMetaDeals,
            'dry_run' => $isDryRun
        ]);
    }
}
