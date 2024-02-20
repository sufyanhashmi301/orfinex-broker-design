<?php

namespace App\Console\Commands;

use App\Enums\ForexAccountStatus;
use App\Models\Ranking;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ForexAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MigrateOldData extends Command
{
    use ForexApiTrait;

    protected $signature = 'migrate:old-data';
    protected $description = 'Migrate old data from MyISAM tables to new InnoDB tables';

    public function handle()
    {
//        $start = Carbon::parse('2024-01-22 00:00:00');
//        $end = Carbon::parse('2024-01-22 23:59:59');

// Get the timestamp (integer representation) of the Carbon instance
//        dd($start->timestamp,$end->timestamp);
//        $timestamp = 1702310384;
//        $dateTime = new DateTime("@$timestamp");
//        $readableDateTime = $dateTime->format('Y-m-d H:i:s');
//
////        echo $readableDateTime;
////        dd($readableDateTime);
//        dd(Hash::make('12345678'));
//        $tables = DB::connection('mysql')->getDoctrineSchemaManager()->listTableNames();
//        foreach ($tables as $table) {
//            Schema::disableForeignKeyConstraints();
//            DB::table($table)->truncate();
//            Schema::enableForeignKeyConstraints();
//        }
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        DB::table('risk_profile_tags_users')->truncate();
//        DB::table('users')->truncate();
//        DB::table('transactions')->truncate();
//        DB::table('forex_accounts')->truncate();
//        DB::table('messages')->truncate();
////        DB::table('notifications')->truncate();
//        DB::table('referral_links')->truncate();
//        DB::table('referral_relationships')->truncate();
//        DB::table('user_metas')->truncate();
//        DB::table('tickets')->truncate();
//        DB::table('invests')->truncate();
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
//        DB::table('withdraw_accounts')->truncate();
        // Migrate data from users table
        $usersOldData = DB::table('user_imports')
//            ->where('id',2)
            ->get();
        $userCount = 0;
        foreach ($usersOldData as $oldUser) {
            // Check if the user has a forex trading account with balance > 1
            if ($oldUser->email) {
                if (!User::where('email', $oldUser->email)->exists()) {
                    $this->migrateUserToNewDB($oldUser);
                    $userCount++;
                }
            } else {
                echo "Email not exist of Login: " . $oldUser->login . "\n";
            }
        }

        $this->info('Total Users ' . $userCount);
        session(['forex-count' => 0]);
        foreach ($usersOldData as $oldUser) {
            // Check if the user has a forex trading account with balance > 1
            if ($oldUser->email) {
                if (!ForexAccount::where('login', $oldUser->login)->exists()) {
                    $this->forexAccountCreate($oldUser);
                    $userCount++;
                }
            } else {
                echo "Email not exist of Login: " . $oldUser->login . "\n";
            }
        }
        $this->info('Data migration completed successfully with Total Forex Accounts ' . session('forex-count'));

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

    public function forexAccountCreate($oldUser)
    {
//dd($forexAccounts);
        $forexCount = 0;
        $getUserResponse = $this->getUserInfoApi($oldUser->login);
//            echo $getUserResponse."\n";
        if ($getUserResponse) {

                $user = User::where('email',$oldUser->email)->first();
                if($user) {
                    $data = $getUserResponse->object();
                    $accountData['user_id'] = $user->id;
                    $accountData['forex_schema_id'] = 1;
                    $accountData['login'] = $oldUser->login;
                    $accountData['account_name'] = 'account-' . str::random(4);
                    $accountData['account_type'] = 'real';
                    $accountData['user_id'] = $user->id;
                    $accountData['currency'] = setting('site_currency', 'global');
                    //                $accountData['invest_password'] = $investPassword;
                    //                $accountData['phone_password'] = $oldForexAccount->PhonePassword;
                    $accountData['group'] = $data->Group;
                    $accountData['balance'] = $data->Balance;
                    $accountData['equity'] = $data->Balance;
                    $accountData['leverage'] = $data->Leverage;
                    $accountData['status'] = ForexAccountStatus::Ongoing;
                    $accountData['server'] = config('forextrading.server');
                    $accountData['created_by'] = $user->id;
                    $accountData['first_min_deposit_paid'] = 1;
                    $accountData['trading_platform'] = config('forextrading.tradingPlatform');
//                    dd($accountData) ;
                    ForexAccount::create($accountData);
                    $forexCount = session('forex-count');
                    $forexCount++;
                    session(['forex-count' => $forexCount]);

                }
        }else{
            echo "Account not exist in MT5: " . $oldUser->login . "\n";
        }


    }


    private function migrateUserToNewDB($oldUser)
    {
//        $userMeta = DB::connection('old_connection')
//            ->table('user_metas')
//            ->where('user_id', $oldUser->id)
//            ->where('meta_key', 'profile_country')
//            ->first('meta_value');
        $rank = Ranking::find(1);

        $parts = explode(' ', trim($oldUser->f_name));

// Assign the first and last names
        $first_name = $oldUser->f_name;
        $last_name = $oldUser->l_name;
//        $last_name = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : $first_name;

        if (!filled($first_name)) {
            $first_name = 'fname';
        }
        if (!filled($last_name)) {
            $last_name = 'lname';
        }
        $targetCode = "AX"; // The country code you want to search for

        $countries = getCountries();
// Find the country with the matching code
        $matchingCountry = collect($countries)->firstWhere('code', $targetCode);

        if ($matchingCountry) {
            $countryName = $matchingCountry['name'];

        } else {
            $countryName = 'United Arab Emirates';
        }
        $parts = explode('@', $oldUser->email);;
        // Migrate user data to the new database
        $dataUser = [
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $parts[0] . rand(1000, 9999),
            'country' => $countryName,
            'phone' => '+971',
            'email' => $oldUser->email,
            'password' => Hash::make(Str::random(8)),
            'kyc' => 1,
            'ib_login' => null,
            'ib_status' => 'unprocessed',
            'email_verified_at' => $oldUser->register_time,
        ];
        $user = User::create($dataUser);

//        // Migrate associated forex trading accounts
//        $forexAccounts = DB::connection('old_connection')
//            ->table('forex_tradings')
//            ->where('user_id', $oldUser->id)
////            ->where('account_type', 'real')
//            ->get();
////dd($forexAccounts);
//        $forexCount=0;
//        foreach ($forexAccounts as $oldForexAccount) {
//            $getUserResponse = $this->getUserApi($oldForexAccount->login);
////            echo $getUserResponse."\n";
//            if ($getUserResponse) {
//
//                    $data = $getUserResponse->object();
//                    $accountData['user_id'] = $user->id;
//                    $accountData['forex_schema_id'] = 1;
//                    $accountData['login'] = $oldForexAccount->login;
//                    $accountData['account_name'] = $oldForexAccount->account_name;
//                    $accountData['account_type'] = $oldForexAccount->account_type;
//                    $accountData['user_id'] = $user->id;
//                    $accountData['currency'] = setting('site_currency', 'global');
//    //                $accountData['invest_password'] = $investPassword;
//    //                $accountData['phone_password'] = $oldForexAccount->PhonePassword;
//                    $accountData['group'] = $data->Group;
//                    $accountData['balance'] = $data->Balance;
//                    $accountData['equity'] = $data->Balance;
//                    $accountData['leverage'] = $data->Leverage;
//                    $accountData['status'] = ForexAccountStatus::Ongoing;
//                    $accountData['server'] = 'MT5';
//                    $accountData['created_by'] = $user->id;
//                    $accountData['first_min_deposit_paid'] = 1;
//                    $accountData['trading_platform'] = config('forextrading.tradingPlatform');
////                    dd($accountData) ;
//                    ForexAccount::create($accountData);
//                $forexCount++;
//                }
//            }
//        echo "Email: ".$user->email." Total accounts: ".$forexCount."\n";


    }
}
