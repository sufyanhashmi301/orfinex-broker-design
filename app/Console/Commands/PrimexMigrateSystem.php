<?php

namespace App\Console\Commands;

use App\Models\Ranking;
use Illuminate\Console\Command;
use DB;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\User;
use App\Models\Account;
use App\Models\Ledger;
use App\Enums\AccountBalanceType;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Hash;
use Txn;

class PrimexMigrateSystem extends Command
{
    protected $signature = 'migrate:primex-system';
    protected $description = 'Merges users from the primex_users backup table to the current users table, and updates wallet balances';

    public function handle()
    {
        // Disable foreign key checks and truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('risk_profile_tags_users')->truncate();
        DB::table('accounts')->truncate();
        DB::table('ledgers')->truncate();
        DB::table('users')->truncate();
        DB::table('transactions')->truncate();
        DB::table('old_transactions')->truncate();
        DB::table('meta_transactions')->truncate();
        DB::table('ib_transactions')->truncate();
        DB::table('forex_accounts')->truncate();
        DB::table('messages')->truncate();
        DB::table('notifications')->truncate();
        DB::table('referral_links')->truncate();
        DB::table('referral_relationships')->truncate();
        DB::table('user_metas')->truncate();
        DB::table('tickets')->truncate();
        DB::table('invests')->truncate();
        DB::table('withdraw_accounts')->truncate();
        DB::table('admin_login_activities')->truncate();
        DB::table('login_activities')->truncate();
        DB::table('meta_deals')->truncate();
        DB::table('multi_levels')->truncate();
        DB::table('multi_level_rebate_rule')->truncate();
        DB::table('rebate_rules')->truncate();
        DB::table('rebate_rule_symbol_group')->truncate();
        DB::table('symbols')->truncate();
        DB::table('symbol_groups')->truncate();
        DB::table('symbol_symbol_group')->truncate();
        DB::table('black_list_countries')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Tables truncated successfully.');

        // Fetch the rank that will be assigned to all users
        $rank = Ranking::find(1);
        if (!$rank) {
            $this->error('Ranking with ID 1 not found. Migration aborted.');
            return;
        }

        $duplicateCount = 0;
        $invalidEmailCount = 0;
        $createdUsersCount = 0;
        $mainWalletUserCount = 0;
        $ibWalletUserCount = 0;
        $mainWalletTotalAmount = BigDecimal::of(0);
        $ibWalletTotalAmount = BigDecimal::of(0);

        // Process users in chunks to avoid memory issues, with an orderBy clause
        DB::table('primex_users')->orderBy('id')->chunk(1000, function ($backupUsers) use ($rank, &$duplicateCount, &$invalidEmailCount, &$createdUsersCount) {
            foreach ($backupUsers as $backupUser) {
                // Validate the email address
                if (!filter_var($backupUser->email, FILTER_VALIDATE_EMAIL)) {
                    // If email is not valid, echo the user's name and email
                    $this->info("Invalid email: name: {$backupUser->name}, Email: {$backupUser->email}");
                    $invalidEmailCount++;
                    continue; // Skip to the next user
                }

                // Check if the email already exists in the users table
                $existingUser = User::where('email', $backupUser->email)->first();

                if ($existingUser) {
                    // If email already exists, echo the email and username
                    $this->info("Email already exists: name: {$backupUser->name}, Email: {$existingUser->email}");
                    $duplicateCount++;
                } else {
                    // Handle splitting the name into first_name and last_name
                    $nameParts = explode(' ', $backupUser->name);
                    $firstName = array_shift($nameParts); // Take the first part as the first name
                    $lastName = implode(' ', $nameParts); // Combine the remaining parts as the last name

                    // If lastName is empty, set it to firstName
                    if (empty($lastName)) {
                        $lastName = $firstName;
                    }

                    // Generate a unique username
                    $usernameBase = $firstName;
                    $username = $usernameBase . rand(1000, 9999);
                    while (User::where('username', $username)->exists()) {
                        $username = $usernameBase . rand(1000, 9999);
                    }

                    // Determine KYC level
                    $kyc = match($backupUser->verification_level) {
                        'Not Verified' => 0,
                        'Verified (Basic Level)' => 1,
                        'Verified (Advanced Level)' => 4,
                        'IB Level' => 5,
                        default => 0,
                    };

                    // Assign user attributes
                    $user = new User();
                    $user->ranking_id = $rank->id;
                    $user->rankings = json_encode([$rank->id]);
                    $user->first_name = $firstName;
                    $user->last_name = $lastName;
                    $user->city = $backupUser->city;
                    $user->country = $backupUser->country;
                    $user->phone = $backupUser->phone;
                    $user->username = $username;
                    $user->email = $backupUser->email;  // Assign email from backup user
                    $user->email_verified_at = Carbon::now();
                    $user->gender = 'other'; // Assuming 'other' if gender not provided

                    // Set status based on backupUser status
                    $user->status = $backupUser->status === 'Active' ? 1 : 0;

                    // Set KYC level
                    $user->kyc = $kyc;

                    $user->kyc_credential = null; // Assuming kyc_credential is a JSON field
                    $user->created_at = Carbon::now();
                    $user->updated_at = Carbon::now();
                    $user->password = Hash::make('primex12345');  // Default password

                    // Save the user
                    $user->save();

                    // Increment the created users counter
                    $createdUsersCount++;
                }
            }
        });

        // Process primex_ewallets and update main wallet balances
        DB::table('primex_ewallets')->orderBy('client_id', 'desc')->each(function ($wallet) use (&$mainWalletUserCount, &$mainWalletTotalAmount) {
            $user = User::where('email', $wallet->email)->first();
            if (!$user) {
                // Handle case where user does not exist (optional: create user)
                $this->info("User not found for email: {$wallet->email}");
                return;
            }

            $userAccount = get_user_account($user->id, AccountBalanceType::MAIN);
            $this->updateWalletBalance($userAccount, $wallet->balance, "Main Wallet Balance Update");

            // Update the counters and totals
            $mainWalletUserCount++;
            $mainWalletTotalAmount = $mainWalletTotalAmount->plus(BigDecimal::of($wallet->balance));
        });

        // Process primex_partnerwallet and update IB wallet balances
        DB::table('primex_partnerwallet')->orderBy('client_id', 'desc')->each(function ($wallet) use (&$ibWalletUserCount, &$ibWalletTotalAmount) {
            $user = User::where('email', $wallet->email)->first();
            if (!$user) {
                // Handle case where user does not exist (optional: create user)
                $this->info("User not found for email: {$wallet->email}");
                return;
            }

            $userAccount = get_user_account($user->id, AccountBalanceType::IB_WALLET);
            $this->updateWalletBalance($userAccount, $wallet->balance, "IB Wallet Balance Update");

            // Update the counters and totals
            $ibWalletUserCount++;
            $ibWalletTotalAmount = $ibWalletTotalAmount->plus(BigDecimal::of($wallet->balance));
        });

        // Display the summary
        $this->info("Completed merging users from backup.");
        $this->info("Total users created: {$createdUsersCount}");
        $this->info("Total duplicate emails found: {$duplicateCount}");
        $this->info("Total invalid emails found: {$invalidEmailCount}");
        $this->info("Total users processed for MAIN Wallet: {$mainWalletUserCount}");
        $this->info("Total amount migrated to MAIN Wallet: {$mainWalletTotalAmount}");
        $this->info("Total users processed for IB Wallet: {$ibWalletUserCount}");
        $this->info("Total amount migrated to IB Wallet: {$ibWalletTotalAmount}");
    }

    protected function updateWalletBalance($userAccount, $amount, $description)
    {
        // Determine transaction type based on account balance type
        if ($userAccount->balance == AccountBalanceType::MAIN) {
            $transactionType = TxnType::Deposit;
        } elseif ($userAccount->balance == AccountBalanceType::IB_WALLET) {
            $transactionType = TxnType::IB;
        } else {
            $transactionType = TxnType::Deposit; // Default to Deposit if type is unknown
        }

        // Create a transaction (adjust as needed for your transaction model)
        $transaction = Txn::new(
            $amount, 0, $amount, 'system', $description, $transactionType, TxnStatus::Success, null, null,
            $userAccount->user_id, null, 'User', [], 'Balance move from old system', $userAccount->id, 'wallet'
        );

        // Update account balance
        $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($amount));
        $userAccount->save();

        // Create ledger entry
        $this->createLedgerEntry($transaction, $this->getLedgerBalance($userAccount->wallet_id));
    }

    private function getLedgerBalance($walletId)
    {
        $latestLedgerEntry = Ledger::where('account_id', $walletId)->orderBy('id', 'desc')->first();
        return data_get($latestLedgerEntry, 'balance', 0.00);
    }

    private function createLedgerEntry($transaction, $ledgerBalance)
    {
        $ledger = new Ledger();
        $ledger->transaction_id = $transaction->id;
        $ledger->credit = $transaction->final_amount;
        $ledger->debit = 0.0;
        $ledger->account_id = $transaction->target_id;
        $balance = BigDecimal::of($ledgerBalance)->plus(BigDecimal::of($transaction->amount));

        if ($balance < BigDecimal::of(0.00)) {
            throw new \Exception(__("Unprocessable transaction."));
        }

        $ledger->balance = $balance;
        $ledger->save();

        return $ledger;
    }
}

