<?php

use App\Enums\FundedSchemeTypes;
use App\Enums\FundedSchemeSubTypes;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\AccountBalanceType;
use App\Models\Account;
use App\Models\ForexAccount;
use App\Models\Gateway;
use App\Models\IbSchema;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\ForexApiTrait;

use App\Helpers\NioHash;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;


if (!function_exists('has_route')) {
    /**
     * check if route exist
     * @param $name
     * @version 1.0.0
     * @since 1.0
     */
    function has_route($name)
    {
        return Route::has($name);
    }
}

if (! function_exists('isActive')) {
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

if (! function_exists('tnotify')) {
    function tnotify($type, $message)
    {
        session()->flash('tnotify', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}

if (! function_exists('setting')) {
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

if (! function_exists('oldSetting')) {

    function oldSetting($field, $section)
    {
        return old($field, setting($field, $section));
    }
}

if (! function_exists('settingValue')) {

    function settingValue($field)
    {
        return \App\Models\Setting::get($field);
    }
}

if (! function_exists('getPageSetting')) {

    function getPageSetting($key)
    {
        return \App\Models\PageSetting::where('key', $key)->first()->value;
    }
}

if (! function_exists('curl_get_file_contents')) {

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

if (! function_exists('getCountries')) {

    function getCountries()
    {
       $countries =  json_decode(file_get_contents(resource_path().'/json/CountryCodes.json'), true);

        $excludedCountries = \App\Models\BlackListCountry::pluck('name')->toArray();

        $filteredCountries = collect($countries)->reject(function ($country) use ($excludedCountries) {
            return in_array($country["name"], $excludedCountries);
        })->values();
        return $filteredCountries;
    }
}

if (! function_exists('getJsonData')) {

    function getJsonData($fileName)
    {
        return file_get_contents(resource_path()."/json/$fileName.json");
    }
}

if (! function_exists('getTimezone')) {
    function getTimezone()
    {
        $timeZones = json_decode(file_get_contents(resource_path().'/json/timeZone.json'), true);

        return array_values(Arr::sort($timeZones, function ($value) {
            return $value['name'];
        }));
    }
}

if (! function_exists('getIpAddress')) {
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

if (! function_exists('getLocation')) {
    function getLocation()
    {
        $clientIp = request()->ip();
        $ip = $clientIp == in_array($clientIp,['127.0.0.1' , '::1']) ? '72.255.51.134' : $clientIp;

        $location = json_decode(curl_get_file_contents('http://ip-api.com/json/'.$ip), true);

        $currentCountry = collect(getCountries())->first(function ($value, $key) use ($location) {
            return $value['code'] == $location['countryCode'];
        });

        $location = [
            'country_code' => $currentCountry['code'],
            'name' => $currentCountry['name'],
            'dial_code' => $currentCountry['dial_code'],
            'ip' => $location['query'] ?? [],
        ];

        return new \Illuminate\Support\Fluent($location);
    }
}

if (! function_exists('carbonInstance')) {
    function carbonInstance($dataTime): Carbon
    {
        return Carbon::create($dataTime->toString());
    }
}

if (! function_exists('gateway_info')) {
    function gateway_info($code)
    {
        $info = Gateway::where('gateway_code', $code)->first();

        return json_decode($info->credentials);
    }
}

if (! function_exists('plugin_active')) {
    function plugin_active($name)
    {
        $plugin = \App\Models\Plugin::where('name', $name)->where('status', true)->first();
        if (! $plugin) {
            $plugin = \App\Models\Plugin::where('type', $name)->where('status', true)->first();
        }

        return $plugin;
    }
}

if (! function_exists('default_plugin')) {
    function default_plugin($type)
    {
        return \App\Models\Plugin::where('type', $type)->where('status', 1)->first('name')?->name;
    }
}

if (! function_exists('br2nl')) {
    function br2nl($input)
    {
        return preg_replace('/<br\\s*?\/??>/i', '', $input);
    }
}

if (! function_exists('safe')) {
    function safe($input)
    {
        if (! env('APP_DEMO', false)) {
            return $input;
        }

        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {

            $emailParts = explode('@', $input);
            $username = $emailParts[0];
            $hiddenUsername = substr($username, 0, 2).str_repeat('*', strlen($username) - 2);
            $hiddenEmailDomain = substr($emailParts[1], 0, 2).str_repeat('*', strlen($emailParts[1]) - 3).$emailParts[1][strlen($emailParts[1]) - 1];

            return $hiddenUsername.'@'.$hiddenEmailDomain;

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
        $amount = (float) ($mainAmount * $bounty) / 100;

        $fromUserReferral = $fromUser == null ? $user : $fromUser;

        $description = ucwords($type).' Referral Bonus Via '.$fromUserReferral->full_name.' - Level '.$depth.' in referral account '.$user->multi_ib_login;

        $transaction = Txn::new($amount, 0, $amount, 'system', $description, TxnType::Referral, TxnStatus::Success, null, null, $referrer->id, $fromUserReferral->id, 'User', [], 'none', $depth, $type, true);

        $referrer->profit_balance += $amount;
        $referrer->save();

        $forexApiTrait = new class {
            use ForexApiTrait;
        };
        $comment = 'referral-bonus' . '/' . substr($transaction->tnx, -7);
        $forexApiTrait->ForexDeposit($user->multi_ib_login, $amount, $comment);
        creditReferralBonus($referrer, $type, $mainAmount, $level, $depth + 1, $user);
    }
}
function creditMultiIbBonus($IBTransaction,$user, $type, $mainAmount, $level = null, $depth = 1, $fromUser = null)
{
    $LevelReferral = \App\Models\LevelReferral::where('type', $type)->where('the_order', $depth)->first('bounty');
//   dd($user->ref_id);
    if (null != $user->ref_id && $depth <= $level && $LevelReferral) {
        $referrer = \App\Models\User::find($user->ref_id);
//        dd($referrer);

        if($referrer->is_multi_ib) {
            $bounty = $LevelReferral->bounty;
            $amount = (float)($mainAmount * $bounty) / 100;
//dd($amount);
            $fromUserReferral = $fromUser == null ? $user : $fromUser;

            $forexApiTrait = new class {
                use ForexApiTrait;
            };
            if(!isset($referrer->multi_ib_login)){
                createMultiIBAccount($referrer);
                $referrer = $referrer->fresh();
            }
            $description = ucwords(str_replace('_', ' ', $type)) . ' Bonus Via ' . $fromUserReferral->full_name . ' - Level ' . $depth . ' in Multi IB account ' . $referrer->multi_ib_login;
            $transaction = Txn::new($amount, 0, $amount, 'system', $description, TxnType::MultiIB, TxnStatus::Success, null, null, $referrer->id, $fromUserReferral->id, 'User', [], 'none', $depth, $type, true);

            $referrer->multi_ib_balance += $amount;
            $referrer->save();

            $comment = 'MIB' . '/'.$IBTransaction->client_no.'/' . $IBTransaction->trade_id;
            $forexApiTrait->ForexDeposit($referrer->multi_ib_login, $amount, $comment);
        }
        creditMultiIbBonus($IBTransaction,$referrer, $type, $mainAmount, $level, $depth + 1, $user);
    }
}
function createMultiIBAccount($user)
{
    $ibSchema = IbSchema::where('type','multi_ib')->where('status',true)->first();
//        dd($ibSchema);
    if(!$ibSchema){
        return false;
    }
    $group = $ibSchema->group;

    $server = config('forextrading.server');
    $password = 'SNNH@2024@bol';
    $investPassword = 'SNNH@2024@bol';
    $name = $user->full_name;
    if(!$name){
        $name = 'abc';
    }
    $phone = $user->phone;
    if(!$phone){
        $phone = 12345678;
    }
    $country = $user->country;
    if(!$country){
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
        'Phone' =>$phone,
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
if (! function_exists('first_min_deposit')) {
    function first_min_deposit($login)
    {
        $forexAccount = ForexAccount::where('login', $login)->where('first_min_deposit_paid', 0)->first();
        if (!$forexAccount) {
            return false;
        }
        $forexAccount->update(['first_min_deposit_paid'=>1]);
    }
}
if (! function_exists('txn_type')) {
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

if (! function_exists('is_custom_rate')) {
    function is_custom_rate($gateway_code)
    {
        if (in_array($gateway_code,['nowpayments','coinremitter','blockchain'])) {
            return 'USD';
        }
        return null;
    }
}

if (! function_exists('site_theme')) {
    function site_theme()
    {
        $theme = new \App\Models\Theme();

        return $theme->active();
    }
}
if (! function_exists('generate_date_range_array')) {
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
if (! function_exists('calPercentage')) {
    function calPercentage($num, $percentage) {
        return $num * ($percentage / 100);
    }
}
if (! function_exists('getQRCode')) {
    function getQRCode($data) {

        return "https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=$data";
    };
}
if (! function_exists('generateDummyPassword')) {
    function generateDummyPassword($length = 12) {
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
                if($pUser->ib_login) {
                    $forexApiTrait->updateAgent($forexAccount->login, $pUser->ib_login);
                }
            }
        }
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

if (!function_exists('AccType')) {
    /**
     * @param $name |string
     * @param $object |boolean
     * @return string|boolean|object
     * @version 1.0.0
     * @since 1.0
     */
    function AccType($name = null, $object = true)
    {
//        dd($name);
        $name = strtoupper($name);
        $acType = get_enums(AccountBalanceType::class, false);
        $acType = ($object === true) ? (object)$acType : $acType;
//        dd($name,$acType);

        if (empty($name)) return $acType;

        return isset($acType->$name) ? $acType->$name : false;
    }
}

if (!function_exists('get_enums')) {
    /**
     * @param $enumClass
     * @param bool $flipArray
     * @return array
     * @throws ReflectionException
     * @version 1.0.0
     * @since 1.0
     */
    function get_enums($enumClass, $flipArray = true)
    {
        try {
            $reflector = new \ReflectionClass($enumClass);
            $enums = $reflector->getConstants();
            return $flipArray ? array_flip($enums) : $enums;
        } catch (\Exception $e) {
            if (env('APP_DEBUG', false)) {
                save_error_log($e, 'enum-refection');
            }
            return [];
        }
    }
}

if (!function_exists('account_balance')) {
    /**
     * @param $account
     * @param $userId | auth->user
     * @version 1.0.0
     * @since 1.0
     */
    function account_balance($account = null, $type = 'base')
    {
        $account = (empty($account)) ? AccountBalanceType::MAIN : $account;
        $userid = auth()->user()->id;
        $account = Account::where('user_id', $userid)->where('balance', $account)->first();
        $amount = (!blank($account)) ? data_get($account, 'amount', 0.00) : 0.00;

        if ($type == 'alter' || $type == 'secondary') {
            return to_amount(base_to_secondary($amount), secondary_currency());
        }

        return to_amount($amount, base_currency());
    }
}

if (!function_exists('user_balance')) {
    /**
     * @param $balance
     * @param $userId | auth->user
     * @version 1.0.0
     * @since 1.0
     */
    function user_balance($balance = null, $userId = null)
    {
        $balance = (empty($balance)) ? AccountBalanceType::MAIN : $balance;
        $userid = !empty($userId) ? $userId : auth()->user()->id;
        $account = Account::where('user_id', $userid)->where('balance', $balance)->first();

        if (!blank($account)) {
            $amount = data_get($account, 'amount', 0.00);
            return ($amount) ? $amount : 0.00;
        }

        return 0;
    }
}

if (!function_exists('the_inv')) {
    /**
     * @param $tnxID
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function the_inv($InvID)
    {
        return config('investorm.inv_prefix') . '-' . $InvID;
    }
}

if (!function_exists('css_state')) {
    /**
     * @param $status
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function css_state($status, $prefix = '', $default = '', $type = 'user')
    {
//        dd($type);
        $statusClass = false;
        $prefix = ($prefix) ? $prefix . '-' : '';
        $default = ($default) ? ' ' . $prefix . 'gray' : '';

        if ($type == 'user') {
            $statusClass = [
                UserStatus::ACTIVE => $prefix . 'success',
                UserStatus::INACTIVE => $prefix . 'warning',
                UserStatus::SUSPEND => $prefix . 'danger',
                UserStatus::LOCKED => $prefix . 'info',
                UserStatus::DELETED => $prefix . 'gray',
                \App\Enums\ForexTradingStatus::ARCHIVE => $prefix . 'gray',

                ComplianceStatus::UNPROCESSED => $prefix . 'gray',
                ComplianceStatus::PENDING => $prefix . 'warning',
                ComplianceStatus::APPROVED => $prefix . 'success',
                ComplianceStatus::REJECTED => $prefix . 'danger',

                UserState::LEAD => $prefix . 'info',
                UserState::CLIENT => $prefix . 'success',
                UserState::RETIRING => $prefix . 'warning',
                UserState::RETIRED => $prefix . 'danger',


                1 => $prefix . 'success',
                0 => $prefix . 'danger',
            ];
        } elseif ($type == 'tnx') {
            $statusClass = [
                TransactionStatus::PENDING => $prefix . 'warning',
                TransactionStatus::ONHOLD => $prefix . 'info',
                TransactionStatus::CONFIRMED => $prefix . 'info',
                TransactionStatus::CANCELLED => $prefix . 'danger',
                TransactionStatus::FAILED => $prefix . 'warning',
                TransactionStatus::COMPLETED => $prefix . 'success',
            ];
        } elseif ($type == 'static') {
            $statusClass = [
                'pending' => $prefix . 'warning',
                'active' => $prefix . 'primary',
                'inactive' => $prefix . 'gray',
                'completed' => $prefix . 'success',
                'cancelled' => $prefix . 'danger',
                'violated' => $prefix . 'danger',
            ];
        }

        return ($statusClass[$status]) ? ' ' . $statusClass[$status] : $default;
    }
}

if (!function_exists('the_state') && function_exists('css_state')) {
    /**
     * @param $status
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function the_state($status, $attr = [])
    {
        $prams = ['prefix' => '', 'default' => '', 'type' => 'static'];
        $prams = array_merge($prams, $attr);
        extract($prams);

        return css_state($status, $prefix, $default, $type);
    }
}

if (!function_exists('fst2n')) {
    /**
     * @param $account
     * @version 1.0.0
     * @since 1.0
     */

    function fst2n($account = null)
    {
        $account = (empty($account)) ? FundedSchemeTypes::DIRECT_FUNDING : $account;
        $nameMap = [
            FundedSchemeTypes::DIRECT_FUNDING => __(sys_settings('direct_funding', 'Direct Funding')),
            FundedSchemeTypes::CHALLENGE_FUNDING => __(sys_settings('challenge_funding', 'Challenge Funding')),
            FundedSchemeTypes::DEMO_FUNDING => __(sys_settings('demo_funding', 'Demo Funding')),
            FundedSchemeTypes::MANUAL_FUNDING => __(sys_settings('manual_funding', 'Manual Funding')),
            ];

        return Arr::get($nameMap, $account);
    }
}

if (!function_exists('fsst2n')) {
    /**
     * @param $account
     * @version 1.0.0
     * @since 1.0
     */

    function fsst2n($account = null)
    {
//        dd($account);
        $account = (empty($account)) ? FundedSchemeSubTypes::TWO_STEP_CHALLENGE : $account;
        $nameMap = [
            FundedSchemeSubTypes::TWO_STEP_CHALLENGE => __(sys_settings('direct_funding', '2 Step Challenge')),
            FundedSchemeSubTypes::SINGLE_STEP_CHALLENGE => __(sys_settings('direct_funding', '1 Step Challenge')),
            FundedSchemeSubTypes::LEVERAGE_1 => __(sys_settings('direct_funding', __('Leverage 1::leverage',['leverage'=>FundedSchemeSubTypes::LEVERAGE_1]))),
            FundedSchemeSubTypes::LEVERAGE_2 => __(sys_settings('direct_funding',  __('Leverage 1::leverage',['leverage'=>FundedSchemeSubTypes::LEVERAGE_2]))),
            FundedSchemeSubTypes::LEVERAGE_3 => __(sys_settings('direct_funding',  __('Leverage 1::leverage',['leverage'=>FundedSchemeSubTypes::LEVERAGE_3]))),

            ];

        return Arr::get($nameMap, $account);
    }
}

if (!function_exists('to_minus')) {
    /**
     * @param $amount1
     * @param $amount2
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function to_minus($amount1, $amount2)
    {
        $total = BigDecimal::of($amount1)->minus(BigDecimal::of($amount2));
        return is_object($total) ? (string)$total : $total;
    }
}

if (!function_exists('get_decimal')) {
    /**
     * @param $method
     * @param $what
     * @return integer
     * @version 1.0.0
     * @since 1.0
     */
    function get_decimal($method = 'calc', $what = true)
    {
        $for = ($what == 'crypto') ? $what : 'fiat';
        $type = ($method == 'display') ? $method : 'calc';
        $fback = ($what == 'crypto') ? 6 : 2;
//        dd('decimal_fiat_calc')

        return sys_settings('decimal_' . $for . '_' . $type, $fback);
    }
}

if (!function_exists('dp_calc') && function_exists('get_decimal')) {
    /**
     * @param $for
     * @return integer
     * @version 1.0.0
     * @since 1.0
     */
    function dp_calc($for = 'fiat')
    {
        return get_decimal('calc', $for);
    }
}

if (!function_exists('percentage_of_total_calc')) {
    /**
     * @param @what
     * @since 1.0
     * @version 1.0.0
     */
    function percentage_of_total_calc($amount, $total)
    {
        $base_currency = base_currency();
        $scale = (is_crypto($base_currency)) ? dp_calc('crypto') : dp_calc('fiat');
        $percentage = (BigDecimal::of($total)->compareTo(0) == 1) ? BigDecimal::of($amount)->multipliedBy(BigDecimal::of(100))->dividedBy($total, $scale, RoundingMode::CEILING) : 0;

        return is_object($percentage) ? (string)$percentage : $percentage;

    }

}

if (!function_exists('base_currency')) {
    /**
     * @version 1.0.0
     * @since 1.0
     */
    function base_currency()
    {
        return sys_settings('base_currency', 'USD');
    }
}

if (!function_exists('amount_format')) {
    /**
     * @param $amount , $attr
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function amount_format($amount, $attr = [])
    {
        $output = empty($amount) ? 0.0 : $amount;
        $default = [
            'point' => '.',
            'thousand' => '',
            'decimal' => 4,
            'trim' => true,
            'zero' => true
        ];
        $default = array_merge($default, $attr);
        extract($default);

        $decimal = (int)$decimal;
        $decimal = ($decimal > 0) ? $decimal : 0;
        $type = is_crypto(base_currency()) ? 'crypto' : 'fiat';
        $zeroAdd = (dp_display($type) < 2) ? '.0' : '.00';
        $zeroLen = strlen($zeroAdd);

        $output = number_format($amount, $decimal, $point, $thousand);
        $output = ($trim === true) ? rtrim($output, '0') : $output;
        $output = (substr($output, -1)) === '.' ? str_replace('.', (($trim === true) ? $zeroAdd : '0'), $output) : $output;
        $output = ($zero === false && (substr($output, -$zeroLen) === $zeroAdd)) ? str_replace($zeroAdd, '', $output) : $output;
        return $output;
    }
}

if (!function_exists('get_decimal')) {
    /**
     * @param $method
     * @param $what
     * @return integer
     * @version 1.0.0
     * @since 1.0
     */
    function get_decimal($method = 'calc', $what = true)
    {
        $for = ($what == 'crypto') ? $what : 'fiat';
        $type = ($method == 'display') ? $method : 'calc';
        $fback = ($what == 'crypto') ? 6 : 2;
//        dd('decimal_fiat_calc')

        return sys_settings('decimal_' . $for . '_' . $type, $fback);
    }
}

if (!function_exists('dp_display') && function_exists('get_decimal')) {
    /**
     * @param $for
     * @return integer
     * @version 1.0.0
     * @since 1.0
     */
    function dp_display($for = 'fiat')
    {
        return get_decimal('display', $for);
    }
}

if (!function_exists('get_currencies')) {
    /**
     * @param $output
     * @return array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_currencies($output = true, $only = '')
    {
        $all = collect(config('currencies'));
        $currencies = (in_array($only, ['fiat', 'crypto'])) ? $all->where('type', $only) : $all;

        if ($output === true || $output === 'key') {
            return $currencies->keys()->toArray();
        } elseif ($output === 'list') {
            $list = [];
            foreach ($currencies as $currency) {
                $list[$currency['code']] = $currency['name'];
            }
            return $list;
        }
        return $currencies->toArray();
    }
}

if (!function_exists('get_currency')) {
    /**
     * @param $name
     * @return string|array|mixed
     * @version 1.0.0
     * @since 1.0
     */
    function get_currency($code, $key = '', $object = false)
    {
        $code = strtoupper($code);
        $currencies = get_currencies('full');

        if (isset($currencies[$code])) {
            $get_code = $currencies[$code];

            if (isset($get_code[$key])) return $get_code[$key];

            return ($object === true) ? json_decode(json_encode($get_code)) : $get_code;
        }

        return false;
    }
}

if (!function_exists('is_crypto')) {
    /**
     * @param $code
     * @return boolean
     * @version 1.0.0
     * @since 1.0
     */
    function is_crypto($code)
    {

        return (get_currency($code, 'type') === 'fiat' || get_currency($code, 'type') === true) ? false : true;
    }
}

if (!function_exists('to_amount')) {
    /**
     * @param $num , $currency, $round
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function to_amount($num, $currency, $attr = [])
    {
        $round = isset($attr['round']) ? $attr['round'] : 'display';
        $zero = isset($attr['zero']) ? $attr['zero'] : true;
        $thousand = isset($attr['thousand']) ? $attr['thousand'] : ',';
        $trim = isset($attr['trim']) ? $attr['trim'] : true;
        $dp_opt = isset($attr['dp']) ? $attr['dp'] : 'display';

        $type = is_crypto($currency) ? 'crypto' : 'fiat';
        $amount = is_object($num) ? (string)$num : $num;

        if (in_array($round, ['up', 'down', 'zero'])) {
            $amount = ($round === 'up') ? ceil($amount) : (($round === 'down') ? floor($amount) : $amount);
            $rounded = 0;
        } else {
            $rounded = ($dp_opt == 'display') ? dp_display($type) : dp_calc($type);
        }
        $return = amount_format($amount, ['decimal' => $rounded, 'thousand' => $thousand, 'zero' => $zero, 'trim' => $trim]);
        return ($return) ? $return : 0;
    }
}

if (!function_exists('amount')) {
    /**
     * @param $num , $currency, $attr|array
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function amount($num, $currency, $attr = [])
    {
        $default = ['zero' => false];
        $param = array_merge($default, $attr);

        return to_amount($num, $currency, $param);
    }
}

if (!function_exists('amount_z')) {
    /**
     * @param $num , $currency, $attr|array
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function amount_z($num, $currency, $attr = [])
    {
        $default = ['zero' => true];
        $param = array_merge($default, $attr);

        return amount($num, $currency, $param);
    }
}

if (!function_exists('money')) {
    /**
     * @param $num , $currency, $attr|array
     * @return string
     * @version 1.0.0
     * @since 1.0
     */
    function money($num, $currency, $attr = [])
    {
        $default = ['zero' => true];
        $param = array_merge($default, $attr);
        return to_amount($num, $currency, $param) . ' ' . strtoupper($currency);
    }
}

if (!function_exists('show_date')) {
    /**
     * @param $date
     * @param false $withTime
     * @return string|void
     * @version 1.0.0
     * @since 1.0
     */
    function show_date($date, $withTime = false, $zone = true)
    {
        if (empty($date)) {
            return;
        }
//dd($date);
        if (!($date instanceof Carbon)) {
            if (1 === preg_match('~^[1-9][0-9]*$~', $date)) {
                $date = Carbon::createFromTimestamp($date);
            } else {
                $date = Carbon::parse($date);
            }
        }

        $format = sys_settings('date_format');
//dd($format);
        if ($withTime) {
            $format .= ' ' . sys_settings('time_format');
        }

        if ($zone == true) {
            $timezone = sys_settings('time_zone');
            return $date->timezone($timezone)->format($format);
        }

        return $date->format($format);
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

if (!function_exists('is_data')) {
    /**
     * @param $array
     * @param $array
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function the_data($array = null, $key = null, $default = null)
    {
        $data = data_get($array, $key, $default);

        return ($data) ? $data : $default;
    }
}

if (!function_exists('is_json')) {
    /**
     * check json value
     * @param $string , $decoded
     * @version 1.0.0
     * @since 1.0
     */
    function is_json($string, $decoded = false)
    {
        if (is_array($string)) return false;
        json_decode($string);
        $check = (json_last_error() == JSON_ERROR_NONE);

        if ($decoded && $check) {
            return json_decode($string);
        }

        return $check;
    }
}

if (!function_exists('sys_settings')) {
    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function sys_settings($key, $default = null)
    {
//        dd($key,$default);
        $settings = Cache::remember('sys_settings', 1800, function () {
            return \App\Models\Setting::all()->pluck('value', 'key');
        });
//dd($settings);
        $value = $settings->get($key) ?? $default;


        return is_json($value) ? json_decode($value, true) : $value;
    }
}


if (!function_exists("gss") && function_exists("sys_settings")) {
    /**
     * @param $key
     * @param null $default
     * @return mixed
     * @version 1.0.0
     * @since 1.0
     */
    function gss($key, $default = null)
    {
        return sys_settings($key, $default);
    }
}

if (!function_exists('get_page')) {
    /**
     * @param $key
     * @param $where
     * @since 1.0
     * @version 1.0.0
     */
    function get_page($key, $where = 'id')
    {
        if (empty($key)) return false;

        $page = Page::where($where, $key)->first();

        if (!blank($page)) {
            return $page;
        }

        return false;
    }
}

if (!function_exists('the_page')) {
    /**
     * @param $name
     * @since 1.0
     * @version 1.0.0
     */
    function the_page($name = '')
    {
        $pageMap = [
            'terms' => get_page(gss('page_terms')),
            'policy' => get_page(gss('page_privacy')),
            'contact' => get_page(gss('page_contact')),
            'support' => get_page(gss('page_contact')),
        ];

        if (in_array($name, array_keys($pageMap))) {
            return $pageMap[$name];
        } else {
            return get_page($name, 'slug');
        }

        return false;
    }
}

if (!function_exists('get_page_link')) {
    /**
     * @param $id
     * @since 1.0
     * @version 1.0.0
     */
    function get_page_link($name = '', $text = '', $target = false)
    {
        $page = the_page($name);

        if (!empty($page)) {
            $new_target = ($target == true || !empty($menu->menu_link)) ? ' target="_blank"' : '';
            $ba = '<a href="' . $page->link . '"' . $new_target . '>';
            $aa = '</a>';
            $tx = ($text) ? __($text) : (($page->menu_name) ? $page->menu_name : $page->name);

            return $ba . $tx . $aa;
        }

        return ($text) ? __($text) : '';
    }
}

if (!function_exists('get_mail_link')) {
    /**
     * @since 1.0
     * @version 1.0.0
     */
    function get_mail_link($text = null)
    {
        $mail = sys_settings('site_email');
//        $mail = sys_settings('user_ref_id_prefix');
//        {{ __('e.g :site_code######',['site_code'=>sys_settings('user_ref_id_prefix')]) }}
//        ,['site_name'=>sys_settings('site_name')])

        if (!empty($mail)) {
            $ba = '<a href="mailto:' . $mail . '">';
            $aa = '</a>';
            $tx = ($text) ? __($text) : $mail;

            return $ba . $tx . $aa;
        }

        return ($text) ? __($text) : '';

    }
}
