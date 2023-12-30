<?php

namespace App\Console\Commands;

use Brick\Math\BigDecimal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ForexAccount;

class MigrateOldData extends Command
{
    protected $signature = 'migrate:old-data';
    protected $description = 'Migrate old data from MyISAM tables to new InnoDB tables';

    public function handle()
    {
        // Migrate data from users table
        $usersOldData = DB::connection('old_connection')->table('users')->get();

        foreach ($usersOldData as $oldUser) {


            // Check if the user has a forex trading account with balance > 1
            if ($this->hasForexAccountWithBalance($oldUser->id)) {
                $this->migrateUserToNewDB($oldUser);
            }
        }

        $this->info('Data migration completed successfully.');
    }

    private function hasForexAccountWithBalance($userId)
    {
        $forexAccounts = DB::connection('old_connection')
            ->table('forex_tradings')
            ->where('user_id', $userId)
            ->where('account_type', 'real')
            ->get();

        foreach ($forexAccounts as $oldForexAccount) {
            $balance = $this->getForexAccountBalance($oldForexAccount->login);

            if ($balance->isGreaterThan(1)) {
                return true; // Migrate user if any account has balance > 1
            }
        }

        return false; // No account with balance > 1 found
    }

    public function getForexAccountBalance($login)
    {
        // Assuming this method returns the balance of the account using the API request
        $getUserResponse = $this->getUserApi($login);

        if ($getUserResponse->status() == 200) {
            if (isset($getUserResponse->object()->Login)) {
                return BigDecimal::of($getUserResponse->object()->Balance);
            } else {
               return 0;
            }
        }
    }


    private function migrateUserToNewDB($olUser)
    {
        // Migrate user data to the new database
        $user = User::create([
            'id' => $olUser->id,
            // Map other fields accordingly
        ]);

        // Migrate associated forex trading accounts
        $forexAccounts = DB::connection('old_connection')
            ->table('forex_tradings')
            ->where('user_id', $olUser->id)
            ->get();

        foreach ($forexAccounts as $oldForexAccount) {
            ForexAccount::on('new_connection')->create([
                'id' => $oldForexAccount->id,
                // Map other fields accordingly
            ]);
        }
    }
}
