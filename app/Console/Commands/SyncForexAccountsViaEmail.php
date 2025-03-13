<?php
namespace App\Console\Commands;

use App\Enums\ForexAccountStatus;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\User;
use App\Services\ForexApiService;
use Illuminate\Console\Command;

class SyncForexAccountsViaEmail extends Command
{
    protected $signature = 'sync:forex-accounts-via-email';
    protected $description = 'Sync Forex Accounts from external API using user emails and store them in the database';

    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        parent::__construct();
        $this->forexApiService = $forexApiService;
    }

    public function handle()
    {
        // Get the last created ForexAccount
        $lastForexAccount = ForexAccount::orderBy('id', 'desc')->first();

        if ($lastForexAccount) {
            $startingUserId = $lastForexAccount->user_id;
            $this->info("Starting from user with ID {$startingUserId}.");
        } else {
            $startingUserId = 1;
            $this->info("No previous forex account found. Starting from the first user.");
        }

        // Process users in chunks
        $users = User::where('id', '>=', $startingUserId)
            ->orderBy('id')->take(35)->get();
//            ->chunk(50, function ($users) {
                foreach ($users as $user) {
                    // Fetch account data using email
                    $email = $user->email;
                    $response = $this->forexApiService->getUserByEmail(['email' => $email]);

                    if ($response['success'] && isset($response['result']) && is_array($response['result'])) {
                        foreach ($response['result'] as $accountData) {

                            // Check if the account with the same login already exists
                            $existingAccount = ForexAccount::where('login', $accountData['login'])->first();
                            if ($existingAccount) {
                                $this->info("Forex account with login {$accountData['login']} already exists, skipping.");
                                continue;
                            }

                            // Determine the ForexSchema and account type based on group
//                            $schema = ForexSchema::where('real_swap_free', $accountData['group'])
//                                ->orWhere('real_islamic', $accountData['group'])
//                                ->orWhere('demo_swap_free', $accountData['group'])
//                                ->orWhere('demo_islamic', $accountData['group'])
//                                ->first();
//
//                            if ($schema) {
//                                // Determine account type
//                                if ($schema->real_swap_free == $accountData['group'] || $schema->real_islamic == $accountData['group']) {
//                                    $accountType = 'real';
//                                } elseif ($schema->demo_swap_free == $accountData['group'] || $schema->demo_islamic == $accountData['group']) {
//                                    $accountType = 'demo';
//                                } else {
//                                    $this->error("No valid account type could be determined for group {$accountData['group']}.");
//                                    continue;
//                                }

                            $schema = ForexSchema::find(1);
                            if (strpos($accountData['group'], 'demo') !== false) {
                                $accountType = 'demo';
                            } else {
                                $accountType = 'real';
                            }
                            if ($schema) {

                                // Prepare the account data to be saved
                                $forexAccountData = [
                                    'forex_schema_id' => $schema->id,
                                    'login' => $accountData['login'],
                                    'account_name' => $accountData['name'],
                                    'account_type' => $accountType,
                                    'user_id' => $user->id,
                                    'currency' => setting('site_currency', 'global'),
                                    'group' => $accountData['group'],
                                    'leverage' => $accountData['leverage'],
                                    'balance' => $accountData['balance'],
                                    'equity' => $accountData['prevDayBalance'], // Assuming equity is taken from prevDayBalance
                                    'credit' => $accountData['credit'], // Credit from API response
                                    'status' => ForexAccountStatus::Ongoing,
                                    'created_by' => $user->id,
                                    'first_min_deposit_paid' => 1,
                                    'trading_platform' => $accountType == 'demo' ? setting('demo_server', 'platform_api') : setting('live_server', 'platform_api'),
                                    'server' => $accountType == 'demo' ? setting('demo_server', 'platform_api') : setting('live_server', 'platform_api'),
                                    'agent' => $accountData['agent'], // Agent from API response
                                ];

                                // Save the account to the database
                                ForexAccount::create($forexAccountData);

//                                $this->info("Forex account with login {$accountData['login']} for user {$user->email} synced successfully.");
                            } else {
                                // Log the missing group and login
                                $this->error("No matching ForexSchema found for group {$accountData['group']} with login {$accountData['login']}.");
                            }
                        }
                    } else {
                        $this->error("Failed to fetch account data for user {$user->email}.");
                    }
                }
//            });

        $this->info("Forex accounts synchronization completed.");
    }
}
