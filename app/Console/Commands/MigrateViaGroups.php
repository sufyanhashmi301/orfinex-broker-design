<?php

namespace App\Console\Commands;

use App\Models\Country;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\Ranking;
use App\Models\User;
use App\Services\ForexApiService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MigrateViaGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate users and forex accounts from API by groups';

    public function handle()
    {
//        Artisan::call('reset:data');

        // List of groups to process
        $groups = [
            'MYMA4_B\ST 10',
//            'MYMA4_B\ST_15',
//            'MYMA4_B\ECN_2_5USD',
//            'MYMA4_B\ECN_2_7USD',
//            'MYMA4_B\Stnd-20-USD',
//            'MYMA4_B\Closed Accounts',
//            'MyMaa\StandardAccount',
//            'MyMaa\PremiumAccount',
//            'MyMaa\TraderAccount',
//            'MyMaa\ECNClassic',
//            'MyMaa\RawECN',
//            'MyMaaFix\ECN',
//            'MyMaaFix\RAW',
//            'demo\forex.hedged',
//            'demo\forex',
        ];

        $duplicateCount = 0;
        $invalidEmailCount = 0;
        $createdUsersCount = 0;
        $createdAccountsCount = 0;

        // Retrieve the default rank
        $rank = Ranking::find(1);

        if (!$rank) {
            $this->error("Default ranking not found. Migration aborted.");
            return;
        }

        $forexApiService = new ForexApiService();

        // Loop through each group
        foreach ($groups as $group) {
            $this->info("Processing group: {$group}");

            $response = $forexApiService->getClientsByGroup($group);

            if (!isset($response['success']) || $response['success'] !== true) {
                $this->error("API response indicates failure for group: {$group}. Skipping.");
                continue;
            }

            $backupUsers = $response['result'];

            foreach ($backupUsers as $backupUser) {
                // Validate email
                if (!filter_var($backupUser['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->info("Invalid email: Name: {$backupUser['firstName']}, Email: {$backupUser['email']}");
                    $invalidEmailCount++;
                    continue;
                }

                // Check for existing user
                $user = User::where('email', $backupUser['email'])->first();
                if (!$user) {
                    // Process first and last name
                    $firstName = $backupUser['firstName'];
                    $lastName = $backupUser['lastName'] ?: 'Unknown';

                    if (empty($lastName)) {
                        $nameParts = explode(' ', $firstName);
                        $firstName = array_shift($nameParts);
                        $lastName = implode(' ', $nameParts) ?: $firstName;
                    }

                    // Generate unique username
                    $usernameBase = strtolower(preg_replace('/\s+/', '', $firstName));
                    $username = $usernameBase . rand(1000, 9999);
                    while (User::where('username', $username)->exists()) {
                        $username = $usernameBase . rand(1000, 9999);
                    }

                    // Determine country and phone
                    $country = Country::where('name', $backupUser['country'])->first();
                    $countryName = $country ? $country->name : 'United Arab Emirates';
                    $phone = $backupUser['phone'] ?? '+971';

                    // Create a new user
                    $user = new User();
                    $user->ranking_id = $rank->id;
                    $user->rankings = json_encode([$rank->id]);
                    $user->first_name = $firstName;
                    $user->last_name = $lastName;
                    $user->city = $backupUser['city'] ?? 'Unknown';
                    $user->country = $countryName;
                    $user->phone = $phone;
                    $user->username = $username;
                    $user->email = $backupUser['email'];
                    $user->email_verified_at = Carbon::now();
                    $user->gender = 'other';
                    $user->status = 1; // Active
                    $user->kyc = 4;
                    $user->created_at = Carbon::now();
                    $user->updated_at = Carbon::now();
                    $user->password = Hash::make('myMaa-12345');

                    try {
                        $user->save();
                        $createdUsersCount++;
                    } catch (\Exception $e) {
                        $this->error("Failed to create user: {$backupUser['firstName']}, Email: {$backupUser['email']}. Error: " . $e->getMessage());
                        continue;
                    }
                }

                // Process Forex Account
                $existingAccount = ForexAccount::where('login', $backupUser['login'])->first();
                if ($existingAccount) {
                    $this->info("Forex account with login {$backupUser['login']} already exists, skipping.");
                    continue;
                }

                // Determine ForexSchema and account type
                $schema = ForexSchema::where('id', 9)->first();

                if (!$schema) {
                    $this->error("No valid schema found for group {$backupUser['group']}, skipping.");
                    continue;
                }

                $accountType =  'real' ;

                // Save Forex Account
                $forexAccountData = [
                    'forex_schema_id' => $schema->id,
                    'login' => $backupUser['login'],
                    'account_name' => $backupUser['name'],
                    'account_type' => $accountType,
                    'user_id' => $user->id,
                    'currency' => setting('site_currency', 'global'),
                    'group' => $backupUser['group'],
                    'leverage' => $backupUser['leverage'],
                    'balance' => $backupUser['balance'],
                    'equity' => $backupUser['balance'], // Assuming equity is prevDayBalance
                    'credit' => $backupUser['credit'],
                    'status' => ForexAccount::ONGOING,
                    'created_by' => $user->id,
                    'first_min_deposit_paid' => 0,
                    'trading_platform' => $accountType == 'demo' ? setting('demo_server', 'platform_api') : setting('live_server', 'platform_api'),
                    'server' => $accountType == 'demo' ? setting('demo_server', 'platform_api') : setting('live_server', 'platform_api'),
                    'agent' => $backupUser['agent'],
                ];

                try {
                    ForexAccount::create($forexAccountData);
                    $createdAccountsCount++;
                } catch (\Exception $e) {
                    $this->error("Failed to create Forex account for login: {$backupUser['login']}. Error: " . $e->getMessage());
                    continue;
                }
            }
            $this->info("Completed processing group: {$group}");
        }
        // Display summary
        $this->info("Migration complete.");
        $this->info("Total users created: {$createdUsersCount}");
        $this->info("Total forex accounts created: {$createdAccountsCount}");
        $this->info("Total duplicates: {$duplicateCount}");
        $this->info("Total invalid emails: {$invalidEmailCount}");

        // Log summary
        Log::info("Migration Summary: Users Created: {$createdUsersCount}, Forex Accounts Created: {$createdAccountsCount}, Duplicates: {$duplicateCount}, Invalid Emails: {$invalidEmailCount}");
    }
}
