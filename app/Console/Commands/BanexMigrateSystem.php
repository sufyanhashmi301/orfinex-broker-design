<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\Ranking;
use Illuminate\Console\Command;
use DB;
use App\Enums\TxnStatus;
use App\Enums\KYCStatus;
use App\Enums\TxnType;
use App\Models\User;
use App\Models\Account;
use App\Models\Ledger;
use App\Enums\AccountBalanceType;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Hash;
use Txn;
use Illuminate\Support\Facades\Log;

class BanexMigrateSystem extends Command
{
    protected $signature = 'migrate:banex-system';
    protected $description = 'Merges users from the primex_users backup table to the current users table, and updates wallet balances';

    public function handle()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        DB::table('admins')->truncate();
        DB::table('users')->truncate();
        DB::table('accounts')->truncate();
//        DB::table('countries')->truncate();
//        DB::table('advertisement_materials')->truncate();
//        DB::table('banners')->truncate();
        DB::table('blogs')->truncate();
//        DB::table('groups')->truncate();
//        DB::table('customer_groups')->truncate();
//        DB::table('customer_group_has_customers')->truncate();
//        DB::table('departments')->truncate();
//        DB::table('department_has_staff')->truncate();
//        DB::table('designations')->truncate();
//        DB::table('designation_has_staff')->truncate();
//        DB::table('employments')->truncate();
        DB::table('ledgers')->truncate();
//        DB::table('ib_questions')->truncate();
        DB::table('ib_question_answers')->truncate();
        DB::table('ib_schemas')->truncate();
        DB::table('ib_transactions')->truncate();
//        DB::table('kyc_levels')->truncate();
//        DB::table('kyc_sub_levels')->truncate();
//        DB::table('kyc_user')->truncate();
//        DB::table('kycs')->truncate();
//        DB::table('black_list_countries')->truncate();
//        DB::table('admin_login_activities')->truncate();
        DB::table('login_activities')->truncate();
        DB::table('transactions')->truncate();
        DB::table('forex_accounts')->truncate();
        DB::table('ib_transactions')->truncate();
        DB::table('meta_deals')->truncate();
        DB::table('meta_transactions')->truncate();
//        DB::table('multi_levels')->truncate();
//        DB::table('multi_level_rebate_rule')->truncate();
//        DB::table('rebate_rules')->truncate();
//        DB::table('rebate_rule_symbol_group')->truncate();
//        DB::table('risk_profile_tags_users')->truncate();
//        DB::table('risk_profile_tag_user')->truncate();
        DB::table('scheduled_task')->truncate();
//        DB::table('schemas')->truncate();
//        DB::table('swap_based_accounts')->truncate();
//        DB::table('swap_free_accounts')->truncate();
//        DB::table('symbols')->truncate();
//        DB::table('symbol_groups')->truncate();
//        DB::table('symbol_symbol_group')->truncate();
        DB::table('invests')->truncate();
        DB::table('subscriptions')->truncate();
        DB::table('messages')->truncate();
        DB::table('password_resets')->truncate();
        DB::table('notifications')->truncate();
        DB::table('referral_links')->truncate();
        DB::table('referral_relationships')->truncate();
        DB::table('user_metas')->truncate();
        DB::table('tickets')->truncate();
        DB::table('invests')->truncate();
        DB::table('withdraw_accounts')->truncate();
        // Fetch the rank that will be assigned to all users
        $rank = Ranking::find(1);
//        dd($rank);
        if (!$rank) {
            $this->error('Ranking with ID 1 not found. Migration aborted.');
            return;
        }

        $duplicateCount = 0;
        $invalidEmailCount = 0;
        $createdUsersCount = 0;

        // Process users in chunks to avoid memory issues, with an orderBy clause
        DB::table('banex_olds')->orderBy('id')->chunk(1000, function ($backupUsers) use ($rank, &$duplicateCount, &$invalidEmailCount, &$createdUsersCount) {
            foreach ($backupUsers as $backupUser) {
                // Validate the email address
                if (!filter_var($backupUser->email, FILTER_VALIDATE_EMAIL)) {
                    // Log invalid email for future reference
                    $this->info("Invalid email: name: {$backupUser->first_name}, Email: {$backupUser->email}");
                    Log::warning("Invalid email found during migration: {$backupUser->email}");
                    $invalidEmailCount++;
                    continue; // Skip to the next user
                }

                // Check if the email already exists in the users table
                $existingUser = User::where('email', $backupUser->email)->first();

                if ($existingUser) {
                    // Log duplicate email
                    $this->info("Email already exists: name: {$backupUser->first_name}, Email: {$existingUser->email}");
                    Log::info("Duplicate email found during migration: {$backupUser->email}");
                    $duplicateCount++;
                } else {
                    // Process name splitting
                    $firstName = $backupUser->first_name;
                    $lastName = $backupUser->last_name;

                    if (empty($lastName)) {
                        // Handle splitting the name into first_name and last_name
                        $nameParts = explode(' ', $backupUser->first_name);
                        $firstName = array_shift($nameParts); // First name
                        $lastName = implode(' ', $nameParts); // Remaining parts as last name

                        // If last name is still empty, set it to first name
                        if (empty($lastName)) {
                            $lastName = $firstName;
                        }
                    }

                    // Generate a unique username
                    $usernameBase = $firstName;
                    $username = $usernameBase . rand(1000, 9999);
                    while (User::where('username', $username)->exists()) {
                        $username = $usernameBase . rand(1000, 9999);
                    }

                    // Determine KYC level (hardcoded as Level2 here)
                    $kyc = KYCStatus::Level2->value;
                    $countryName = 'United Arab Emirates';
                    $phone = '+971';
                    $country = Country::where('name',$backupUser->country)->first();
                    if($country){
                        $countryName = $country->name;
                        $phone = '+'.$country->country_code;
                    }
//dd($rank->id);
                    // Create a new User instance
                    $user = new User();
                    $user->ranking_id = $rank->id;
                    $user->rankings = json_encode([$rank->id]);
                    $user->first_name = $firstName;
                    $user->last_name = $lastName;
                    $user->city = $backupUser->city ?? 'Unknown';
                    $user->country = $countryName;
                    $user->phone = $phone;
                    $user->username = $username;
                    $user->email = $backupUser->email;
                    $user->email_verified_at = Carbon::now();
                    $user->gender = 'other'; // Default to 'other'
                    $user->status = 1; // Assuming active status for all users
                    $user->kyc = $kyc;
                    $user->kyc_credential = null; // Assuming this is a JSON field
                    $user->created_at = Carbon::now();
                    $user->updated_at = Carbon::now();
                    $user->password = Hash::make('banex12345'); // Default password

                    // Save the user
                    try {
                        $user->save();
                        $createdUsersCount++; // Increment created users counter
                    } catch (\Exception $e) {
                        // Log any error during the saving process
                        $this->error("Failed to create user: {$backupUser->first_name}, Email: {$backupUser->email}. Error: " . $e->getMessage());
                        Log::error("Failed to create user during migration: " . $e->getMessage());
                    }
                }
            }
        });

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Display the summary
        $this->info("Completed merging users from backup.");
        $this->info("Total users created: {$createdUsersCount}");
        $this->info("Total duplicate emails found: {$duplicateCount}");
        $this->info("Total invalid emails found: {$invalidEmailCount}");

        // Log the summary for future reference
        Log::info("Migration completed. Total users created: {$createdUsersCount}, Total duplicates: {$duplicateCount}, Total invalid emails: {$invalidEmailCount}");
    }
}
