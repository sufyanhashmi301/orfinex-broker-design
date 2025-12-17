<?php

use App\Enums\AccountBalanceType;
use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Helpers\NioHash;
use App\Models\Account;
use App\Models\ForexAccount;
use App\Models\Gateway;
use App\Models\IbSchema;
use App\Models\KycLevel;
use App\Models\Setting;
use App\Models\User;
use App\Models\RiskProfileTag;
use App\Services\ForexApiService;
use Carbon\Carbon;
use App\Traits\ForexApiTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

if (!function_exists('is_force_https')) {
    /**
     * Check if force to https form configure.
     * @version 1.0.0
     * @since 1.0
     */
    function is_force_https()
    {
        if (config('app.force_https')) {
            return true;
        }

        return false;
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

if (!function_exists('getFilteredTxnTypes')) {
    /**
     * Get filtered transaction types excluding specific ones
     *
     * @return array
     */
    function getFilteredTxnTypes(): array
    {
        // Define excluded transaction types
        $excludedTypes = [
            'multi_ib',
            'ib',
            'interest',
            'exchange'
        ];

        return array_filter(TxnType::cases(), function ($txnType) use ($excludedTypes) {
            return !in_array($txnType->value, $excludedTypes, true);
        });
    }
}
if (!function_exists('get_user_account')) {

    /**
     * Retrieves or creates an account for a user based on user ID and balance type.
     * @param int $userId The ID of the user.
     * @param string $balance The type of balance, defaulting to main balance if not specified.
     * @return mixed Returns the account object.
     * @version 1.0.0
     * @since 1.0
     */
    function get_user_account($userId, $balance = null)
    {
        // Default to main balance type if not specified.
        $balance = (empty($balance)) ? AccountBalanceType::MAIN : $balance;

        // Attempt to retrieve the account.
        $account = Account::where('user_id', $userId)
            ->where('balance', $balance)
            ->lockForUpdate()->first();

        // If no account exists, create a new one.
        if (blank($account)) {
            $account = Account::create([
                'user_id' => $userId,
                'balance' => $balance,
                'amount' => 0.00,
                'wallet_id' => generateUniqueWalletId()  // Generate a unique 10-character ID for the wallet
            ]);
        }

        return $account;
    }
}
if (!function_exists('w2n_by_wallet_id')) {

    /**
     * Retrieves or creates an account for a user based on user ID and balance type.
     * @param int $userId The ID of the user.
     * @param string $balance The type of balance, defaulting to main balance if not specified.
     * @return mixed Returns the account object.
     * @version 1.0.0
     * @since 1.0
     */
    function w2n_by_wallet_id($walletId, $userId=null)
    {
        // Attempt to retrieve the account.
        $account = Account::where('wallet_id', $walletId);
        if($userId)
            $account->where('user_id', $userId);

        $account = $account->lockForUpdate()->first();
        $nameMap = [
            AccountBalanceType::MAIN => __(sys_settings('account_main', 'Main Wallet')),
            AccountBalanceType::IB_WALLET => __(sys_settings('ib_wallet', 'IB Wallet')),
        ];
        if($account) {
            return Arr::get($nameMap, $account->balance);
        }
        return sys_settings('account_main', 'Main Wallet');
    }
}
if (!function_exists('get_user_account_by_wallet_id')) {

    /**
     * Retrieves or creates an account for a user based on user ID and balance type.
     * @param int $userId The ID of the user.
     * @param string $balance The type of balance, defaulting to main balance if not specified.
     * @return mixed Returns the account object.
     * @version 1.0.0
     * @since 1.0
     */
    function get_user_account_by_wallet_id($walletId, $userId=null)
    {
        // Attempt to retrieve the account.
        $account = Account::where('wallet_id', $walletId);
        if($userId)
            $account->where('user_id', $userId);

        $account = $account->lockForUpdate()->first();
        return $account;
    }
}
if (!function_exists('get_all_wallets')) {

    /**
     * Retrieves or creates an account for a user based on user ID and balance type.
     * @param int $userId The ID of the user.
     * @param string $balance The type of balance, defaulting to main balance if not specified.
     * @return mixed Returns the account object.
     * @version 1.0.0
     * @since 1.0
     */
    function get_all_wallets($userId,$balance = null)
    {
        // Attempt to retrieve the account.
        $accounts = Account::where('user_id', $userId);
        if($balance){
            $accounts->where('balance',$balance);
        }
        if($count = $accounts->count() ==  0){
            get_user_account($userId);
            $accounts = Account::where('user_id', $userId);
            if($balance){
                $accounts->where('balance',$balance);
            }
        }
        $accounts = $accounts->lockForUpdate()->get();

        return $accounts;
    }
}
if (!function_exists('transaction_method_name')) {

    /**
     * Retrieves or creates an account for a user based on user ID and balance type.
     * @param int $userId The ID of the user.
     * @param string $balance The type of balance, defaulting to main balance if not specified.
     * @return mixed Returns the account object.
     * @version 1.0.0
     * @since 1.0
     */
    function transaction_method_name($transaction)
    {
        if ($transaction->type == TxnType::Deposit ||
            $transaction->type == TxnType::ManualDeposit ||
            $transaction->type == TxnType::DemoDeposit) {

            // Check if depositMethod exists, if not return the method
            return $transaction->depositMethod ? $transaction->depositMethod->name : $transaction->method;

        } elseif ($transaction->type == TxnType::Withdraw || $transaction->type == TxnType::WithdrawAuto) {

            return $transaction->method;

        } else {
            return $transaction->method;
        }
    }

}
if (!function_exists('transaction_method_image')) {

    /**
     * Retrieves or creates an account for a user based on user ID and balance type.
     * @param int $userId The ID of the user.
     * @param string $balance The type of balance, defaulting to main balance if not specified.
     * @return mixed Returns the account object.
     * @version 1.0.0
     * @since 1.0
     */
    function transaction_method_image($transaction)
    {
        // Check for deposit-related transaction types
        if (in_array($transaction->type, [TxnType::Deposit, TxnType::ManualDeposit, TxnType::DemoDeposit])) {
            // Check if depositMethod and depositMethod->icon are set, otherwise use the default image
            return !empty($transaction->depositMethod) && !empty($transaction->depositMethod->icon)
                ? $transaction->depositMethod->icon
                : \setting('default_transaction_method', 'theme');
        }
        // Check for withdrawal-related transaction types
        elseif (in_array($transaction->type, [TxnType::Withdraw, TxnType::WithdrawAuto])) {
            // Use the method for withdrawal type
            return !empty($transaction->withdrawMethod) && !empty($transaction->withdrawMethod->icon)
                ? $transaction->withdrawMethod->icon
                : \setting('default_transaction_method', 'theme');        }
        // For any other transaction types, apply default image
        else {
            return \setting('default_transaction_method', 'theme');
        }
    }

}

/**
 * Generates a unique 10-character alphanumeric ID.
 * @return string
 */
if (!function_exists('generateUniqueWalletId')) {
    function generateUniqueWalletId()
    {
        do {
            // Generate a random 10-digit integer.
            $id = mt_rand(1000000000, 9999999999);
        } while (Account::where('wallet_id', $id)->exists()); // Ensure uniqueness in the database.

        return $id;
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
//if (!function_exists('account_balance')) {
//    /**
//     * @param $account
//     * @param $userId | auth->user
//     * @version 1.0.0
//     * @since 1.0
//     */
//
//    function account_balance($account = null, $type = 'base')
//    {
//        $account = (empty($account)) ? AccountBalanceType::MAIN : $account;
//        $userid = auth()->user()->id;
//        $account = Account::where('user_id', $userid)->where('balance', $account)->first();
//        $amount = (!blank($account)) ? data_get($account, 'amount', 0.00) : 0.00;
//
//        if ($type == 'alter' || $type == 'secondary') {
//            return to_amount(base_to_secondary($amount), secondary_currency());
//        }
//
//        return to_amount($amount, base_currency());
//    }
//}
//
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
//
//if (!function_exists('secondary_currency')) {
//    /**
//     * @return mixed
//     * @version 1.0.0
//     * @since 1.0
//     */
//    function secondary_currency()
//    {
//        return sys_settings('alter_currency', 'USD');
//    }
//}
//if (!function_exists('base_to_secondary')) {
//
//    /**
//     * @param $amount
//     * @return float|int
//     * @version 1.0.0
//     * @since 1.0
//     */
//    function base_to_secondary($amount)
//    {
//        if ($amount == 0) {
//            return 0.00;
//        }
//        $secondaryCurrency = secondary_currency();
//        $exchangeRate = get_exchange_rates(actived_exchange(), $secondaryCurrency);
//        return ($amount * $exchangeRate);
//    }
//}

if (!function_exists('w2n')) {
    /**
     * @param $account
     * @version 1.0.0
     * @since 1.0
     */

    function w2n($account = null)
    {
        $account = (empty($account)) ? AccountBalanceType::MAIN : $account;
        $nameMap = [
            AccountBalanceType::MAIN => __(sys_settings('account_main', 'Main Wallet')),
              AccountBalanceType::IB_WALLET => __(sys_settings('ib_wallet', 'IB Wallet')),
           ];
        return Arr::get($nameMap, $account);
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
            return Setting::all()->pluck('name', 'val');
        });
//dd($settings);
        $value = $settings->get($key) ?? $default;


        return is_json($value) ? json_decode($value, true) : $value;
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
if (!function_exists('getSettingByColumn')) {
    function getSettingByColumn($key, $attribute = 'created_at')
    {
        $setting = \App\Models\Setting::where('name', $key)->first();
//        dd($setting);

        // Ensure the attribute exists on the model to avoid potential errors
        if ($setting) {
            // Check if the attribute is an instance of Carbon (or a date field)
            if ($setting->{$attribute} instanceof \Illuminate\Support\Carbon) {
                return $setting->{$attribute}->format('d M, Y H:i'); // Custom format
            }
            return $setting->{$attribute}; // Return non-date attribute directly
        }

        return 'N/A';
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
        $setting = \App\Models\PageSetting::where('key', $key)->first();
        return $setting ? $setting->value : null;
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

if (!function_exists('getCountryDialCode')) {

    function getCountryDialCode($countryName)
    {
        $filePath = resource_path('json/CountryCodes.json');

        if (!file_exists($filePath)) {
            return null;
        }

        $jsonContents = file_get_contents($filePath);
        $countries = json_decode($jsonContents, true);

        foreach ($countries as $country) {
            if (strcasecmp($country['name'], $countryName) == 0) {
                return $country['dial_code'] ?? null;
            }
        }

        return null;
    }
}

if (!function_exists('getCountryFromPhone')) {

    /**
     * Detect country metadata from an E.164 phone by matching longest dial code prefix.
     * Returns ['name' => string, 'dial_code' => string, 'code' => string] or null.
     */
    function getCountryFromPhone(string $e164Phone)
    {
        $normalized = preg_replace('/[^\d\+]/', '', $e164Phone);
        if (empty($normalized) || !str_starts_with($normalized, '+')) {
            return null;
        }

        $filePath = resource_path('json/CountryCodes.json');
        if (!file_exists($filePath)) {
            return null;
        }

        $countries = json_decode(file_get_contents($filePath), true);
        // Find the longest matching dial code prefix
        $match = null;
        $maxLen = 0;
        foreach ($countries as $country) {
            if (!isset($country['dial_code'])) continue;
            $dial = $country['dial_code']; // e.g. +44
            if (str_starts_with($normalized, $dial)) {
                $len = strlen($dial);
                if ($len > $maxLen) {
                    $maxLen = $len;
                    $match = [
                        'name' => $country['name'] ?? null,
                        'dial_code' => $dial,
                        'code' => $country['code'] ?? null,
                    ];
                }
            }
        }
        return $match;
    }
}

if (!function_exists('validateAndNormalizePhone')) {

    /**
     * Validate and normalize a phone number using libphonenumber.
     * - Validates general number structure and, if a country is provided, region-specific validity
     * - Returns E.164 formatted number on success
     * - Optionally detects country from number when not supplied
     *
     * @param string|null $rawPhone Raw phone from input (may include spaces, dashes, etc.)
     * @param string|null $countryRaw Country input; may be name or in format "Name:+Dial"
     * @param string|null $fallbackCountry Country name to use if $countryRaw is empty
     * @return array{valid: bool, e164?: string, error?: string, detected?: array{name: ?string, dial_code: ?string, code: ?string}, region?: ?string}
     */
    function validateAndNormalizePhone(?string $rawPhone, ?string $countryRaw = null, ?string $fallbackCountry = null, bool $enforceRegion = false): array
    {
        $result = ['valid' => false];
        $phone = (string) ($rawPhone ?? '');
        if ($phone === '') {
            $result['error'] = 'Phone number is required.';
            return $result;
        }

        // Normalize country input to name and ISO2 region code
        $countrySource = $countryRaw ?? $fallbackCountry ?? '';
        $countryName = $countrySource;
        if ($countryName !== '' && strpos($countryName, ':') !== false) {
            $countryName = explode(':', $countryName)[0];
        }

        $region = null; // ISO2, e.g. PK
        if ($countryName !== '') {
            $iso2 = getCountryCode($countryName);
            if (!empty($iso2)) {
                $region = strtoupper($iso2);
            }
        }

        try {
            $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $proto = $phoneUtil->parse($phone, $region ?: 'ZZ');

            if (!$phoneUtil->isValidNumber($proto)) {
                $result['error'] = 'Invalid phone number for the selected country.';
                return $result;
            }

            if ($enforceRegion && !empty($region) && !$phoneUtil->isValidNumberForRegion($proto, $region)) {
                $result['error'] = 'Phone number does not match the selected country.';
                return $result;
            }

            $e164 = $phoneUtil->format($proto, \libphonenumber\PhoneNumberFormat::E164);
            $result['e164'] = $e164;
            $result['valid'] = true;

            // Detect country from phone
            $detected = getCountryFromPhone($e164);
            if ($detected) {
                $result['detected'] = $detected;
                $result['region'] = $detected['code'] ?? null;
            } else {
                $result['region'] = $region;
            }
        } catch (\libphonenumber\NumberParseException $e) {
            $result['error'] = 'Invalid phone number format.';
        }

        return $result;
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
//dd($location,$currentCountry,getCountries());
        $location = [
            'country_code' => $currentCountry['code'] ?? '00',
            'name' => $currentCountry['name'] ?? 'Not found',
            'dial_code' => $currentCountry['dial_code'] ?? '+971',
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

        if (!$info || !$info->credentials) {
            return null;
        }

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

if (!function_exists('creditReferralBonus')) {
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
}

if (!function_exists('creditMultiIbBonus')) {
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
}

if (!function_exists('createMultiIBAccount')) {
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
            case TxnType::ReceiveMoneyInternal->value:
            case TxnType::Deposit->value:
            case TxnType::ManualDeposit->value:
            case TxnType::Bonus->value:
            case TxnType::Refund->value:
            case TxnType::Exchange->value:
            case TxnType::Referral->value:
                $result = ['text-success', '+'];
                break;
            case TxnType::SendMoney->value:
            case TxnType::SendMoneyInternal->value:
            case TxnType::Withdraw->value:
            case TxnType::Subtract->value:
            case TxnType::BonusSubtract->value:
            case TxnType::BonusRefund->value:
                $result = ['text-danger', '-'];
                break;
        }
        $commonResult = array_intersect($value, $result);

        return current($commonResult);
    }
}

if (!function_exists('is_custom_rate')) {
    function is_custom_rate($gateway_code)
    {
        if (in_array($gateway_code, ['coinremitter', 'blockchain'])) {
            return 'USD';
        }
        return null;
    }
}
if (!function_exists('match2pay_currencies')) {
    function match2pay_currencies()
    {
        // Define the mapping of payment currencies to their gateway names
        return [
            'BTC' => 'BTC',
            'ETH' => 'ETH',
            'UST' => 'USDT ERC20',
            'UCC' => 'USDC ERC20',
            'TRX' => 'TRX',
            'USX' => 'USDT TRC20',
            'UCX' => 'USDC TRC20',
            'BNB' => 'BNB',
            'USB' => 'USDT BEP20',
            'MAT' => 'MATIC',
            'USP' => 'USDT POLYGON',
            'UCP' => 'USDC POLYGON',
            'XRP' => 'XRP',
            'DOG' => 'DOGECOIN',
            'LTC' => 'LTC',
            'SOL' => 'SOL',
            'USS' => 'USDT SOL',
            'UCS' => 'USDC SOL',
            'TON' => 'TON',
            'UTT' => 'USDT TON',
        ];

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
if (!function_exists('kyc_required_completed_level')) {
    function kyc_required_completed_level()
    {
        $kycLevels = kyclevel::with('kyc_sub_levels')->where('status', true);
        $totalActiveLevels = $kycLevels->count();
        $kycCompletedLevel = 0;
        if($totalActiveLevels == 1){
            if(kyclevel::where('id', 1)->where('status', true)->exists()) {
                $kycCompletedLevel = App\Enums\KYCStatus::Level1->value;
            }elseif(kyclevel::where('id', 2)->where('status', true)->exists()) {
                $kycCompletedLevel = App\Enums\KYCStatus::Level2->value;
            }elseif(kyclevel::where('id', 3)->where('status', true)->exists()) {
                $kycCompletedLevel = App\Enums\KYCStatus::Level3->value;
            }
        }elseif($totalActiveLevels == 2){
            // Assume you have the highest level like this:
            $highestLevel = kyclevel::where('status', true)->orderBy('id', 'desc')->first();
            $enumLevelName = 'Level' . $highestLevel->id;
            $kycCompletedLevel = constant("App\\Enums\\KYCStatus::$enumLevelName")->value;

        }elseif($totalActiveLevels == 3){
            $kycCompletedLevel = App\Enums\KYCStatus::Level3->value;
        }
        return $kycCompletedLevel;
    }
}
if (!function_exists('kyc_first_level_check')) {
    function kyc_first_level_check()
    {
        $kycLevels = kyclevel::with('kyc_sub_levels')->where('status', true);
        $totalActiveLevels = $kycLevels->count();
        $kycCompletedLevel = 0;
        if($totalActiveLevels == 1){
            if(kyclevel::where('id', 1)->where('status', true)->exists()) {
                $kycCompletedLevel = App\Enums\KYCStatus::Level1->value;
            }elseif(kyclevel::where('id', 2)->where('status', true)->exists()) {
                $kycCompletedLevel = App\Enums\KYCStatus::Level2->value;
            }elseif(kyclevel::where('id', 3)->where('status', true)->exists()) {
                $kycCompletedLevel = App\Enums\KYCStatus::Level3->value;
            }
        }elseif($totalActiveLevels == 2){
            // Assume you have the highest level like this:
            $highestLevel = kyclevel::where('status', true)->orderBy('id', 'desc')->first();
            $enumLevelName = 'Level' . $highestLevel->id;
            $kycCompletedLevel = constant("App\\Enums\\KYCStatus::$enumLevelName")->value;

        }elseif($totalActiveLevels == 3){
            $kycCompletedLevel = App\Enums\KYCStatus::Level3->value;
        }
        return $kycCompletedLevel;
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
//        dd($users);
        foreach ($users as $user) {
            $forexAccounts = ForexAccount::where('user_id', $user->id)
                ->where('account_type', 'real')
                ->get();
//        dd($forexAccounts,$this->user);
//            $forexApiTrait = new class {
//                use ForexApiTrait;
//            };
            $forexApiService = new ForexApiService();
            foreach ($forexAccounts as $forexAccount) {
//                dd($forexAccount);
                if ($pUser->ib_login) {
                    $data = [
                        'login' => $forexAccount->login,
                        'agent' => $pUser->ib_login,
                    ];
                    $forexApiService->updateAgentAccount($data);
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
        $forexApiService = new ForexApiService();
        foreach ($forexAccounts as $forexAccount) {
                $data = [
                    'login' => $forexAccount->login,
                    'agent' => 0,
                    ];

                $forexApiService->updateAgentAccount($data);
            }
    }
}
if (!function_exists('get_mt5_account')) {
    /**
     * Retrieves the MT5 account details for a specific login.
     *
     * @param int $login The login ID of the MT5 account.
     * @return object|null The MT5 account object, or null if not found or on error.
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account($login)
    {
        if(isset($login) && $login > 0) {
            try {
                $mt5Service = app(\App\Services\MT5DatabaseService::class);
                return $mt5Service->executeWithTimeout(function () use ($login) {
                    return DB::connection('mt5_db')
                        ->table('mt5_accounts')
                        ->where('Login', $login)
                        ->first();
                }, null);
            } catch (\Exception $e) {
                \Log::error('MT5 DB connection failed when retrieving account: ' . $e->getMessage());
                return null;
            }
        }
        return null;
    }
}
if (!function_exists('get_mt5_account_balance')) {
    /**
     * Retrieves the balance of an MT5 account for a specific login.
     *
     * @param int $login The login ID of the MT5 account.
     * @return float The balance of the MT5 account, or 0.0 if not found or on error.
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account_balance($login)
    {
        if (!isset($login) || $login <= 0) {
            return 0.0;
        }

        try {
            $mt5Service = app(\App\Services\MT5DatabaseService::class);
            return $mt5Service->getAccountBalance($login);
        } catch (\Exception $e) {
            \Log::error('Failed to get MT5 account balance via service: ' . $e->getMessage());
            return 0.0;
        }
    }
}

if (!function_exists('get_mt5_account_equity')) {
    /**
     * Retrieves the equity of an MT5 account for a specific login.
     *
     * @param int $login The login ID of the MT5 account.
     * @return float The equity of the MT5 account, or 0.0 if not found or on error.
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account_equity($login)
    {
        if (!isset($login) || $login <= 0) {
            return 0.0;
        }

        try {
            $mt5Service = app(\App\Services\MT5DatabaseService::class);
            return $mt5Service->executeWithTimeout(function () use ($login) {
                $mt5Account = DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->where('Login', $login)
                    ->first();
                    
                return $mt5Account ? (float)$mt5Account->Equity : 0.0;
            }, 0.0);
        } catch (\Exception $e) {
            \Log::error('Failed to get MT5 account equity via service: ' . $e->getMessage());
            return 0.0;
        }
    }
}

if (!function_exists('get_mt5_account_credit')) {
    /**
     * Retrieves the credit of an MT5 account for a specific login.
     *
     * @param int $login The login ID of the MT5 account.
     * @return float The credit of the MT5 account, or 0.0 if not found or on error.
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account_credit($login)
    {
        if (!isset($login) || $login <= 0) {
            return 0.0;
        }

        try {
            $mt5Service = app(\App\Services\MT5DatabaseService::class);
            return $mt5Service->executeWithTimeout(function () use ($login) {
                $mt5Account = DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->where('Login', $login)
                    ->first();
                    
                return $mt5Account ? (float)$mt5Account->Credit : 0.0;
            }, 0.0);
        } catch (\Exception $e) {
            \Log::error('Failed to get MT5 account credit via service: ' . $e->getMessage());
            return 0.0;
        }
    }
}

if (!function_exists('apply_cent_account_adjustment')) {
    /**
     * Adjust amount if the account is a cent account.
     *
     * @param int|string $login
     * @param float|int|string|\Brick\Math\BigDecimal $amount
     * @return float
     */
    function apply_cent_account_adjustment($login, float|int|string|\Brick\Math\BigDecimal $amount): float
    {
        try {
            // Normalize amount to float
            if ($amount instanceof \Brick\Math\BigDecimal) {
                $amount = $amount->toFloat();
            } elseif (is_string($amount)) {
                $amount = (float) trim($amount);
            } elseif (is_int($amount)) {
                $amount = (float) $amount;
            } elseif (!is_float($amount)) {
                throw new \InvalidArgumentException('Invalid amount type provided to apply_cent_account_adjustment.');
            }

            // Fetch the forex account by login
            $account = \App\Models\ForexAccount::where('login', $login)
                ->where('account_type', 'real')
                ->with('schema')
                ->first();

            if (!$account || !$account->schema) {
                return $amount;
            }

            return $account->schema->is_cent_account ? $amount * 100 : $amount;

        } catch (\Throwable $e) {
            \Log::error("Failed to apply cent account adjustment", [
                'login' => $login,
                'amount' => (string) $amount,
                'type' => gettype($amount),
                'error' => $e->getMessage(),
            ]);
            return (float) (is_numeric($amount) ? $amount : 0);
        }
    }
}


if (!function_exists('get_mt5_account_equity')) {
    /**
     * Retrieves the equity of an MT5 account for a specific login.
     *
     * @param int $login The login ID of the MT5 account.
     * @return float The equity of the MT5 account, or 0.0 if not found or on error.
     * @version 1.0.0
     * @since 1.0
     */
    function get_mt5_account_equity($login)
    {
        if(isset($login) && $login > 0) {
            try {
                $mt5Service = app(\App\Services\MT5DatabaseService::class);
                return $mt5Service->executeWithTimeout(function () use ($login) {
                    $mt5Account = DB::connection('mt5_db')
                        ->table('mt5_accounts')
                        ->where('Login', $login)
                        ->first();
                    return $mt5Account ? $mt5Account->Equity : 0.0;
                }, 0.0);
            } catch (\Exception $e) {
                \Log::error('MT5 DB connection failed when retrieving equity: ' . $e->getMessage());
                return 0.0;
            }
        }
        return 0.0;
    }
}



if (!function_exists('mt5_total_balance')) {
    /**
     * Calculates the total balance for a user's ongoing real forex accounts.
     *
     * @param int $user_id The ID of the user.
     * @return float The total balance, or 0 if the connection fails.
     * @version 1.0.0
     * @since 1.0
     */


    function mt5_total_balance($user_id)
    {

        // Define a cache key for the database connection status
        $cacheKey = 'mt5_db_connection_status';

        // Check if the database is marked as unavailable
        if (Cache::has($cacheKey) && Cache::get($cacheKey) === 'down') {
            // Return 0 immediately without attempting to connect
            return 0;
        }
//        dd($user_id);
//         Attempt to establish a database connection
        try {
            DB::connection('mt5_db')->getPdo();
        } catch (\PDOException $e) {
//            \Log::error('MT5 DB connection failed: ' . $e->getMessage());
            Cache::put($cacheKey, 'down', now()->addMinutes(5)); // Adjust the duration as needed
            return 0;
        }

        // Proceed with your query since the connection is established
        try {
            // Fetch the forex account logins for the user
            $forexAccounts = ForexAccount::where('user_id', $user_id)
                ->where('account_type', 'real')
                ->where('status', ForexAccountStatus::Ongoing)
                ->pluck('login');
//            dd($forexAccounts);

            // If no accounts found, return 0
            if ($forexAccounts->isEmpty()) {
                return 0;
            }
//dd($forexAccounts);
          //   Calculate the total balance using the mt5_db connection
            // Use the resilient MT5DatabaseService
            $mt5Service = app(\App\Services\MT5DatabaseService::class);
            return $mt5Service->executeWithTimeout(function () use ($forexAccounts) {
                return (float)DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->whereIn('Login', $forexAccounts)
                    ->sum('Balance');
            }, 0.0);
        } catch (\Exception $e) {
            // Handle other exceptions if necessary
            \Log::error('An error occurred: ' . $e->getMessage());
            return 0;
        }
    }
}

if (!function_exists('mt5_total_equity')) {
    /**
     * Calculates the total equity for a user's ongoing real forex accounts.
     *
     * @param int $user_id The ID of the user.
     * @return float The total equity, or 0 if the connection fails.
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_total_equity($user_id)
    {
        try {
            // Fetch the forex account logins for the user
            $forexAccounts = ForexAccount::where('user_id', $user_id)
                ->where('account_type', 'real')
                ->where('status', ForexAccountStatus::Ongoing)
                ->pluck('login')
                ->toArray();

            // If no accounts found, return 0
            if (empty($forexAccounts)) {
                return 0;
            }

            // Use the resilient MT5DatabaseService
            $mt5Service = app(\App\Services\MT5DatabaseService::class);
            return $mt5Service->getTotalEquity($forexAccounts);

        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('MT5 DB connection failed: ' . $e->getMessage());

            // Return 0 in case of any failure
            return 0;
        }
    }
}

if (!function_exists('mt5_total_credit')) {
    /**
     * Calculates the total credit for a user's ongoing real forex accounts.
     *
     * @param int $user_id The ID of the user.
     * @return float The total credit, or 0 if the connection fails.
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_total_credit($user_id)
    {

        try {
            // Fetch the forex account logins for the user
            $forexAccounts = ForexAccount::where('user_id', $user_id)
                ->where('account_type', 'real')
                ->where('status', ForexAccountStatus::Ongoing)
                ->pluck('login');

            // If no accounts found, return 0
            if ($forexAccounts->isEmpty()) {
                return 0;
            }

            // Calculate the total credit using the mt5_db connection
            $totalCredit = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $forexAccounts)
                ->sum('Credit');

            return $totalCredit;

        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('MT5 DB connection failed: ' . $e->getMessage());

            // Return 0 in case of any failure
            return 0;
        }
    }
}
if (!function_exists('mt5_total_used_margin')) {
    /**
     * Calculates the total credit for a user's ongoing real forex accounts.
     *
     * @param int $user_id The ID of the user.
     * @return float The total credit, or 0 if the connection fails.
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_total_used_margin($user_id)
    {

        try {
            // Fetch the forex account logins for the user
            $forexAccounts = ForexAccount::where('user_id', $user_id)
                ->where('account_type', 'real')
                ->where('status', ForexAccountStatus::Ongoing)
                ->pluck('login');

            // If no accounts found, return 0
            if ($forexAccounts->isEmpty()) {
                return 0;
            }

            // Calculate the total credit using the mt5_db connection
            $totalMargin = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $forexAccounts)
                ->sum('Margin');

            return $totalMargin;

        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('MT5 DB connection failed: ' . $e->getMessage());

            // Return 0 in case of any failure
            return 0;
        }
    }
}
if (!function_exists('mt5_total_free_margin')) {
    /**
     * Calculates the total credit for a user's ongoing real forex accounts.
     *
     * @param int $user_id The ID of the user.
     * @return float The total credit, or 0 if the connection fails.
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_total_free_margin($user_id)
    {

        try {
            // Fetch the forex account logins for the user
            $forexAccounts = ForexAccount::where('user_id', $user_id)
                ->where('account_type', 'real')
                ->where('status', ForexAccountStatus::Ongoing)
                ->pluck('login');

            // If no accounts found, return 0
            if ($forexAccounts->isEmpty()) {
                return 0;
            }

            // Calculate the total credit using the mt5_db connection
            $totalMargin = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $forexAccounts)
                ->sum('MarginFree');

            return $totalMargin;

        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('MT5 DB connection failed: ' . $e->getMessage());

            // Return 0 in case of any failure
            return 0;
        }
    }
}

if (!function_exists('mt5_update_balance')) {
    /**
     * Updates the balance and equity for a specific forex account login.
     *
     * @param int $login The login ID of the forex account.
     * @param float $balance The new balance to be set.
     * @return bool True if update was successful, False if it failed.
     * @version 1.0.0
     * @since 1.0
     */
    function mt5_update_balance($login, $balance)
    {
        try {
            // Update the balance and equity for the given login
            $updated = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->where('Login', $login)
                ->update(['Balance' => $balance, 'Equity' => $balance]);

            // Return true if update was successful
            return $updated > 0;

        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('MT5 DB connection failed during balance update: ' . $e->getMessage());

            // Return false in case of failure
            return false;
        }
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

        return ['r' => $r, 'g' => $g, 'b' => $b];
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

if (!function_exists('isDarkColor')) {
    function isDarkColor($hex)
    {
        $rgb = hexToRgb($hex);
        $luminance = 0.2126 * $rgb['r'] + 0.7152 * $rgb['g'] + 0.0722 * $rgb['b'];

        return $luminance < 128;
    }
}

if (!function_exists('document_link_by_slug')) {
    function document_link_by_slug($slug)
    {
        return App\Models\DocumentLink::where('slug', $slug)->where('status', 1)->first();
    }
}

if (!function_exists('social_links')) {
    function social_links()
    {
        return App\Models\SocialLink::where('status', 1)->get();
    }
}

if (! function_exists('getFilteredPath')) {
    function getFilteredPath($path = null, $fallback = 'fallbacks/default.png')
    {
        // Step 1: If the path is a valid full URL (http or https), return as-is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Step 2: If it's a non-empty local path (e.g., from DB), return using asset()
        if (!empty($path)) {
            return asset($path);
        }

        // Step 3: Fallback using R2 asset URL
        return config('app.r2_asset_url') . '/' . ltrim($fallback, '/');
    }
}

if (!function_exists('get_recursive_equity_details')) {
    /**
     * Get detailed recursive equity information for a user and their downline
     *
     * @param User $user The user to calculate equity for
     * @return array Returns an array containing:
     *               - personal_equity: User's own equity
     *               - direct_referral_equity: Total equity of direct referrals
     *               - total_downline_equity: Total equity of entire downline (all levels)
     *               - total_equity: Sum of personal + downline equity
     *               - referral_count: Total number of referrals in downline
     *               - levels_data: Array of equity data per level
     */
    function get_recursive_equity_details($user) {
        $result = [
            'personal_equity' => mt5_total_equity($user->id),
            'direct_referral_equity' => 0,
            'total_downline_equity' => 0,
            'total_equity' => 0,
            'referral_count' => 0,
            'levels_data' => []
        ];

        // Calculate direct referral equity
        foreach ($user->referrals as $directRef) {
            $result['direct_referral_equity'] += mt5_total_equity($directRef->id);
        }

        // Function to recursively process levels
        $processLevel = function($currentUser, $level = 1) use (&$processLevel, &$result) {
            if (!isset($result['levels_data'][$level])) {
                $result['levels_data'][$level] = [
                    'level_equity' => 0,
                    'referral_count' => 0
                ];
            }

            foreach ($currentUser->referrals as $referral) {
                // Add to level totals
                $referralEquity = mt5_total_equity($referral->id);
                $result['levels_data'][$level]['level_equity'] += $referralEquity;
                $result['levels_data'][$level]['referral_count']++;

                // Add to overall totals
                $result['total_downline_equity'] += $referralEquity;
                $result['referral_count']++;

                // Process next level
                $processLevel($referral, $level + 1);
            }
        };

        // Start processing from level 1
        $processLevel($user);

        // Calculate total equity
        $result['total_equity'] = $result['personal_equity'] + $result['total_downline_equity'];

        return $result;
    }
}

if (!function_exists('getAccessibleUserIds')) {
    function getAccessibleUserIds(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $loggedInUser = auth('admin')->user();

        // Get staff's assigned branch IDs (top level filter)
        $staffBranchIds = $loggedInUser->branches()->pluck('branches.id')->toArray();
        $hasBranchRestriction = !empty($staffBranchIds) && !$loggedInUser->hasRole('Super-Admin');

        // Case 1: Super Admin - no restrictions
        if ($loggedInUser->hasRole('Super-Admin')) {
            return User::applyFilters($filters);
        }

        // Case 2: Staff with "show all users" permission
        if ($loggedInUser->can('show-all-users-by-default-to-staff')) {
            if ($hasBranchRestriction) {
                // Show all users within assigned branches only
                return User::whereHas('user_metas', function ($q) use ($staffBranchIds) {
                    $q->where('meta_key', 'branch_id')
                      ->whereIn('meta_value', array_map('strval', $staffBranchIds));
                })->applyFilters($filters);
            } else {
                // No branch restriction - show all users (existing behavior)
                return User::applyFilters($filters);
            }
        }

        // Case 3: Staff without "show all users" permission - use attached users
        $finalUserIds = collect();

        // Add directly attached users
        $attachedUserIds = $loggedInUser->users->pluck('id');
        $finalUserIds = $finalUserIds->merge($attachedUserIds);

        // Merge users from child staff (team members)
        if ($loggedInUser->relationLoaded('teamMembers') || method_exists($loggedInUser, 'teamMembers')) {
            foreach ($loggedInUser->teamMembers as $teamMember) {
                if ($teamMember->can('show-all-users-by-default-to-staff')) {
                    if ($hasBranchRestriction) {
                        // Team member has "show all" permission but we still apply branch filter
                        return User::whereHas('user_metas', function ($q) use ($staffBranchIds) {
                            $q->where('meta_key', 'branch_id')
                              ->whereIn('meta_value', array_map('strval', $staffBranchIds));
                        })->applyFilters($filters);
                    } else {
                        // No branch restriction - show all users
                        return User::applyFilters($filters);
                    }
                }

                // Otherwise, merge their attached users
                $childAttachedUserIds = $teamMember->users->pluck('id');
                $finalUserIds = $finalUserIds->merge($childAttachedUserIds);
            }
        }

        // Apply branch filtering to attached users if staff has branch restrictions
        if ($finalUserIds->isNotEmpty()) {
            $query = User::whereIn('id', $finalUserIds->unique());
            
            if ($hasBranchRestriction) {
                // Further filter attached users by branch assignment
                $query = $query->whereHas('user_metas', function ($q) use ($staffBranchIds) {
                    $q->where('meta_key', 'branch_id')
                      ->whereIn('meta_value', array_map('strval', $staffBranchIds));
                });
            }
            
            return $query->applyFilters($filters);
        }

        // Otherwise, return empty result
        return User::where('id', -1); // Always false condition
    }
}

if (!function_exists('parseEmails')) {
    /**
     * Parse comma-separated email addresses from string or array.
     * Validates emails and returns unique, filtered array.
     * 
     * @param mixed $value String with comma-separated emails or array of emails
     * @return array Array of valid, unique email addresses
     * 
     * @example
     * parseEmails('admin@example.com, support@example.com') 
     * // Returns: ['admin@example.com', 'support@example.com']
     * 
     * parseEmails(['admin@example.com', 'invalid-email', 'support@example.com'])
     * // Returns: ['admin@example.com', 'support@example.com']
     */
    function parseEmails(mixed $value): array
    {
        if (is_array($value)) {
            return array_filter($value, function ($email) {
                return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
            });
        }

        if (is_string($value) && trim($value) !== '') {
            // Support comma-separated emails only
            return collect(explode(',', $value))
                ->map(function ($email) { 
                    return trim($email); 
                })
                ->filter(function ($email) { 
                    return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL); 
                })
                ->unique()
                ->values()
                ->toArray();
        }

        return [];
    }
}

if (!function_exists('getAttachedStaffAdminEmails')) {
    /**
     * Get unique staff admin emails attached to the given user via staff_user pivot.
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    function getAttachedStaffAdminEmails(int $userId)
    {
        return DB::table('staff_user')
            ->join('admins', 'staff_user.staff_id', '=', 'admins.id')
            ->where('staff_user.user_id', $userId)
            ->pluck('admins.email')
            ->filter()
            ->unique()
            ->values();
    }
}

if (!function_exists('notify_attached_staff_admins')) {
    /**
     * Notify all staff admins attached to a user using a template code.
     * Expects $notifier to provide mailNotify($email, $code, $shortcodes, $throwOnFailure).
     * @param object $notifier Controller/service using NotifyTrait
     * @param int $userId Target user id
     * @param string $templateCode Email template code
     * @param array $shortcodes Shortcodes for template replacement
     * @param bool $throwOnFailure If true, rethrow on mail send failures
     * @return int Number of emails attempted
     */
    function notify_attached_staff_admins($notifierOrUserId, int $userId = null, string $templateCode = '', array $shortcodes = [], bool $throwOnFailure = false): int
    {
        try {
            // Backward-compatible signature handling
            if (is_int($notifierOrUserId) && $userId === null) {
                $userId = $notifierOrUserId;
                $notifier = new class {
                    use \App\Traits\NotifyTrait;
                    public function send($email, $code, $shortcodes, $throwOnFailure) { return $this->mailNotify($email, $code, $shortcodes, $throwOnFailure); }
                };
            } else {
                $notifier = $notifierOrUserId;
            }

            $emails = getAttachedStaffAdminEmails($userId);
            Log::info('notify_attached_staff_admins', [
                'user_id' => $userId,
                'template' => $templateCode,
                'count' => $emails->count(),
            ]);

            foreach ($emails as $email) {
                if (method_exists($notifier, 'mailNotify')) {
                    $notifier->mailNotify($email, $templateCode, $shortcodes, $throwOnFailure);
                } elseif (method_exists($notifier, 'send')) {
                    $notifier->send($email, $templateCode, $shortcodes, $throwOnFailure);
                }
            }

            return $emails->count();
        } catch (\Throwable $e) {
            Log::warning('notify_attached_staff_admins_failed', [
                'user_id' => $userId,
                'template' => $templateCode,
                'error' => $e->getMessage(),
            ]);
            if ($throwOnFailure) {
                throw $e;
            }
            return 0;
        }
    }
}

if (!function_exists('applyStaffNameFilter')) {
    function applyStaffNameFilter(\Illuminate\Database\Eloquent\Builder $query, string $staffName): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereHas('staff', function ($query) use ($staffName) {
            if (str_contains($staffName, ' ')) {
                $nameParts = explode(' ', $staffName, 2);

                $query->where(function ($subQuery) use ($nameParts) {
                    $subQuery->where(function ($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%' . $nameParts[0] . '%')
                          ->where('last_name', 'like', '%' . $nameParts[1] . '%');
                    })->orWhere(function ($q) use ($nameParts) {
                        $q->where('first_name', 'like', '%' . $nameParts[1] . '%')
                          ->where('last_name', 'like', '%' . $nameParts[0] . '%');
                    });
                });
            } else {
                $query->where(function ($q) use ($staffName) {
                    $q->where('first_name', 'like', '%' . $staffName . '%')
                      ->orWhere('last_name', 'like', '%' . $staffName . '%')
                      ->orWhere('email', 'like', '%' . $staffName . '%');
                });
            }
        });
    }
}

// Branch System Helper Functions
if (!function_exists('isBranchSystemEnabled')) {
    /**
     * Check if branch system is enabled
     * @return bool
     */
    function isBranchSystemEnabled(): bool
    {
        return (bool) sys_settings('branch_system_enabled', false);
    }
}

if (!function_exists('shouldApplyBranchFilter')) {
    /**
     * Determine if branch filtering should be applied
     * @return bool
     */
    function shouldApplyBranchFilter(): bool
    {
        if (!isBranchSystemEnabled()) {
            return false;
        }

        $admin = auth('admin')->user();
        if (!$admin) {
            return false;
        }

        // Super-Admin is exempt from branch filtering
        return !$admin->hasRole('Super-Admin');
    }
}

if (!function_exists('getCurrentUserBranchIds')) {
    /**
     * Get branch IDs accessible by current admin
     * @return array
     */
    function getCurrentUserBranchIds(): array
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return [];
        }

        if ($admin->hasRole('Super-Admin')) {
            return \App\Models\Branch::pluck('id')->toArray();
        }

        return $admin->branches()->pluck('branches.id')->toArray();
    }
}

if (!function_exists('getUserBranchId')) {
    /**
     * Get user's assigned branch ID using the common user_meta function
     * @param int|null $userId
     * @param \App\Models\User|null $user
     * @return int|null
     */
    function getUserBranchId($userId = null, $user = null): ?int
    {
        // If user object is provided, use it; otherwise get user by ID
        if ($user === null && $userId !== null) {
            $user = \App\Models\User::find($userId);
        }
        
        $branchId = user_meta('branch_id', null, $user);
        return $branchId ? (int) $branchId : null;
    }
}

if (!function_exists('setUserBranchId')) {
    /**
     * Set user's branch ID in user_metas using the same pattern as other meta values
     * @param int $userId
     * @param int|null $branchId
     * @return bool
     */
    function setUserBranchId(int $userId, ?int $branchId): bool
    {
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return false;
        }

        if ($branchId === null) {
            // Remove branch assignment
            return $user->user_metas()->where('meta_key', 'branch_id')->delete() > 0;
        }

        // Update or create branch assignment using the same pattern
        $user->user_metas()->updateOrCreate(
            ['meta_key' => 'branch_id'],
            ['meta_value' => (string) $branchId]
        );

        return true;
    }
}

if (!function_exists('applyBranchFilter')) {
    /**
     * Apply branch filtering to query for models with direct branch_id column
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $branchColumn
     * @return \Illuminate\Database\Eloquent\Builder
     */
    function applyBranchFilter($query, string $branchColumn = 'branch_id')
    {
        if (!shouldApplyBranchFilter()) {
            return $query;
        }

        $branchIds = getCurrentUserBranchIds();
        if (empty($branchIds)) {
            return $query->where('id', -1); // No access
        }

        return $query->whereIn($branchColumn, $branchIds);
    }
}

if (!function_exists('applyUserBranchFilter')) {
    /**
     * Apply branch filtering to User queries based on user_metas
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    function applyUserBranchFilter($query)
    {
        if (!shouldApplyBranchFilter()) {
            return $query;
        }

        $branchIds = getCurrentUserBranchIds();
        if (empty($branchIds)) {
            return $query->where('id', -1); // No access
        }

        return $query->whereHas('user_metas', function ($q) use ($branchIds) {
            $q->where('meta_key', 'branch_id')
              ->whereIn('meta_value', array_map('strval', $branchIds));
        });
    }
}

/**
 * Get success page by type
 *
 * @param string $type
 * @return object|null
 */
if (!function_exists('getSuccessPage')) {
    function getSuccessPage($type)
    {
        return \App\Models\SuccessPage::getByType($type);
    }
}

/**
 * Check if custom success page exists for type
 *
 * @param string $type
 * @return bool
 */
if (!function_exists('hasCustomSuccessPage')) {
    function hasCustomSuccessPage($type)
    {
        return \App\Models\SuccessPage::hasCustomPage($type);
    }
}

if (!function_exists('getAccountDetails')) {
    function getAccountDetails($targetId, $accountType = null, $userId = null)
    {

        $targetId = is_string($targetId) ? get_hash($targetId) : $targetId;

        $forexAccount = \App\Models\ForexAccount::where('login', $targetId)->first();

        if ($forexAccount) {
            return $forexAccount;
        }else{
            $walletAccount = get_user_account_by_wallet_id($targetId, $userId);
            return $walletAccount;
        }
    }
}

if (!function_exists('getDepositMethodDetails')) {
    function getDepositMethodDetails($gatewayCode)
    {
        return \App\Models\DepositMethod::code($gatewayCode)->first();
    }
}

if (!function_exists('getWithdrawAccountDetails')) {
    function getWithdrawAccountDetails($withdrawAccountId, $userId)
    {
        return \App\Models\WithdrawAccount::where('id', $withdrawAccountId)->where('user_id', $userId)->first();
    }
}