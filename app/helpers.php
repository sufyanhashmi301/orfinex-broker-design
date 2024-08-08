<?php

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Helpers\NioHash;
use App\Models\ForexAccount;
use App\Models\Gateway;
use App\Models\IbSchema;
use App\Models\User;
use App\Models\RiskProfileTag;
use Carbon\Carbon;
use App\Traits\ForexApiTrait;

if (!function_exists('isActive')) {
    function isActive($route, $parameter = null)
    {

        if (null != $parameter && request()->url() === route($route, $parameter)) {
            return 'active';
        }
        if (null == $parameter && is_array($route)) {
            foreach ($route as $value) {
                if (Request::routeIs($value)) {
                    return 'show';
                }
            }
        }
        if (null == $parameter && Request::routeIs($route)) {
            return 'active';
        }

    }
}

if (!function_exists('tnotify')) {
    function tnotify($type, $message)
    {
        session()->flash('tnotify', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}

if (!function_exists('setting')) {
    function setting($key, $section = null, $default = null)
    {

        if (is_null($key)) {
            return new \App\Models\Setting();
        }
        if (is_array($key)) {

            return \App\Models\Setting::set($key[0], $key[1]);
        }

        $value = \App\Models\Setting::get($key, $section, $default);

        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('oldSetting')) {

    function oldSetting($field, $section)
    {
        return old($field, setting($field, $section));
    }
}

if (!function_exists('settingValue')) {

    function settingValue($field)
    {
        return \App\Models\Setting::get($field);
    }
}

if (!function_exists('getPageSetting')) {

    function getPageSetting($key)
    {
        return \App\Models\PageSetting::where('key', $key)->first()->value;
    }
}

if (!function_exists('curl_get_file_contents')) {

    function curl_get_file_contents($URL)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) {
            return $contents;
        }

        return false;

    }
}

if (!function_exists('getCountries')) {

    function getCountries()
    {
        $countries = json_decode(file_get_contents(resource_path() . '/json/CountryCodes.json'), true);

        $excludedCountries = \App\Models\BlackListCountry::pluck('name')->toArray();

        $filteredCountries = collect($countries)->reject(function ($country) use ($excludedCountries) {
            return in_array($country["name"], $excludedCountries);
        })->values();
        return $filteredCountries;
    }
}
if (!function_exists('getCountryCode')) {

    function getCountryCode($countryName)
    {
        // Path to the JSON file
        $filePath = resource_path('json/CountryCodes.json');

        // Read the file contents
        if (!file_exists($filePath)) {
            return null;
        }

        $jsonContents = file_get_contents($filePath);
        $countries = json_decode($jsonContents, true);

        // Iterate through the array and find the country code
        foreach ($countries as $country) {
            if (strcasecmp($country['name'], $countryName) == 0) {
                return $country['code'];
            }
        }

        return null; // Return null if the country is not found
    }
}

if (!function_exists('getJsonData')) {

    function getJsonData($fileName)
    {
        return file_get_contents(resource_path() . "/json/$fileName.json");
    }
}

if (!function_exists('getTimezone')) {
    function getTimezone()
    {
        $timeZones = json_decode(file_get_contents(resource_path() . '/json/timeZone.json'), true);

        return array_values(Arr::sort($timeZones, function ($value) {
            return $value['name'];
        }));
    }
}

if (!function_exists('getIpAddress')) {
    function getIpAddress()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }
}

if (!function_exists('getLocation')) {
    function getLocation()
    {
        $clientIp = request()->ip();
        $ip = $clientIp == in_array($clientIp, ['127.0.0.1', '::1']) ? '72.255.51.134' : $clientIp;
//        $ip = '72.255.51.134';
        $location = json_decode(curl_get_file_contents('http://ip-api.com/json/' . $ip), true);

        $currentCountry = collect(getCountries())->first(function ($value, $key) use ($location) {
            return $value['code'] == $location['countryCode'];
        });
//dd($location,$currentCountry);
        $location = [
            'country_code' => $currentCountry['code'] ?? '00',
            'name' => $currentCountry['name'] ?? 'Not found',
            'dial_code' => $currentCountry['dial_code'] ?? 'zzzz',
            'ip' => $location['query'] ?? [],
        ];
//dd( new \Illuminate\Support\Fluent($location));
        return new \Illuminate\Support\Fluent($location);
    }
}

if (!function_exists('carbonInstance')) {
    function carbonInstance($dataTime): Carbon
    {
        return Carbon::create($dataTime->toString());
    }
}

if (!function_exists('gateway_info')) {
    function gateway_info($code)
    {
        $info = Gateway::where('gateway_code', $code)->first();

        return json_decode($info->credentials);
    }
}

if (!function_exists('plugin_active')) {
    function plugin_active($name)
    {
        $plugin = \App\Models\Plugin::where('name', $name)->where('status', true)->first();
        if (!$plugin) {
            $plugin = \App\Models\Plugin::where('type', $name)->where('status', true)->first();
        }

        return $plugin;
    }
}

if (!function_exists('default_plugin')) {
    function default_plugin($type)
    {
        return \App\Models\Plugin::where('type', $type)->where('status', 1)->first('name')?->name;
    }
}

if (!function_exists('br2nl')) {
    function br2nl($input)
    {
        return preg_replace('/<br\\s*?\/??>/i', '', $input);
    }
}

if (!function_exists('safe')) {
    function safe($input)
    {
        if (!env('APP_DEMO', false)) {
            return $input;
        }

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {

            $emailParts = explode('@', $input);
            $username = $emailParts[0];
            $hiddenUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);
            $hiddenEmailDomain = substr($emailParts[1], 0, 2) . str_repeat('*', strlen($emailParts[1]) - 3) . $emailParts[1][strlen($emailParts[1]) - 1];

            return $hiddenUsername . '@' . $hiddenEmailDomain;

        }

        return preg_replace('/(\d{3})\d{3}(\d{3})/', '$1****$2', $input);

    }
}

function creditReferralBonus($user, $type, $mainAmount, $level = null, $depth = 1, $fromUser = null)
{
    $LevelReferral = \App\Models\LevelReferral::where('type', $type)->where('the_order', $depth)->first('bounty');
    if (null != $user->ref_id && $depth <= $level && $LevelReferral) {
        $referrer = \App\Models\User::find($user->ref_id);

        $bounty = $LevelReferral->bounty;
        $amount = (float)($mainAmount * $bounty) / 100;

        $fromUserReferral = $fromUser == null ? $user : $fromUser;

        $description = ucwords($type) . ' Referral Bonus Via ' . $fromUserReferral->full_name . ' - Level ' . $depth . ' in referral account ' . $user->multi_ib_login;

        $transaction = Txn::new($amount, 0, $amount, 'system', $description, TxnType::Referral, TxnStatus::Success, null, null, $referrer->id, $fromUserReferral->id, 'User', [], 'none', $depth, $type, true);

        $referrer->profit_balance += $amount;
        $referrer->save();

        $forexApiService = new ForexApiService();
        $comment = 'referral-bonus' . '/' . substr($transaction->tnx, -7);
        $data = [
            'login' => $user->multi_ib_login,
            'Amount' => $amount,
            'type' => 1,//deposit
            'TransactionComments' => $comment
        ];
        $forexApiService->balanceOperation($data);
//        $forexApiTrait->ForexDeposit($user->multi_ib_login, $amount, $comment);
        creditReferralBonus($referrer, $type, $mainAmount, $level, $depth + 1, $user);
    }
}

function creditMultiIbBonus($IBTransaction, $user, $type, $mainAmount, $level = null, $depth = 1, $fromUser = null)
{
    $LevelReferral = \App\Models\LevelReferral::where('type', $type)->where('the_order', $depth)->first('bounty');
//   dd($user->ref_id);
    if (null != $user->ref_id && $depth <= $level && $LevelReferral) {
        $referrer = \App\Models\User::find($user->ref_id);
//        dd($referrer);

        if ($referrer->is_multi_ib) {
            $bounty = $LevelReferral->bounty;
            $amount = (float)($mainAmount * $bounty) / 100;
//dd($amount);
            $fromUserReferral = $fromUser == null ? $user : $fromUser;

            $forexApiTrait = new class {
                use ForexApiTrait;
            };
            if (!isset($referrer->multi_ib_login)) {
                createMultiIBAccount($referrer);
                $referrer = $referrer->fresh();
            }
            $description = ucwords(str_replace('_', ' ', $type)) . ' Bonus Via ' . $fromUserReferral->full_name . ' - Level ' . $depth . ' in Multi IB account ' . $referrer->multi_ib_login;
            $transaction = Txn::new($amount, 0, $amount, 'system', $description, TxnType::MultiIB, TxnStatus::Success, null, null, $referrer->id, $fromUserReferral->id, 'User', [], 'none', $depth, $type, true);

            $referrer->multi_ib_balance += $amount;
            $referrer->save();
            $forexApiService = new ForexApiService();
            $comment = 'MIB' . '/' . $IBTransaction->client_no . '/' . $IBTransaction->trade_id;
            $data = [
                'login' => $user->multi_ib_login,
                'Amount' => $amount,
                'type' => 1,//deposit
                'TransactionComments' => $comment
            ];
            $forexApiService->balanceOperation($data);
//            $forexApiTrait->ForexDeposit($referrer->multi_ib_login, $amount, $comment);
        }
        creditMultiIbBonus($IBTransaction, $referrer, $type, $mainAmount, $level, $depth + 1, $user);
    }
}

function createMultiIBAccount($user)
{
    $ibSchema = IbSchema::where('type', 'multi_ib')->where('status', true)->first();
//        dd($ibSchema);
    if (!$ibSchema) {
        return false;
    }
    $group = $ibSchema->group;

    $server = config('forextrading.server');
    $password = 'SNNH@2024@bol';
    $investPassword = 'SNNH@2024@bol';
    $name = $user->full_name;
    if (!$name) {
        $name = 'abc';
    }
    $phone = $user->phone;
    if (!$phone) {
        $phone = 12345678;
    }
    $country = $user->country;
    if (!$country) {
        $country = 'UAE';
    }
    $dataArray = array(
        'Name' => $name,
        'Leverage' => 1,
        'Group' => $group,
        'MasterPassword' => $password,
        'InvestorPassword' => $investPassword,
//            'PhonePassword' => $password,
        'Email' => $user->email,
        'Phone' => $phone,
        'Country' => $country,
    );
    $dataArray['Login'] = 0;
    $dataArray['Language'] = 0;
    $dataArray['Rights'] = 'USER_RIGHT_ALL';
    $dataArray['Status'] = 'YES';
    $URL = config('forextrading.createUserUrl');
//        dd($dataArray,$URL);
    $forexApiTrait = new class {
        use ForexApiTrait;
    };
    $response = $forexApiTrait->sendApiPostRequest($URL, $dataArray);
//        dd($response->object());
//        if ($response->serverError() || $response->failed()) {
//            return redirect()->back()->withErrors(['msg' => 'Some error occurred! please try again']);
//        }

    if ($response->status() == 200 && $response->successful() && $response->object()->ResponseCode == 0) {
        $resData = $response->object();
        $user->multi_ib_login = $resData->Login;
        $user->save();
        return $resData->Login;
    }
    return false;
    //        return redirect()->back()->withErrors(['msg' => 'Update your phone and country in profile']);
}

if (!function_exists('first_min_deposit')) {
    function first_min_deposit($login)
    {
        $forexAccount = ForexAccount::where('login', $login)->where('first_min_deposit_paid', 0)->first();
        if (!$forexAccount) {
            return false;
        }
        $forexAccount->update(['first_min_deposit_paid' => 1]);
    }
}
if (!function_exists('txn_type')) {
    function txn_type($type, $value = [])
    {
        $result = [];
        switch ($type) {
            case TxnType::Interest->value:
            case TxnType::ReceiveMoney->value:
            case TxnType::Deposit->value:
            case TxnType::ManualDeposit->value:
            case TxnType::Bonus->value:
            case TxnType::Refund->value:
            case TxnType::Exchange->value:
            case TxnType::Referral->value:
                $result = ['green-color', '+'];
                break;
            case TxnType::SendMoney->value:
            case TxnType::Investment->value:
            case TxnType::Withdraw->value:
            case TxnType::Subtract->value:
                $result = ['red-color', '-'];
                break;
        }
        $commonResult = array_intersect($value, $result);

        return current($commonResult);
    }
}

if (!function_exists('is_custom_rate')) {
    function is_custom_rate($gateway_code)
    {
        if (in_array($gateway_code, ['nowpayments', 'coinremitter', 'blockchain'])) {
            return 'USD';
        }
        return null;
    }
}

if (!function_exists('site_theme')) {
    function site_theme()
    {
        $theme = new \App\Models\Theme();

        return $theme->active();
    }
}
if (!function_exists('generate_date_range_array')) {
    function generate_date_range_array($startDate, $endDate): array
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $dates = collect([]);

        while ($startDate->lte($endDate)) {
            $dates->push($startDate->format('d M'));
            $startDate->addDay();
        }

        return $dates->toArray();
    }
}
if (!function_exists('calPercentage')) {
    function calPercentage($num, $percentage)
    {
        return $num * ($percentage / 100);
    }
}
if (!function_exists('getQRCode')) {
    function getQRCode($data)
    {

        return "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=$data";
    }

    ;
}
if (!function_exists('generateDummyPassword')) {
    function generateDummyPassword($length = 12)
    {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $special = '!@#$%^&*()-_?';
        $digits = '0123456789';

        $password = '';

        // Ensure at least one lowercase letter
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];

        // Ensure at least one uppercase letter
        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];

        // Ensure at least one special character
        $password .= $special[rand(0, strlen($special) - 1)];

        // Ensure at least one digit
        $password .= $digits[rand(0, strlen($digits) - 1)];

        // Fill the rest of the password with random characters
        for ($i = 4; $i < $length; $i++) {
            $allCharacters = $lowercase . $uppercase . $special . $digits;
            $password .= $allCharacters[rand(0, strlen($allCharacters) - 1)];
        }

        // Shuffle the password to ensure randomness
        $password = str_shuffle($password);

        return $password;
    }
}

if (!function_exists('user_meta')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function user_meta($metaKey, $default = null, $user = null)
    {
        $user = (blank($user)) ? auth()->user() : $user;
//dd($user);
        if (!blank($user)) {
            $userMetas = $user->user_metas->pluck('meta_value', 'meta_key');
            if (!blank($userMetas)) {
                return data_get($userMetas, $metaKey, $default);
            }
        }
        return ($default) ? $default : false;
    }
}
if (!function_exists('add_child_agent')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function add_child_agent($pUser)
    {
        $users = User::where('ref_id', $pUser->id)->get();
        foreach ($users as $user) {
            $forexAccounts = ForexAccount::where('user_id', $user->id)
                ->where('account_type', 'real')
                ->get();
//        dd($forexAccounts,$this->user);
            $forexApiTrait = new class {
                use ForexApiTrait;
            };
            foreach ($forexAccounts as $forexAccount) {
                if ($pUser->ib_login) {
                    $forexApiTrait->updateAgent($forexAccount->login, $pUser->ib_login);
                }
            }
        }
    }
}



if (!function_exists('sync_forex_accounts')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function sync_forex_accounts($pUser)
    {
        if (!isset($userID))
            $userID = auth()->user()->id;

        $realAccounts = ForexAccount::where('user_id', $userID)
            ->where('status', ForexAccountStatus::Ongoing)
//            ->where('login', 1003462)
            ->get();

        $balance = 0;
        $forexApiService = app(ForexApiService::class);
        foreach ($realAccounts as $account) {
//            dd($account);
            $data = [
                'login' => $account->login
            ];
            $getUserResponse = $forexApiService->getBalance($data);
//            dd($getUserResponse);
//           dd($getUserResponse->object(),$getUserResponse->object()->Login);
//            if (!empty($getUserResponse)) {
//                dd($getUserResponse->object(),$getUserResponse->object()->Login);
                if ($getUserResponse['success']) {

                    update_user_account_by_api_response($getUserResponse['result']);
//                    if ($account->account_type == 'real') {
//                        $balance += $getUserResponse->object()->Balance;
//                    }
                }
            }
//        }
//        dd($balance);
//        update_total_balance($userID, $balance);
    }
}

if (!function_exists('update_user_account_by_api_response')) {
    function update_user_account_by_api_response($resData, $lastDeposit = false)
    {
//        dd($resData);
//        $resData = $getUserResponse->object();
//        dd($resData);
        if (isset($resData['login'])) {
            $forexTrading = ForexAccount::where('login', $resData['login'])->first();
//        $forexTrading->account_name = $resData->Name;
            if ($forexTrading) {
//                $forexTrading->leverage = $resData['leverage'];
//      $forexTrading->email = $resData->Email;
                $forexTrading->balance = $resData['balance'];
                $forexTrading->equity = $resData['equity'];
                $forexTrading->credit = $resData['credit'];
//                $forexTrading->agent = $resData->Agent;
//            $forexTrading->free_margin = $resData->MarginFree;
//            $forexTrading->margin = $resData->Margin;
//                $forexTrading->group = $resData->Group;

                $forexTrading->save();
            }
        }
    }
}
if (!function_exists('update_total_balance')) {
    function update_total_balance($userID, $balance)
    {
        $user = User::where('id', $userID)->first();
//        $forexTrading->account_name = $resData->Name;
        $user->balance = $balance;
        $user->save();
    }
}

    if (!function_exists('remove_child_agent')) {

    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function remove_child_agent($user)
    {
        $forexAccounts = ForexAccount::where('user_id', $user->id)
            ->where('account_type', 'real')
            ->get();
//        dd($forexAccounts,$this->user);
        $forexApiTrait = new class {
            use ForexApiTrait;
        };
        foreach ($forexAccounts as $forexAccount) {
            $forexApiTrait->updateAgent($forexAccount->login, 0);
        }

    }
}
if (!function_exists('get_mt5_account')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account($login)
    {
        return DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->where('Login', $login)
            ->first();

    }
}
if (!function_exists('get_mt5_account_balance')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account_balance($login)
    {
        $balance = 0.0;
        $mt5Account = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->where('Login', $login)
            ->first();
        if ($mt5Account) {
            $balance = $mt5Account->Balance;
        }
        return $balance;

    }
}
if (!function_exists('get_mt5_account_equity')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account_equity($login)
    {
        $equity = 0.0;
        $mt5Account = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->where('Login', $login)
            ->first();
        if ($mt5Account) {
            $equity = $mt5Account->Equity;
        }
        return $equity;

    }
}
if (!function_exists('mt5_total_balance')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_total_balance($user_id)
    {
        $forexAccounts = ForexAccount::where('user_id', $user_id)->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)->pluck('login');

        $totalBalance = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->whereIn('Login', $forexAccounts)
            ->sum('Balance');

        return $totalBalance;

    }
}
if (!function_exists('mt5_total_equity')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_total_equity($user_id)
    {
        $forexAccounts = ForexAccount::where('user_id', $user_id)->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)->pluck('login');

        $totalEquity = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->whereIn('Login', $forexAccounts)
            ->sum('Equity');

        return $totalEquity;

    }
}
if (!function_exists('mt5_total_credit')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_total_credit($user_id)
    {
        $forexAccounts = ForexAccount::where('user_id', $user_id)->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)->pluck('login');

        $totalEquity = DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->whereIn('Login', $forexAccounts)
            ->sum('Credit');

        return $totalEquity;

    }
}
if (!function_exists('mt5_update_balance')) {
    /**
     * @param $metaKey
     * @param null $default
     * @param null $user
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_update_balance($login, $balance)
    {
//        $forexAccounts = ForexAccount::where('user_id', $user_id)->where('account_type', 'real')
//            ->where('status', ForexAccountStatus::Ongoing)->pluck('login');
        DB::connection('mt5_db')
            ->table('mt5_accounts')
            ->where('Login', $login)
            ->update(['Balance' => $balance, 'Equity' => $balance]);

    }
}
if (!function_exists('the_hash')) {
    /**
     * @param $data
     * @param $match
     * @return string|boolean
     * @version 1.0.0
     * @since 1.0
     */
    function the_hash($data, $match = null)
    {
        return NioHash::of($data, $match);
    }
}

if (!function_exists('get_hash')) {
    /**
     * @param $data
     * @return string|boolean
     * @version 1.0.0
     * @since 1.0
     */
    function get_hash($data)
    {
        return NioHash::toID($data);
    }
}

if (!function_exists('getRiskProfileTag')) {
    function getRiskProfileTag() {
        return RiskProfileTag::where('status', 1)->get();
    }
}

if (!function_exists('hexToRgb')) {
    function hexToRgb($hexColor)
    {
        // Remove the '#' character if it's there
        $hexColor = ltrim($hexColor, '#');

        // Expand shorthand hex colors (e.g., #FFF to #FFFFFF)
        if (strlen($hexColor) === 3) {
            $hexColor = $hexColor[0] . $hexColor[0] . $hexColor[1] . $hexColor[1] . $hexColor[2] . $hexColor[2];
        }

        // Convert the hex color to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        return [$r, $g, $b];
    }
}

if (!function_exists('getColorFromSettings')) {
    function getColorFromSettings($key, $default = '000000')
    {
        // Assuming you have a function `setting()` that retrieves settings from the database
        $hexColor = setting($key, 'global', $default);
        return hexToRgb($hexColor);
    }
}

