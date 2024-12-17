<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteUsersWithoutForexAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-without-forex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete users without forex accounts and related data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::transaction(function () {
            // Step 1: Fetch user IDs without forex accounts
            $userIds = DB::table('users')
                ->leftJoin('forex_accounts', 'users.id', '=', 'forex_accounts.user_id')
                ->whereNull('forex_accounts.id')
                ->pluck('users.id');

            $totalDeletedUsers = $userIds->count();

            if ($totalDeletedUsers === 0) {
                $this->info('No users found without forex accounts.');
                return;
            }

            // Step 2: Delete from related tables
            $relatedTables = [
                'transactions',
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
                DB::table($table)->whereIn('user_id', $userIds)->delete();
            }

            // Step 3: Delete from users table
            DB::table('users')->whereIn('id', $userIds)->delete();

            // Output the total count of deleted users
            $this->info("Deleted $totalDeletedUsers users and their related data successfully.");
        });
    }
}
