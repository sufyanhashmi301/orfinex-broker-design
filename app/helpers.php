<?php

use Carbon\Carbon;
use App\Enums\TxnType;
use App\Models\Gateway;
use App\Models\Setting;
use App\Models\Storage;
use App\Helpers\NioHash;
use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use App\Enums\KycStatusEnums;
use App\Enums\AccountTypePhase;
use App\Enums\InvestmentStatus;
use App\Enums\AccountBalanceType;
use App\Enums\StorageMethodEnums;
use App\Enums\KycNoticeInvokeEnums;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\AccountTypeInvestment;

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
if (!function_exists('getSettingCreatedAt')) {
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

if (!function_exists('percentage_calc')) {
    /**
     * @param @what
     * @since 1.0
     * @version 1.0.0
     */
    function percentage_calc($percent, $amount)
    {
        $scale = 2;
        $amount = (BigDecimal::of($amount)->compareTo(0) == 1) ? BigDecimal::of($amount)->multipliedBy(BigDecimal::of($percent))->dividedBy(100, $scale, RoundingMode::CEILING) : 0;

        return is_object($amount) ? (string)$amount : $amount;
    }
}
if (!function_exists('base_currency')) {
    /**
     * @version 1.0.0
     * @since 1.0
     */
    function base_currency()
    {
        return setting('site_currency', 'global');
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
        $scale = 2;
        $percentage = (BigDecimal::of($total)->compareTo(0) == 1) ? BigDecimal::of($amount)->multipliedBy(BigDecimal::of(100))->dividedBy($total, $scale, RoundingMode::CEILING) : 0;
        return is_object($percentage) ? (string)$percentage : $percentage;
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
    };
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
        return 0;
        try {
            return DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->where('Login', $login)
                ->first();
        } catch (\Exception $e) {
            \Log::error('MT5 DB connection failed when retrieving account: ' . $e->getMessage());
            return null;
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


if (!function_exists("generate_dummy_password")) {
    /**
     * @param object $method
     * @return array
     * @version 1.0.0
     * @since 1.1.3
     */
    function generate_dummy_password($length = 12)
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

if (!function_exists('social_links')) {
    function social_links()
    {
        return App\Models\SocialLink::where('status', 1)->get();
    }
}

if (!function_exists('kyc_invoke_at')) {
    function kyc_invoke_at() {
        return Setting::where('name', 'kyc_notice')->first() ? Setting::where('name', 'kyc_notice')->first()->val : KycNoticeInvokeEnums::FUNDED_PHASE;
    }
}

if (!function_exists('kyc_check_exists')) {
    function kyc_check_exists($kyc_invoke) {
        $user = Auth::user();
        if($user->kyc->status == KycStatusEnums::UNVERIFIED && kyc_invoke_at() == $kyc_invoke) {
            return true;
        }

        return false;
    }
}

if (!function_exists('show_kyc_notice')) {
    
    function show_kyc_notice() {
        $user = Auth::user();
        $kyc_invoke_at = kyc_invoke_at();

        if($user->kyc->status == KycStatusEnums::UNVERIFIED) {

            // Account Purchase (Always)
            if($kyc_invoke_at == KycNoticeInvokeEnums::ACCOUNT_PURCHASE) {
                return [
                    'show' => true,
                    'message' => 'buy new account(s).'
                ];
            }

            // Verification Phase (invoke when the user gets evaluation phase)
            if($kyc_invoke_at == KycNoticeInvokeEnums::VERIFICATION_PHASE) {
                $accounts = AccountTypeInvestment::where('user_id', $user->id)
                                                ->whereHas('accountTypePhase', function ($query) {
                                                    $query->where('type', AccountTypePhase::EVALUATION);
                                                })->get();
                if(count($accounts) > 0) {
                    return [
                        'show' => true,
                        'message' => 'get next phases account access.'
                    ];
                }
            }

            // Funded Phase (invoke when the user gets verification phase)
            if($kyc_invoke_at == KycNoticeInvokeEnums::FUNDED_PHASE) {
                $accounts = AccountTypeInvestment::where('user_id', $user->id)
                                                ->whereHas('accountTypePhase', function ($query) {
                                                    $query->where('type', AccountTypePhase::EVALUATION)->orWhere('type', AccountTypePhase::VERIFICATION);
                                                })->get();
                if(count($accounts) > 0) {
                    return [
                        'show' => true,
                        'message' => 'get funded account access without hassle.'
                    ];
                }
            }

            // Payout (invoke when the user gets funded phase)
            if($kyc_invoke_at == KycNoticeInvokeEnums::PAYOUT) {
                $accounts = AccountTypeInvestment::where('user_id', $user->id)
                                                ->whereHas('accountTypePhase', function ($query) {
                                                    $query->where('type', AccountTypePhase::FUNDED);
                                                })->get();
                if(count($accounts) > 0) {
                    return [
                        'show' => true,
                        'message' => 'create payout requests without hassle.'
                    ];
                }
            }
            

        }

        return [
            'show' => false,
            'message' => ''
        ];
        
    }

}

if (!function_exists('setEnvironmentValue')) {
    function setEnvironmentValue($values) {
   
        $envPath = base_path('.env');

        if (File::exists($envPath)) {
            $envContent = File::get($envPath);

            foreach ($values as $key => $value) {
                $keyValue = "{$key}=" . env($key);
                $newKeyValue = "{$key}=\"{$value}\"";

                // Replace old values with new ones
                $envContent = preg_replace("/^{$key}=.*/m", $newKeyValue, $envContent);
            }

            // Write updated content back to .env
            File::put($envPath, $envContent);
        }
    
    }
}

