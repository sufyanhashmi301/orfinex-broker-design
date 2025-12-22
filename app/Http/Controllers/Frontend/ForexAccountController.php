<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\IBStatus;
use App\Enums\InvestStatus;
use App\Enums\ForexAccountStatus;

use App\Enums\TraderType;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\DepositMethod;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\Invest;
use App\Models\LevelReferral;
use App\Models\LeverageUpdate;
use App\Models\Schema;
use App\Models\User;
use App\Models\PlatformLink;
use App\Rules\ForexLoginBelongsToUser;
use App\Services\ForexApiService;
use App\Rules\ForexLoginBelongsToUserGeneral;
use App\Services\x9ApiService;
use App\Services\ActivityLogService;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Txn;

class ForexAccountController extends GatewayController
{
    use ImageUpload, NotifyTrait, ForexApiTrait;

    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
    }

    public function forexAccountCreateNow(Request $request)
    {
        if (!setting('account_creation', 'kyc_permissions') && auth()->user()->kyc < kyc_required_completed_level())  {
            ActivityLogService::log('forex_account_create', "Account creation restricted due to KYC pending", [
                'Account Type' => $request->account_type,
            ]);
            notify()->error('KYC Pending: Please complete your KYC verification to proceed with your account creation', __('Error'));
            return redirect()->route('user.kyc');
        }
        $validator = Validator::make($request->all(), [
                'schema_id' => 'required',
                'main_password' => [
                    'required',
                    'min:8',
                    'max:20',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%&*():{}|<>])[A-Za-z\d!@#$%&*():{}|<>]+$/',
                ],
                'account_type' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!in_array($value, ['real', 'demo'])) {
                            $fail(__('The ' . $attribute . ' must be either real or demo.'));
                        }
                    },
                ],
                'investor_password' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $schema = ForexSchema::find(get_hash($request->schema_id));
                        if ($schema && $schema->is_update_investor_password) {
                            if (empty($value)) {
                                $fail(__('The investor password field is required.'));
                                return;
                            }
                            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%&*():{}|<>])[A-Za-z\d!@#$%&*():{}|<>]+$/', (string) $value)) {
                                $fail(__('The investor password must be 8–20 chars, include upper, lower, digit and special: ! @ # $ % & * ( ) : { } | < >'));
                            }
                            if (strlen((string) $value) < 8 || strlen((string) $value) > 20) {
                                $fail(__('The investor password must be between 8 and 20 characters.'));
                            }
                        }
                    },
                ],
                'is_islamic' => [
                    function ($attribute, $value, $fail) use ($request) {
                        $schema = ForexSchema::find(get_hash($request->schema_id));
                        if ($request->account_type === 'real' && $value == 1 && !$schema->is_real_islamic) {
                            $fail(__('The selected schema does not support Islamic account for Real account type.'));
                        }
                        if ($request->account_type === 'demo' && $value == 1 && !$schema->is_demo_islamic) {
                            $fail(__('The selected schema does not support Islamic account for Demo account type.'));
                        }
                    },
                ],
                'leverage' => 'required',
                'account_name' => 'required',
                'demo_deposit_amount' => [
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->account_type !== 'demo') { return; }
                        if ($value === null || $value === '') { return; } // optional
                        if (!is_numeric($value) || (float)$value < 0) {
                            $fail(__('Please enter a valid positive demo deposit amount.'));
                            return;
                        }
                        $schema = ForexSchema::find(get_hash($request->schema_id));
                        if ($schema) {
                            $min = $schema->demo_min_deposit_amount;
                            $max = $schema->demo_max_deposit_amount;
                            $num = (float) $value;
                            if ($min !== null && $num < (float)$min) {
                                $fail(__('The demo deposit must be at least :min.', ['min' => $min]));
                                return;
                            }
                            if ($max !== null && $num > (float)$max) {
                                $fail(__('The demo deposit must not exceed :max.', ['max' => $max]));
                                return;
                            }
                        }
                    },
                ],
            ], [
                'main_password.required' => __('The main password field is required.'),
                'main_password.min' => __('The main password must be at least 8 characters long.'),
                'main_password.max' => __('The main password must not exceed 20 characters.'),
                'main_password.regex' => __('The main password must be 8–20 characters long, contain at least one lowercase letter, one uppercase letter, one digit, and one special character from the following: ! @ # $ % & * ( ) : { } | < >'),
                'account_type.required' => 'The account type is required.',
                'leverage.not_regex' => __('Kindly select a valid leverage.'),
            ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));

            return redirect()->back();
        }
        $input = $request->all();
        $input['schema_id'] = get_hash($input['schema_id']);
        $user = Auth::user();
        $mainWalletBalance = user_balance();
        $schema = ForexSchema::find($input['schema_id']);
        $accountType = $request->account_type;
        $totalLimit = setting('forex_account_create_limit', 'forex_account_settings');
        if($user->account_limit > $totalLimit){
            $totalLimit = $user->account_limit;
       }
        //total account creation limit check
        $totalForexAccounts = ForexAccount::where(['user_id' => $user->id, 'account_type' => $accountType])->traderType()->count();
        if ($totalForexAccounts >= $totalLimit) {
            $message = __('Sorry, You have achieved your total account creation limit. Please contact support :support to increase your account limit.', ['title' => $schema->title,'support' => setting('support_email', 'common_settings')]);
            ActivityLogService::log('forex_account_create', "Account creation failed due to total limit reached", [
                'Account Type' => $accountType,
                'Total Limit' => $totalLimit,
                'Total Accounts' => $totalForexAccounts,
            ]);
            notify()->error($message, 'Error');
            return redirect()->back();
        }
        //specific type account creation limit check
        if (ForexAccount::where(['user_id' => $user->id, 'forex_schema_id' => $schema->id, 'account_type' => $accountType])->traderType()->count() >= $schema->account_limit) {
            $message = __('Sorry, You have achieved your account creation limit of :title type . Please choose different type or contact support to increase your account limit.', ['title' => $schema->title]);
            ActivityLogService::log('forex_account_create', "Account creation failed due to specific type limit reached", [
                'Account Type' => $accountType,
                'Total Limit' => $totalLimit,
                'Total Accounts' => $totalForexAccounts,
            ]);
            notify()->error($message, __('Error'));
            return redirect()->back();
        }
       //minimum balance limit check
        if ($schema->min_amount > $mainWalletBalance ) {
            $message = __('We’re sorry, but a minimum balance of :limit in your main wallet is required to create a new Forex account. Please make the necessary deposit and try again.', ['limit' => $schema->min_amount.' '.base_currency()]);
            ActivityLogService::log('forex_account_create', "Account creation failed due to minimum balance limit not met", [
                'Account Type' => $accountType,
                'Minimum Balance' => $schema->min_amount.' '.base_currency(),
                'Main Wallet Balance' => $mainWalletBalance,
            ]);
            notify()->error($message, 'Error');
            return redirect()->back();
        }

        $traderType = $schema->trader_type;
        if($traderType == TraderType::MT5) {
            $login = 0;
        }elseif($traderType == TraderType::X9) {
            $login = 'default';
        }
        //Start/End Range of create forex account on MT5
        if (setting('is_forex_group_range', 'global')) {
//            $forexAccount = ForexAccount::where('forex_schema_id', $schema->id)->orderBy('login', 'desc')->first();
            $forexAccount = ForexAccount::where('forex_schema_id', $schema->id)
                ->orderBy(DB::raw('CAST(login AS UNSIGNED)'), 'desc')
                ->first();

            if ($forexAccount) {
                // Get the highest login from the database (even if it's outside the range)
                $highestLogin = (int) $forexAccount->login;

                if ($highestLogin < $schema->start_range || $highestLogin >= $schema->end_range) {
                    // If highest login is out of range, reset to start_range
                    $withinRangeForexAccount = ForexAccount::where('forex_schema_id', $schema->id)
                        ->whereBetween('login', [$schema->start_range, $schema->end_range - 1])
                        ->orderBy(DB::raw('CAST(login AS UNSIGNED)'), 'desc')
                        ->first();
                    if($withinRangeForexAccount){
                        $login = (int) $withinRangeForexAccount->login  + 1;
                    }else{
                    $login = $schema->start_range;
                    }
                } else {
                    // Otherwise, increment login
                    $login = $highestLogin + 1;
                }

                // Validate if login exceeds end_range
                if ($login > $schema->end_range) {
                    $message = __('Account limit reached! Please ask the admin to increase the range.');
                    ActivityLogService::log('forex_account_create', "Account creation failed due to account limit reached", [
                        'Account Type' => $accountType,
                        'Account Limit' => $schema->account_limit,
                        'Total Accounts' => $totalForexAccounts,
                        'Total Limit' => $totalLimit,
                    ]);
                    notify()->error($message, 'Error');
                    return redirect()->back();
                }
            } else {
                // No accounts exist, so start from start_range
                $login = $schema->start_range;
            }

        }

        $server = $this->getServe($request,$schema);
        $group = $this->getGroup($user,$request, $schema);
        $password = $request->main_password;
        $investorPasswordInput = ($schema->is_update_investor_password ? ($request->investor_password ?: 'Inv@Pass1!') : 'Inv@Pass1!');
        if($user->phone){
            $phone = $user->phone;
        }else{
            $phone = '+91';

        }
//        dd($traderType,$group,$server);

        // Check if admin approval is enabled for this account type
        $adminApprovalEnabled = ($accountType === 'real' && setting('live_account_creation', 'features')) || ($accountType === 'demo' && setting('demo_account_creation', 'features'));
        
        // If admin approval is enabled, check account limit
        $limitReached = false;
        if ($adminApprovalEnabled) {
            $accountLimit = ($accountType === 'real') ? ($schema->live_account_limit ?? 0) : ($schema->demo_account_limit ?? 0);
            
            // Only check limit if limit is set (greater than 0, 0 means unlimited)
            if ($accountLimit > 0) {
                $userAccountCount = ForexAccount::where('user_id', $user->id)
                    ->where('forex_schema_id', $schema->id)
                    ->where('account_type', $accountType)
                    ->traderType()
                    ->whereIn('status', [ForexAccountStatus::Ongoing, ForexAccountStatus::Pending])
                    ->count();
                
                // If user has reached or exceeded the limit, require approval
                $limitReached = ($userAccountCount >= $accountLimit);
            }
            // If limit is 0 (unlimited), limitReached remains false, so account will be auto-approved
        }

        // If admin approval is enabled AND limit is reached, do NOT call platform APIs here; create local pending record only
        // If admin approval is enabled but limit NOT reached (or limit is 0/unlimited), proceed with normal flow (auto-approve)
        $manualApproval = $adminApprovalEnabled && $limitReached;

        if ($manualApproval) {
            // Save local pending account and exit
            $data = [
                'group' => $group,
                'leverage' => $request->leverage,
                'master_password' => $password,
                'investor_password' => $schema->is_update_investor_password ? $investorPasswordInput : null,
            ];
            $this->saveAccount($request, $schema, 0, $accountType, $user, $data, $server);
            // Email notification: Pending approval
            $pendingShortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[plan_name]]' => $schema->title,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];
            try { $this->mailNotify($user->email, 'user_forex_account_pending', $pendingShortcodes); } catch (\Exception $e) { /* ignore if template missing */ }

            ActivityLogService::log('forex_account_create', "Account request submitted for approval", [
                'Account Type' => $accountType,
                'Login' => $login,
                'Group' => $group,
                'Server' => $server,
                'Schema' => $schema->title,
            ]);
            notify()->success(__('Your account request has been submitted for approval.'), 'success');
            return redirect()->route('user.forex-account-logs');
        }

        if ($traderType == TraderType::MT5) {
            $data = [
                "login" => $login,
                "group" => $group,
                "firstName" => $user->first_name,
                "middleName" => "",
                "lastName" => $user->last_name,
                "leverage" => $request->leverage,
                "rights" => "USER_RIGHT_ALL",
                "country" => $user->country,
                "city" => $user->city,
                "state" => "",
                "zipCode" => $user->zip_code,
                "address" => $user->address,
                "phone" => $phone,
                "email" => $user->email,
                "agent" => 0,
                "account" => "",
                "company" => setting('site_title', 'global'),
                "language" => 0,
                "phonePassword" => 'SNNH@2024@bol',
                "status" => "RE",
                "masterPassword" => $password,
                "investorPassword" => $schema->is_update_investor_password ? $investorPasswordInput : 'SNNH@2024@bol'
            ];

            $retryCount = 0;
            $maxRetries = 3;
            $success = false;

            while ($retryCount < $maxRetries) {
                if ($accountType == 'real') {
                    $response = $this->forexApiService->createUser($data);
                } else {
                    $response = $this->forexApiService->createUserDemo($data);
                }

                if ($response['success']) {
                    $success = true;
                    break;
                }

                // Increment login and retry
                $login++;
                $data['login'] = $login;
                $retryCount++;
            }

            if ($success) {
                $resResult = $response['result'];
                $mt5Login = $resResult['login'];
                if ($this->isAccountAlreadyExists(TraderType::MT5, $mt5Login)) {
                    ActivityLogService::log('forex_account_create', "Account creation failed due to account already exists", [
                        'Account Type' => $accountType,
                        'Login' => $mt5Login,
                        'Group' => $group,
                        'Server' => $server,
                        'Schema' => $schema->title,
                    ]);
                    notify()->error(__('An account with the same login already exists in our records. Please contact support for assistance.'), __('Error'));
                    return redirect()->back();
                }
                if ($mt5Login && $resResult['responseCode'] == 0) {
                    $rightData = [
                        "login" => $mt5Login,
                        "rights" => 'USER_RIGHT_ENABLED',
                    ];
                    $this->forexApiService->setUserRights($rightData);

                    // Save account in DB
                    $this->saveAccount($request, $schema, $mt5Login, $accountType, $user, $data, $server);
                    $this->sendNotification($user,$mt5Login,$password,$schema,$server, $schema->is_update_investor_password ? $investorPasswordInput : 'Inv@Pass1!');
                    if ($accountType == 'demo') {
                        $demoAmount = $request->filled('demo_deposit_amount') ? (float) $request->demo_deposit_amount : (float) ($schema->demo_deposit_amount ?? 0);
                        if ($demoAmount > 0) {
                            $this->forexApiService->balanceOperationDemo([
                                'login' => $mt5Login,
                                'Amount' => $demoAmount,
                                'type' => 1, // deposit
                                'TransactionComments' => 'auto/demo/deposit/'.time()
                            ]);
                        }
                    }
                    ActivityLogService::log('forex_account_create', "Account created successfully", [
                        'Account Type' => $accountType,
                        'Login' => $mt5Login,
                        'Group' => $group,
                        'Server' => $server,
                        'Schema' => $schema->title,
                    ]);
                    notify()->success(__('Successfully Created Account'), 'success');
                    return redirect()->route('user.forex-account-logs');
                }
            }
        } elseif($traderType == TraderType::X9) {
            $data = [
                "preferred_login" => 'default',
                "client_id" => null,
                "client_group_type_id" => $accountType == 'real' ? 2 : 1,
                "client_group_id" => (int)$group,
                "first_name" => $user->first_name,
                "middle_name" => null,
                "last_name" => $user->last_name,
//            "leverage" => $invest->leverage,

                "country_id" => 5,
//            "city" => $invest->user->city,
//            "state" => "",
//            "zipCode" => $invest->user->zip_code,
//            "address" => $invest->user->address,
                "phone" => $user->phone,
                "email" => $user->email,
//            "agent" => 0,
                "company" => setting('site_title', 'global'),
                "master_password" => $password,
                "investor_password" => $schema->is_update_investor_password ? $investorPasswordInput : 'SNNH@2024@bol'
            ];
//        dd($data);
            $forexApiService = new x9ApiService();
            $response = $forexApiService->createUser($data);
//        dd($response);
            if ($response['success']) {
                $resResult = $response['result']['trading_account'];
                $mt5Login = $resResult['account_number'];

                if ($this->isAccountAlreadyExists(TraderType::X9,$mt5Login)) {
                    ActivityLogService::log('forex_account_create', "Account creation failed due to account already exists", [
                        'Account Type' => $accountType,
                        'Login' => $mt5Login,
                        'Group' => $group,
                        'Server' => $server,
                        'Schema' => $schema->title,
                    ]);
                    notify()->error(__('An account with the same login already exists in our records. Please contact support for assistance.'), __('Error'));
                    return redirect()->back();
                }
                $data['group'] = (int)$group;
                $data['leverage'] = $request->leverage;
                // Prevent duplicate account creation

                //save account in DB
                $this->saveAccount($request, $schema,$mt5Login,$accountType,$user,$data,$server);

                $this->sendNotification($user,$mt5Login,$password,$schema,$server, $schema->is_update_investor_password ? $investorPasswordInput : 'Inv@Pass1!');
                if ($accountType == 'demo') {
                    $demoAmount = $request->filled('demo_deposit_amount') ? (float) $request->demo_deposit_amount : (float) ($schema->demo_deposit_amount ?? 0);
                    if ($demoAmount > 0) {
                        (new x9ApiService())->balanceOperationDemo([
                            'login' => $mt5Login,
                            'Amount' => $demoAmount,
                            'type' => 1, // deposit
                            'TransactionComments' => 'auto/demo/deposit/'.time()
                        ]);
                    }
                }
                ActivityLogService::log('forex_account_create', "Account created successfully", [
                    'Account Type' => $accountType,
                    'Login' => $mt5Login,
                    'Group' => $group,
                    'Server' => $server,
                    'Schema' => $schema->title,
                ]);
                notify()->success(__('Successfully Created Account'), 'success');
                return redirect()->route('user.forex-account-logs');
            }
        }
        ActivityLogService::log('forex_account_create', "Account creation failed due to unknown error", [
            'Account Type' => $accountType,
            'Group' => $group,
            'Server' => $server,
            'Schema' => $schema->title,
        ]);
        notify()->error(__('Some error occurred! please try again'), __('Error'));
        return redirect()->route('user.schema.preview', the_hash($schema->id));

        return redirect()->route('user.forex-account-logs');
    }

    public function saveAccount($request, $schema, $mt5Login, $accountType, $user, $data, $server)
    {
        return DB::transaction(function () use ($request, $schema, $mt5Login, $accountType, $user, $data, $server) {
            $exists = ForexAccount::where('login', $mt5Login)
                ->where('trader_type', $schema->trader_type)
                ->exists();

            if ($exists) {
                throw new \Exception("Duplicate account creation attempt: login already exists.");
            }

            $accountData = $request->all();
            $accountData['forex_schema_id'] = $schema->id;
            $accountData['login'] = $mt5Login;
            $accountData['account_name'] = $request->account_name;
            $accountData['account_type'] = $accountType;
            $accountData['user_id'] = $user->id;
            $accountData['currency'] = setting('site_currency', 'global');
            $accountData['group'] = $data['group'];
            $accountData['leverage'] = $data['leverage'];
            $status = ForexAccountStatus::Ongoing;
            
            // Check if admin approval is enabled for this account type
            $adminApprovalEnabled = ($accountType === 'real' && setting('live_account_creation', 'features')) || ($accountType === 'demo' && setting('demo_account_creation', 'features'));
            
            // If admin approval is enabled, check account limit
            if ($adminApprovalEnabled) {
                $accountLimit = ($accountType === 'real') ? ($schema->live_account_limit ?? 0) : ($schema->demo_account_limit ?? 0);
                
                // Only check limit if limit is set (greater than 0, 0 means unlimited)
                if ($accountLimit > 0) {
                    $userAccountCount = ForexAccount::where('user_id', $user->id)
                        ->where('forex_schema_id', $schema->id)
                        ->where('account_type', $accountType)
                        ->traderType()
                        ->whereIn('status', [ForexAccountStatus::Ongoing, ForexAccountStatus::Pending])
                        ->count();
                    
                    // If user has reached or exceeded the limit, set status to pending
                    if ($userAccountCount >= $accountLimit) {
                        $status = ForexAccountStatus::Pending;
                    }
                    // If limit not reached, status remains Ongoing (auto-approved)
                }
                // If limit is 0 (unlimited), status remains Ongoing (auto-approved)
            }
            
            $accountData['status'] = $status;
            $accountData['server'] = $server;
            $accountData['created_by'] = $user->id;
            $accountData['first_min_deposit_paid'] = 0;
            $accountData['trader_type'] = $schema->trader_type;
            $accountData['trading_platform'] = $schema->trader_type;
            $meta = [];
            if (isset($data['master_password'])) { $meta['master_password'] = $data['master_password']; }
            if (isset($data['investor_password']) && $data['investor_password']) { $meta['investor_password'] = $data['investor_password']; }
            if (!empty($meta)) { $accountData['meta'] = json_encode($meta); }

            return ForexAccount::create($accountData);
        });
    }

    protected function isAccountAlreadyExists($traderType,$login)
    {
        return ForexAccount::where('trader_type' , $traderType)->where('login' , $login)->exists();
    }

    public function sendNotification($user,$mt5Login,$password,$schema,$server, $investorPassword = null)
    {
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[login]]' => $mt5Login,
            '[[password]]' => $password,
            '[[investor_password]]' => $investorPassword,
            '[[plan_name]]' => $schema->title,
            '[[server]]' => $server,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];
//
        $this->mailNotify($user->email, 'user_forex_account_creation', $shortcodes);
//                $this->pushNotify('user_investment', $shortcodes, route('user.forex-account-logs'), $tnxInfo->user->id);
//                $this->smsNotify('user_investment', $shortcodes, $tnxInfo->user->phone);
    }
    public function getServe($request,$schema)

    {

        $server = '';
        if($schema->trader_type == TraderType::MT5) {
            if ($request->account_type === 'real') {
                $server = setting('live_server', 'platform_api');
            } elseif ($request->account_type === 'demo') {
                $server = setting('demo_server', 'platform_api');
            }
        }elseif($schema->trader_type == TraderType::X9) {
            if ($request->account_type === 'real') {
                $server = setting('x9_name', 'x9_api');
            } elseif ($request->account_type === 'demo') {
                $server = setting('x9_name', 'x9_api');
            }
        }

        return $server;
    }
    public function getGroup($user,$request, $schema)
    {
        $group = '';
        if ($request->account_type === 'real') {
            $referral = $user->referralRelationship;
            if($referral && isset($referral->multi_level_id)){
                $group = $referral->multiLevel->group_tag;
            }else {
                $group = $request->is_islamic ? $schema->real_islamic : $schema->real_swap_free;
            }
        } elseif ($request->account_type === 'demo') {
            $group = $request->is_islamic ? $schema->demo_islamic : $schema->demo_swap_free;
        }
        return $group;
    }
    public function userAccountExist($account)
    {
//        dd($account);
        $forexAccount = ForexAccount::where('login', $account)->where('status', ForexAccountStatus::Ongoing)->first();

        if ($forexAccount) {
            $data =  __('Name') . $forexAccount->user->first_name . ' ' . $forexAccount->user->last_name;
        } else {
            $data = __('Account Not Found');
        }

        return $data;
    }
    public function forexAccountLogs(Request $request)
    {

        $clientIp = request()->ip();
        if (!in_array($clientIp, ['127.0.0.1', '::1'])) {
//            sync_forex_accounts(auth()->id());
        }
        $realForexAccounts = ForexAccount::where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->traderType()
            ->whereIn('status', [ForexAccountStatus::Ongoing, ForexAccountStatus::Pending, ForexAccountStatus::Canceled])
            ->orderBy('balance', 'desc')
            ->get();
        $demoForexAccounts = ForexAccount::where('user_id', auth()->id())
            ->where('account_type', 'demo')
            ->traderType()
            ->whereIn('status', [ForexAccountStatus::Ongoing, ForexAccountStatus::Pending, ForexAccountStatus::Canceled])
            ->orderBy('balance', 'desc')
            ->get();
        $archiveForexAccounts = ForexAccount::archiveAccount()->traderType()
            ->orderBy('balance', 'desc')
            ->get();

        $activePlatform = setting('active_trader_type', 'features');
        $platformLinks = PlatformLink::where('platform', $activePlatform)->where('status', 1)->get();

        return view('frontend::user.forex.log', compact('realForexAccounts', 'demoForexAccounts', 'archiveForexAccounts', 'platformLinks'));
    }
    public function testForexAccount(Request $request, $account = null)
    {
        // Use the account parameter from URL if provided, otherwise use default
        $login = $account ? $account : 601055;
        
        $data = [
            'login' => $login
        ];
        $response = $this->forexApiService->getBalance($data);
        dd($response);

            $reportFlag = 0;//(0 for all, 1 for buy, 2 for sell)
            $timeInSeconds = 360;
            $from = '25/10/24';
            $to = '28/10/24';


        $response = $this->forexApiService->fastDeals($reportFlag,$timeInSeconds,$from,$to);

        dd($response);
        dd($this->getUserInfoApi(88876));
//        $this->getPositionList(9996792);
//        $this->getPositionListGroup(9996792);
//        $this->getOrderOpenUser(9996792);
//        $this->getDealListUser(9997821);
//        $this->getUserAccountBalance(9996792);
//        $this->dealerCreditUrl(9996792,1,2);

//        $clientIp = request()->ip();
//        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
//            $this->syncForexAccounts(auth()->id());
//        }
        $realForexAccounts = ForexAccount::realActiveAccount()->traderType()
            ->orderBy('balance', 'desc')
            ->get();
        $demoForexAccounts = ForexAccount::demoActiveAccount()->traderType()
            ->orderBy('balance', 'desc')
            ->get();
        $archiveForexAccounts = ForexAccount::archiveAccount()->traderType()
            ->orderBy('balance', 'desc')
            ->get();

        return view('frontend::user.forex.log', compact('realForexAccounts', 'demoForexAccounts', 'archiveForexAccounts'));
    }

    public function getLeverage(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'id' => 'required',
        ]);
        $forexTrading = ForexAccount::find($request->id);
//        dd($forexTrading);

        return view('frontend::user.forex.modal.__change_leverage_render', compact('forexTrading'))->render();

    }

    public function updateAccountInfo(Request $request)
    {
//        dd($request->all());
        $request->validate([
            'login' => ['required', 'integer', new ForexLoginBelongsToUserGeneral],
            'leverage' => 'sometimes|nullable|numeric|gt:0',
//            'password' => 'sometimes|nullable|'.Password::min(8)->mixedCase(),
            'main_password' => ['sometimes',
                'min:8',     // Minimum length requirement
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%&*():{}|<>])[A-Za-z\d!@#$%&*():{}|<>]+$/',

            ],
            'invest_password' => ['sometimes',
                'min:8',     // Minimum length requirement
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%&*():{}|<>])[A-Za-z\d!@#$%&*():{}|<>]+$/',
            ],
        ]);
//        dd('s');
        $updateUserUrl = config('forextrading.updateUserUrl');

        $dataArray = [];
        $dataArray['Login'] = $request->login;

        if ($request->leverage) {
            $forexAccount = ForexAccount::where('login',$request->login)->first();
            if($forexAccount->leverage == $request->leverage){
                ActivityLogService::log('forex_account_update', "Leverage update failed due to same leverage", [
                    'Login' => $request->login,
                    'Leverage' => $request->leverage,
                ]);
                return response()->json(['error' => __('Kindly provide a different leverage! The leverage :leverage has already been assigned.',['leverage'=>$request->leverage]), 'reload' => false]);

            }
            if(!$forexAccount) {
                ActivityLogService::log('forex_account_update', "Leverage update failed due to account not found", [
                    'Login' => $request->login,
                ]);
                return response()->json(['error' => __('Kindly provide valid forex account and try again!'), 'reload' => false]);
            }
            $data = [
                'last_leverage' => $forexAccount->leverage,
                'updated_leverage' => $request->leverage,
            ];
//          dd(setting('leverage_approval','features'));
            if(setting('leverage_approval','features')  == 'by_admin') {
                LeverageUpdate::updateOrCreate(['user_id' => auth()->user()->id,
                    'forex_account_id' => $forexAccount->id], $data);
                $mailType   = 'user_pending_leverage';
                $this->leverageMailNotify($request,$mailType);
                ActivityLogService::log('forex_account_update', "Leverage update request submitted for approval", [
                    'Login' => $request->login,
                    'Leverage' => $request->leverage,
                ]);
                return response()->json(['success' => __('Leverage update request successfully submitted. An admin will review and process it shortly.'), 'reload' => true]);
            }else{
                // Prepare data for the API call
                $data = [
                    'login' => $forexAccount->login,
                    'leverageAmount' => $request->leverage,
                ];

                // Call the API to update leverage
                $response = $this->forexApiService->setUserLeverage($data);
                ActivityLogService::log('forex_account_update', "Leverage updated successfully", [
                    'Login' => $request->login,
                    'Leverage' => $request->leverage,
                ]);
                // Update leverage in ForexAccount model

                $forexAccount->leverage = $request->leverage;
                $forexAccount->save();

                $mailType   = 'user_approved_leverage';
                $this->leverageMailNotify($request,$mailType);

                // Send email notification
                return response()->json(['success' => __('Leverage Update Approved and Updated Successfully!.'), 'reload' => true]);

                $message = 'Leverage Update Approved and Updated Successfully!';
            }
//                ForexAccount::where('login', $request->login)->update(['leverage' => $request->leverage]);
        }

        if ($request->name) {
            ForexAccount::where('login', $request->login)->update(['account_name' => $request->name]);
            ActivityLogService::log('forex_account_update', "Account name updated successfully", [
                'Login' => $request->login,
                'Account Name' => $request->name,
            ]);
            return response()->json(['success' => __('Successfully updated your account name.'), 'reload' => true]);

        }
        if ($request->main_password) {
            $dataArray['MainPassword'] = $request->main_password;
//            $updateUserApiResponse = $this->updateMainPassword($request->login, $request->main_password);
//        dd($updateUserApiResponse->object());
            $data = [
                'login' => $request->login,
                'password' => $request->main_password,
            ];
            $updateUserApiResponse = $this->forexApiService->resetMasterPassword($data);
            if ($updateUserApiResponse['success']) {
                $shortcodes = [
                    '[[full_name]]' => auth()->user()->full_name,
                    '[[login]]' => $request->login,
                    '[[password]]' =>  $request->main_password,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ];
//
                $this->mailNotify(auth()->user()->email, 'user_update_master_password', $shortcodes);
                ActivityLogService::log('forex_account_update', "Password updated successfully", [
                    'Login' => $request->login,
                    'Password' => $request->main_password,
                ]);
                return response()->json(['success' => __('Successfully updated Password.'), 'reload' => false]);
            } else {
                return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again.'), 'reload' => false]);
            }
        }
        if ($request->invest_password) {
//            $updateUserApiResponse = $this->updateInvestorPassword($request->login, $request->invest_password);
//        dd($updateUserApiResponse->object());

            $data = [
                'login' => $request->login,
                'password' => $request->invest_password,
            ];
            $updateUserApiResponse = $this->forexApiService->resetInvestorPassword($data);
            if ($updateUserApiResponse['success']) {
                $shortcodes = [
                    '[[full_name]]' => auth()->user()->full_name,
                    '[[login]]' => $request->login,
                    '[[password]]' =>  $request->invest_password,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ];
//
                $this->mailNotify(auth()->user()->email, 'user_update_investor_password', $shortcodes);
                ActivityLogService::log('forex_account_update', "Investor password updated successfully", [
                    'Login' => $request->login,
                    'Investor Password' => $request->invest_password,
                ]);
                return response()->json(['success' => __('Successfully updated Password.'), 'reload' => false]);
            } else {
                return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again.'), 'reload' => false]);
            }
//            $dataArray['InvestPassword'] = $request->password;
        }
        if ($request->archive) {
//            $updateUserApiResponse = $this->disableAccount($request->login);
//        dd($updateUserApiResponse->object());
//            if (($updateUserApiResponse ? $updateUserApiResponse->status() == 200 && isset($updateUserApiResponse->object()->data->Login) : false)) {
            ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Archive]);
            ActivityLogService::log('forex_account_update', "Account archived successfully", [
                'Login' => $request->login,
            ]);
            $shortcodes = [
                '[[full_name]]' => auth()->user()->full_name,
                '[[login]]' => $request->login,
                '[[password]]' =>  $request->invest_password,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];
//
            $this->mailNotify(auth()->user()->email, 'user_update_investor_password', $shortcodes);
            return response()->json(['success' => __('Successfully archived your account.'), 'reload' => true]);
//            } else {
//                notify()->error('Opps! We unable to process your request. Please reload the page and try again.', 'Error');
//            }
        }
        if ($request->reactive) {
//            dd($request->all());
//            $updateUserApiResponse = $this->enableAccount($request->login);
////        dd($updateUserApiResponse->object());
//            if (($updateUserApiResponse ? $updateUserApiResponse->status() == 200 && isset($updateUserApiResponse->object()->data->Login) : false)) {
            ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Ongoing]);
            ActivityLogService::log('forex_account_update', "Account reactivated successfully", [
                'Login' => $request->login,
            ]);
            return response()->json(['success' => __('Successfully reactive your account.'), 'reload' => true]);
//            } else {
//                return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again'), 'reload' => false]);
//            }
        }
//        $dataArray['Email'] = 'sufyan@gmail.com';
//        $dataArray['status'] = 'RE';
//        $updateUserApiResponse = $this->sendApiPostRequest($updateUserUrl, $dataArray);
////        dd($updateUserApiResponse->object()->data);
//        if (($updateUserApiResponse->status() == 200 && $updateUserApiResponse->object() == 0)) {
////            $getUserResponse = $this->getUserApi($request->login);
////            if ($getUserResponse->status() == 200) {
////                $this->updateUserAccount($getUserResponse);
////            }
//            return response()->json(['success' => __('Successfully updated.'), 'reload' => true]);
//        } else {
//            notify()->error('Opps! We unable to process your request. Please reload the page and try again.', 'Error');
//
//        }

    }

    public function leverageMailNotify($request,$mailType)
    {
         $shortcodes = [
                        '[[full_name]]' => auth()->user()->full_name,
                        '[[login]]' => $request->login,
                        '[[leverage]]' =>  $request->leverage,
                        '[[site_title]]' => setting('site_title', 'global'),
                        '[[site_url]]' => route('home'),

         ];
         $this->mailNotify(auth()->user()->email, $mailType, $shortcodes);
    }
    public function getAccount($login)
    {
//        dd($login);
        $resposne = $this->getUserApi($login);
//        $resposne = $this->getUserInfoApi($login);
//        $resposne = $this->getMT5GroupList();
//        $resposne = $this->getRoiApi($login);
//        dd($resposne->object());

    }

    public function investCancel(Request $request)
    {
//        dd($login);
//        $resposne = $this->getUserInfoApi($login);
//        dd($resposne->object());

    }
}
