<?php

namespace App\Console\Commands;

use App\Enums\ForexAccountStatus;
use App\Models\Ranking;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ForexAccount;
use Illuminate\Support\Facades\Schema;

class MigrateOldData extends Command
{use ForexApiTrait;
    protected $signature = 'migrate:old-data';
    protected $description = 'Migrate old data from MyISAM tables to new InnoDB tables';

    public function handle()
    {
//        $tables = DB::connection('mysql')->getDoctrineSchemaManager()->listTableNames();
//        foreach ($tables as $table) {
//            Schema::disableForeignKeyConstraints();
//            DB::table($table)->truncate();
//            Schema::enableForeignKeyConstraints();
//        }
        DB::table('users')->truncate();
        DB::table('transactions')->truncate();
        DB::table('transactions')->truncate();
        DB::table('forex_accounts')->truncate();
        DB::table('messages')->truncate();
        DB::table('notifications')->truncate();
        DB::table('referral_links')->truncate();
        DB::table('referral_relationships')->truncate();
        DB::table('user_metas')->truncate();
        DB::table('tickets')->truncate();
        DB::table('invests')->truncate();

//        DB::table('withdraw_accounts')->truncate();
        // Migrate data from users table
        $usersOldData = DB::connection('old_connection')->table('users')
            ->where('id',2)->get();

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
                echo 'Login : '.$oldForexAccount->login;
                return true; // Migrate user if any account has balance > 1
            }
        }

        return false; // No account with balance > 1 found
    }

    public function getForexAccountBalance($login)
    {
        // Assuming this method returns the balance of the account using the API request
        $getUserResponse = $this->getUserApi($login);

        if (isset($getUserResponse)) {
                return BigDecimal::of($getUserResponse->object()->Balance);
        } else {
          return BigDecimal::of(0);
        }
    }


    private function migrateUserToNewDB($oldUser)
    {
        $meta = DB::connection('old_connection')
            ->table('user_metas')
            ->where('user_id', $oldUser->id)
            ->where('meta_key', 'profile_country')
            ->first('meta_value');
        $rank = Ranking::find(1);
        // Migrate user data to the new database
       $dataUser =  [
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => $oldUser->name,
            'last_name' => '',
            'username' => $oldUser->username ? $oldUser->username : $oldUser->name.rand(1000, 9999),
            'country' => $meta->meta_value,
            'phone' => $oldUser->profile_phone,
            'email' => $oldUser->email,
            'password' => $oldUser->password,
            'kyc' => 1,
            'ib_login' => $oldUser->ib_login,
            'ib_status' => $oldUser->ib_status,
            'email_verified_at' => $oldUser->created_at,
        ];
        $user = User::create($dataUser);

        // Migrate associated forex trading accounts
        $forexAccounts = DB::connection('old_connection')
            ->table('forex_tradings')
            ->where('user_id', $oldUser->id)
//            ->where('account_type', 'real')
            ->get();
//dd($forexAccounts);
        foreach ($forexAccounts as $oldForexAccount) {
            $getUserResponse = $this->getUserApi($oldForexAccount->login);

//            echo $getUserResponse."\n";
            if (isset($getUserResponse)) {
//                dd($getUserResponse->object());
                echo "Login final : ".$oldForexAccount->login;
                    $data = $getUserResponse->object();
                    $accountData['user_id'] = $user->id;
                    $accountData['forex_schema_id'] = 1;
                    $accountData['login'] = $oldForexAccount->login;
                    $accountData['account_name'] = $oldForexAccount->account_name;
                    $accountData['account_type'] = $oldForexAccount->account_type;
                    $accountData['user_id'] = $user->id;
                    $accountData['currency'] = setting('site_currency', 'global');
    //                $accountData['invest_password'] = $investPassword;
    //                $accountData['phone_password'] = $oldForexAccount->PhonePassword;
                    $accountData['group'] = $data->Group;
                    $accountData['leverage'] = $data->Leverage;
                    $accountData['status'] = ForexAccountStatus::Ongoing;
                    $accountData['server'] = 'MT5';
                    $accountData['created_by'] = $user->id;
                    $accountData['first_min_deposit_paid'] = 0;
                    $accountData['trading_platform'] = config('forextrading.tradingPlatform');
//                    dd($accountData) ;
                    ForexAccount::create($accountData);
                } else {
                    return 0;
                }
            }


    }
}
