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
        // Fetch all users
        $users = User::all();

        foreach ($users as $user) {
            // Fetch account data using email
            $email = $user->email;
            $response = $this->forexApiService->getUserByEmail(['email' => $email]);

            if ($response['success'] && isset($response['result'])) {
                $accountData = $response['result'];

                // Find the matching ForexSchema based on the group
                $schema = ForexSchema::where('group', $accountData['group'])->first();

                if ($schema) {
                    // Prepare the account data to be saved
                    $forexAccountData = [
                        'forex_schema_id' => $schema->id,
                        'login' => $accountData['login'],
                        'account_name' => $accountData['name'],
                        'account_type' => strpos($accountData['group'], 'demo') !== false ? 'demo' : 'real',
                        'user_id' => $user->id,
                        'currency' => setting('site_currency', 'global'),
                        'group' => $accountData['group'],
                        'leverage' => $accountData['leverage'],
                        'balance' => $accountData['balance'],
                        'equity' => $accountData['balance'], // Assuming equity is same as balance initially
                        'status' => ForexAccountStatus::Ongoing,
                        'created_by' => $user->id,
                        'first_min_deposit_paid' => 0,
                        'trading_platform' => strpos($accountData['group'], 'demo') !== false ? setting('demo_server', 'platform_api') : setting('live_server', 'platform_api'),
                    ];

                    // Save the account to the database
                    ForexAccount::create($forexAccountData);

                    $this->info("Forex account for user {$user->email} synced successfully.");
                } else {
                    $this->error("No matching ForexSchema found for group {$accountData['group']}.");
                }
            } else {
                $this->error("Failed to fetch account data for user {$user->email}.");
            }
        }

        $this->info("Forex accounts synchronization completed.");
    }
}
