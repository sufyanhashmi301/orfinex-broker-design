<?php

namespace App\Http\Controllers\Backend;

use Cache;
use Exception;
use App\Models\Setting;
use App\Models\Country;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use ImageUpload , NotifyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:site-setting|email-setting', ['only' => ['update']]);
        $this->middleware('permission:site-setting|site-settings', ['only' => ['siteSetting']]);
        $this->middleware('permission:maintainance-settings', ['only' => ['siteMaintenance']]);
        $this->middleware('permission:email-setting', ['only' => ['mailSetting']]);
        $this->middleware('permission:company-setting', ['only' => ['companySetting']]);
        $this->middleware('permission:misc-setting', ['only' => ['miscSetting']]);
        $this->middleware('permission:company-permissions-setting', ['only' => ['companyPermissions']]);
        $this->middleware('permission:internal-transfer-display|external-transfer-display', ['only' => ['transfers']]);
        $this->middleware('permission:meta-trader-display', ['only' => ['platformApiSetting']]);
        $this->middleware('permission:mt5-webterminal-display', ['only' => ['mt5WebterminalSetting']]);
        $this->middleware('permission:x9-webterminal-display', ['only' => ['x9WebterminalSetting']]);
        $this->middleware('permission:copy-trading-setting', ['only' => ['copyTradingSetting']]);
        $this->middleware('permission:data-encryption-settings', ['only' => ['endToEndEncryption']]);
        $this->middleware('permission:clear-cache-settings', ['only' => ['clearCache']]);
        $this->middleware('permission:dev-mode-settings', ['only' => ['devMode']]);
        $this->middleware('permission:gdpr-compliance-settings', ['only' => ['grpdCompliance']]);
        $this->middleware('permission:api-access-setting', ['only' => ['apiAccess']]);
        $this->middleware('permission:web-hooks-setting', ['only' => ['webHook']]);
        $this->middleware('permission:currency-setting', ['only' => ['currencySetting']]);
        $this->middleware('permission:collab-tools-setting', ['only' => ['slackSetting']]);



    }

    public static function index()
    {
        return view('backend.setting.index');
    }

    /**
     * @return Application|Factory|View
     */
    public static function siteSetting()
    {
        return view('backend.setting.site_setting.index');
    }

    /**
     * @return Application|Factory|View
     */
    public static function mailSetting()
    {
        return view('backend.setting.email_setting.mail');
    }

    public static function googleMailSetting()
    {
        return view('backend.setting.email_setting.google-mail');
    }

    public static function sendGridSetting()
    {
        return view('backend.setting.email_setting.sendgrid');
    }

    public static function sesSetting()
    {
        return view('backend.setting.email_setting.ses');
    }

    public static function forexApiSetting()
    {
        return view('backend.setting.forex-api');
    }

    public static function platformApiSetting()
    {
        return view('backend.setting.platform_api.metatrader');
    }

    public function mailConnectionTest(Request $request)
    {
//        dd($request->all());
        $shortcodes = [
            '[[full_name]]' => 'test',
            '[[txn]]' =>'test',
            '[[gateway_name]]' => 'test',
            '[[deposit_amount]]' => 'test',
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[message]]' => 'test',
            '[[status]]' =>  'Pending',
        ];
        $this->mailNotify($request->email, 'user_manual_deposit_request', $shortcodes);

        try {
            Mail::raw('Testing SMTP connection successful', function ($message) use ($request) {
                $message->to($request->email);
            });

            notify()->success(__('SMTP connection test successful.'));

            return back();
        } catch (\Exception $e) {
            notify()->error('SMTP connection test failed: '.$e->getMessage(), 'Error');

            return redirect()->back();
        }

    }

    //store any setting

    /**
     * @return RedirectResponse
     */

public function update(Request $request)
{
    $section = $request->section;
    $rules = Setting::getValidationRules($section);
    // Dynamically add validation rules based on the type
    if ($section == 'transfer_internal') {
        $rules['internal_min_send'] = 'required|numeric|min:1|max:10000';
        $rules['internal_max_send'] = 'required|numeric|min:1|max:10000000|gte:internal_min_send';
        $rules['internal_send_charge'] = 'required|numeric|min:0';
        $rules['internal_send_charge_type'] = 'required|in:fixed,percentage';
        $rules['internal_send_daily_limit'] = 'required|numeric|min:1';
        $rules['is_internal_transfer'] = 'nullable|boolean';
    } elseif ($section == 'transfer_external') {
        $rules['external_min_send'] = 'required|numeric|min:1|max:10000';
        $rules['external_max_send'] = 'required|numeric|min:1|max:10000000|gte:external_min_send';
        $rules['external_send_charge'] = 'required|numeric|min:0';
        $rules['external_send_charge_type'] = 'required|in:fixed,percentage';
        $rules['external_send_daily_limit'] = 'required|numeric|min:1';
        $rules['is_external_transfer'] = 'nullable|boolean';
        $rules['is_external_transfer_auto_approve'] = 'nullable|boolean';
        $rules['is_external_transfer_purpose'] = 'nullable|boolean';
    } elseif ($section == 'transfer_misc') {
        $rules['wallet_exchange_charge'] = 'required|numeric|min:0';
        $rules['wallet_exchange_charge_type'] = 'required|in:fixed,percentage';
//        $rules['referral_bonus'] = 'required|numeric|min:0';
//        $rules['signup_bonus'] = 'required|numeric|min:0';
        $rules['wallet_exchange_day_limit'] = 'required|numeric|min:1';
        $rules['send_money_day_limit'] = 'required|numeric|min:1';
        $rules['withdraw_day_limit'] = 'required|numeric|min:1';
        $rules['investment_cancellation_daily_limit'] = 'required|numeric|min:1';
    }
//    dd($request->all(),$rules);

    // Validate the request
    $validator = Validator::make($request->all(), $rules);
//    dd($validator->fails());

    // If validation fails, display errors using notify
    if ($validator->fails()) {
        foreach ($validator->errors()->all() as $error) {
            notify()->error($error); // Display each validation error using notify
        }
        return redirect()->back()->withInput(); // Retain the old input values
    }
    // If validation passes, proceed with the update logic
    $data = $validator->validated();

    // Update session expiry
    $user = Auth::user();
    auth()->user()->update([
        'session_expiry' => $request->session_expiry
    ]);

    try {
        $validSettings = array_keys($rules);

        foreach ($data as $key => $val) {
            if (in_array($key, $validSettings)) {

                if ($request->hasFile($key)) {
                    $oldImage = Setting::get($key, $section);
                    $val = self::imageUploadTrait($val, $oldImage);
                }

                if (is_string($val)) {
                    // Replace `{` and `}` with `<` and `>`
                    $val = str_replace(['{', '}'], ['<', '>'], $val);
                }

                Setting::add($key, $val, Setting::getDataType($key, $section));
            }
        }

        if ($section == 'mt5_db_credentials') {
            Cache::forget('mt5_db_credentials');
        }

        notify()->success(__('Settings have been saved')); // Success message
        return redirect()->back();

    } catch (Exception $e) {
        notify()->error('Something went wrong. Please check the error log.', 'Error Log'); // Error message
        return redirect()->back();
    }
}

    public static function userPermissions()
    {
        return view('backend.setting.user_permissions.index');
    }

    public static function serverSetting()
    {
        return view('backend.server_settings.server.index');
    }

    public static function companySetting()
    {
        return view('backend.setting.company.company');
    }

    public static function companyPermissions()
    {
        return view('backend.setting.company.permission');
    }

    public static function customerPermissions()
    {
        return view('backend.setting.customer.permission');
    }

    public static function customerMiscSettings()
    {
        return view('backend.setting.customer.misc');
    }

    public static function miscSetting()
    {
        return view('backend.setting.company.misc');
    }

    public static function currencySetting()
    {
        $countries = Country::with('rate')->paginate(15);
        return view('backend.setting.site_setting.currency', compact('countries'));
    }

    public static function siteMaintenance()
    {
        return view('backend.setting.site_setting.site_maintenance');
    }

    public function transfers(Request $request)
    {
        $type = $request->query('type');
        return view('backend.setting.transfers.index', compact('type'));
    }

    public static function gdpr()
    {
        return view('backend.setting.site_setting.gdpr');
    }

    public static function devMode()
    {
        return view('backend.system.dev_mode');
    }

    public static function endToEndEncryption()
    {
        return view('backend.setting.data_management.end_to_end_encryption');
    }

    public static function clearCache()
    {
        return view('backend.system.cache_clear');
    }

    public static function apiAccess()
    {
        return view('backend.setting.integrations.api_access');
    }

    public static function webHook()
    {
        return view('backend.setting.integrations.web_hook');
    }

    public static function documentation()
    {
        return view('backend.setting.documentation');
    }

    public static function slackSetting()
    {
        return view('backend.setting.collab_tools.slack');
    }

    public function copyTradingSetting(){
        return view('backend.setting.copy_trading.brokeree');
    }

    public function  mt5WebterminalSetting(){
        return view('backend.setting.platform_api.mt5-webterminal');
    }

    public function  x9WebterminalSetting(){
        return view('backend.setting.platform_api.x9-webterminal');
    }

    public function  grpdCompliance(){
        return view('backend.setting.site_setting.gdpr_compliance');
    }

    public function testDatabaseConnection(Request $request)
    {
        $credentials = [
            'driver'    => 'mysql',
            'host'      => $request->input('database_host'),
            'port'      => $request->input('database_port'),
            'database'  => $request->input('database_name'),
            'username'  => $request->input('database_username'),
            'password'  => $request->input('database_password'),
        ];

        try {
            // Attempt to connect to the database
            DB::connection()->setPdo(new \PDO(
                "mysql:host={$credentials['host']};port={$credentials['port']};dbname={$credentials['database']}",
                $credentials['username'],
                $credentials['password'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            ));

            return response()->json(['status' => 'success', 'message' => 'Connection successful']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Connection failed: ' . $e->getMessage()]);
        }
    }

    public function changelog()
    {
        $url = 'https://cdn.brokeret.com/crm-assets/json/changelog.json';

        //$response = Http::get($url);
        $response = Http::withoutVerifying()->get($url);

        if ($response->successful()) {

            $data = $response->json();
            return view('backend.system.changelog', compact('data'));

        } else {
            return response()->json(['error' => 'Failed to fetch data from CDN'], 500);
        }
    }

    public function featureLocked()
    {
        return view('global.__feature_locked');
    }

}
