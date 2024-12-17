<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteNonMatchingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-non-matching';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List users from accounts_1 not in users or forex_accounts, delete unmatched users and extra forex_accounts for retained users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            // Step 1: Get the login and email combinations from accounts_1
            $accounts1Data = DB::table('accounts_1')
                ->select('login', 'email')
                ->get();

            if ($accounts1Data->isEmpty()) {
                $this->info('No data found in accounts_1.');
                return;
            }

            // Step 2: Find accounts_1 entries not in users or forex_accounts
            $missingAccounts = $accounts1Data->filter(function ($account) {
                $userExists = DB::table('users')
                    ->where('email', $account->email)
                    ->exists();

                $forexAccountExists = DB::table('forex_accounts')
                    ->where('login', $account->login)
                    ->exists();

                return !$userExists && !$forexAccountExists;
            });

            if ($missingAccounts->isEmpty()) {
                $this->info('No users found in accounts_1 missing from users or forex_accounts.');
            } else {
                $this->info('Users in accounts_1 but not in users or forex_accounts:');
                $this->table(
                    ['Login', 'Email'],
                    $missingAccounts->map(function ($account) {
                        return (array) $account;
                    })->toArray()
                );
            }

            // Step 3: Fetch users to retain based on accounts_1
            $userIdsToRetain = DB::table('forex_accounts')
                ->join('users', 'forex_accounts.user_id', '=', 'users.id')
                ->whereIn('forex_accounts.login', $accounts1Data->pluck('login'))
                ->whereIn('users.email', $accounts1Data->pluck('email'))
                ->pluck('users.id');

            if ($userIdsToRetain->isEmpty()) {
                $this->info('No users to retain.');
                return;
            }

            // Step 4: Delete extra forex_accounts for retained users
            $forexAccountsToDelete = DB::table('forex_accounts')
                ->whereIn('user_id', $userIdsToRetain)
                ->whereNotIn('login', $accounts1Data->pluck('login'))
                ->pluck('id');

            if ($forexAccountsToDelete->isNotEmpty()) {
                DB::table('forex_accounts')->whereIn('id', $forexAccountsToDelete)->delete();
                $this->info("Deleted {$forexAccountsToDelete->count()} extra forex_accounts for retained users.");
            } else {
                $this->info('No extra forex_accounts found to delete for retained users.');
            }

            // Step 5: Find user IDs to delete
            $userIdsToDelete = DB::table('users')
                ->whereNotIn('id', $userIdsToRetain)
                ->pluck('id');

            $totalDeletedUsers = $userIdsToDelete->count();

            if ($totalDeletedUsers === 0) {
                $this->info('No users found to delete.');
                return;
            }

            // Step 6: Delete from related tables
            $relatedTables = [
                'transactions',
                'forex_accounts',
                'accounts',
                'login_activities',
                'referral_links',
                'referral_relationships',
                'tickets',
                'notifications',
                'user_ib_rules',
                'user_languages',
                'withdraw_accounts',
                'ib_question_answers',
                'ib_transactions',
                'messages',
                'meta_deals',
                'notes',
                'rebate_records',
                'risk_profile_tag_user',
                'risk_profile_tags_users',
            ];

            foreach ($relatedTables as $table) {
                DB::table($table)->whereIn('user_id', $userIdsToDelete)->delete();
            }

            // Step 7: Delete from users table
            DB::table('users')->whereIn('id', $userIdsToDelete)->delete();

            // Output the total count of deleted users
            $this->info("Deleted $totalDeletedUsers users and their related data successfully.");
        });
    }
}
