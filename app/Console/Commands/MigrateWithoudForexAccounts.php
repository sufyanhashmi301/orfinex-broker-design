<?php

namespace App\Console\Commands;

use App\Enums\ForexAccountStatus;
use App\Models\ForexAccount;
use App\Models\Ranking;
use App\Models\User;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateWithoudForexAccounts extends Command
{
    use ForexApiTrait;

    protected $signature = 'migrate:only-users';
    protected $description = 'Migrate old data from MyISAM tables to new InnoDB tables';

    public function handle()
    {
//        $tables = DB::connection('mysql')->getDoctrineSchemaManager()->listTableNames();
//        foreach ($tables as $table) {
//            Schema::disableForeignKeyConstraints();
//            DB::table($table)->truncate();
//            Schema::enableForeignKeyConstraints();
//        }


//        DB::table('withdraw_accounts')->truncate();
        // Migrate data from users table
        $usersOldData = DB::connection('old_connection')->table('users')
//            ->where('id',2)
            ->get();
        $userCount = 0;
        foreach ($usersOldData as $oldUser) {

            $user = User::where('email', $oldUser->email)->exists();
            if (!$user) {
                // Check if the user has a forex trading account with balance > 1
//                if ($this->hasForexAccountWithBalance($oldUser->id)) {
                    $userCount++;
                    $this->migrateUserToNewDB($oldUser);
//                }
            }
        }

        $this->info('Data migration completed successfully with Total Users ' . $userCount);
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
//                echo 'Login : '.$oldForexAccount->login;
                return true; // Migrate user if any account has balance > 1
            }
        }

        return false; // No account with balance > 1 found
    }

    public function getForexAccountBalance($login)
    {
        // Assuming this method returns the balance of the account using the API request
        $getUserResponse = $this->getUserApi($login);

        if ($getUserResponse) {
            return BigDecimal::of($getUserResponse->object()->Balance);
        } else {
            return BigDecimal::of(0);
        }
//        return BigDecimal::of(5);
    }


    private function migrateUserToNewDB($oldUser)
    {
        $userMeta = DB::connection('old_connection')
            ->table('user_metas')
            ->where('user_id', $oldUser->id)
            ->where('meta_key', 'profile_country')
            ->first('meta_value');

        $rank = Ranking::find(1);
        $parts = explode(' ', trim($oldUser->name));

// Assign the first and last names
        $first_name = isset($parts[0]) ? $parts[0] : 'name';
        $last_name = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : $first_name;

        $username = User::where('username',$oldUser->username)->exists();
        // Migrate user data to the new database
        $dataUser = [
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $oldUser->username ? (!$username ? $oldUser->username : $first_name . rand(10000, 99999)) : $first_name . rand(1000, 9999),
            'country' => $userMeta ? $userMeta->meta_value : 'United Arab Emirates',
            'phone' => $oldUser->profile_phone ? $oldUser->profile_phone : '+971',
            'email' => $oldUser->email,
            'password' => $oldUser->password,
            'kyc' => 0,
            'ib_login' => $oldUser->ib_login,
            'ib_status' => $oldUser->ib_status,
            'email_verified_at' => $oldUser->created_at,
        ];
        $user = User::create($dataUser);


        echo "Email: " . $user->email . "\n";


    }
}
