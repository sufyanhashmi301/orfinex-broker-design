<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Enums\IBStatus;
use App\Enums\TraderType;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Exports\RealAccountExport;
use App\Exports\DemoAcoountExport;
use App\Models\LeverageUpdate;
use App\Models\ForexSchema;
use App\Models\Invest;
use App\Models\User;
use App\Services\ForexApiService;
use App\Services\AdminForexAccountApprovalService;
use App\Rules\ForexLoginBelongsToUserGeneral;
use App\Traits\NotifyTrait;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class   AccountsController extends Controller
{
    use NotifyTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
        $this->middleware('permission:accounts-list');

    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
   public function forexAccounts(Request $request, $type = 'real', $id = null)
{
    $loggedInUser = auth()->user(); // Get the logged-in user

    // Get accessible user IDs using the helper (includes branch filtering)
    $accessibleUserIds = getAccessibleUserIds()->pluck('id')->toArray();

    // Start Forex Account query
    $data = ForexAccount::query()
        ->with('schema')
        ->where('account_type', $type);

    // Apply user filtering based on accessible users
    if (!empty($accessibleUserIds)) {
        $data->whereIn('user_id', $accessibleUserIds);
    } elseif (!$loggedInUser->hasRole('Super-Admin')) {
        // If no accessible users and not Super-Admin, show no results
        $data->where('user_id', -1);
    }

    // Apply individual user filter (if ID passed)
    if ($id) {
        $data->where('user_id', $id);
    }

    // Apply additional filters
    $filters = $request->only(['global_search', 'login', 'country', 'status', 'created_at', 'tag']);
    $data->applyFilters($filters);

    // Return Datatables if Ajax
    if ($request->ajax()) {
        // Prepare sortable computed columns
        $data = $data->select('forex_accounts.*')
            ->selectSub(
                DB::table('users')
                    ->whereColumn('users.id', 'forex_accounts.user_id')
                    ->selectRaw("MIN(CONCAT(users.first_name, ' ', users.last_name))"),
                'username_sort'
            )
            ->selectSub(
                DB::table('users')
                    ->whereColumn('users.id', 'forex_accounts.user_id')
                    ->selectRaw('MIN(users.ib_login)'),
                'ib_login_sort'
            )
                ->selectSub(
                    DB::table('users')
                        ->whereColumn('users.id', 'forex_accounts.user_id')
                        ->selectRaw('MIN(users.email)'),
                    'user_email_sort'
                )
            ->selectSub(
                DB::table('forex_schemas')
                    ->whereColumn('forex_schemas.id', 'forex_accounts.forex_schema_id')
                    ->selectRaw('MIN(forex_schemas.title)'),
                'schema_title_sort'
            );

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('ib_number', 'backend.user.include.__ib_number')
            ->addColumn('username', 'backend.transaction.include.__user')
            ->addColumn('balance', function($account) {
                return view('backend.investment.include.__balance_mt5', ['account' => $account])->render();
            })
            ->addColumn('equity', 'backend.investment.include.__equity_mt5')
            ->addColumn('credit', 'backend.investment.include.__credit_mt5')
            ->addColumn('schema', function($account) {
                // Return the rendered HTML directly
                return view('backend.investment.include.__invest_schema', ['account' => $account])->render();
            })
            ->addColumn('status', 'backend.investment.include.__status')
            ->addColumn('action', 'backend.investment.include.__action')
            // Server-side ordering mappings
            ->orderColumn('login', 'CAST(forex_accounts.login AS UNSIGNED) $1')
            ->orderColumn('username', 'username_sort $1')
            ->orderColumn('schema', 'schema_title_sort $1')
            ->orderColumn('group', 'forex_accounts.group $1')
            ->orderColumn('currency', 'forex_accounts.currency $1')
            ->orderColumn('leverage', 'forex_accounts.leverage $1')
            ->orderColumn('balance', 'forex_accounts.balance $1')
            ->orderColumn('ib_number', 'ib_login_sort $1')
            ->orderColumn('status', 'forex_accounts.status $1')
            ->orderColumn('created_at', 'forex_accounts.created_at $1')
            ->rawColumns(['ib_number', 'schema', 'username', 'balance', 'equity', 'credit', 'status', 'action'])
            ->make(true);
    }

    // Gather login IDs for MT5 DB balance stats
    $realForexAccounts = $data->pluck('login');

    $withBalance = 0.0;
    $withoutBalance = 0.0;

    try {
        if ($realForexAccounts->isNotEmpty()) {
            $withBalance = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', '>', 0)
                ->count();
        }
    } catch (\Exception $e) {
        \Log::error('MT5 DB connection failed when retrieving account: ' . $e->getMessage());
    }

    try {
        if ($realForexAccounts->isNotEmpty()) {
            $withoutBalance = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', '<=', 0)
                ->count();
        }
    } catch (\Exception $e) {
        \Log::error('MT5 DB connection failed when retrieving account: ' . $e->getMessage());
    }

    // Count inactive accounts
    $unActiveAccountsQuery = ForexAccount::where('account_type', $type)
        ->where('status', '!=', ForexAccountStatus::Ongoing);

    // Apply user filtering for inactive accounts as well
    if (!empty($accessibleUserIds)) {
        $unActiveAccountsQuery->whereIn('user_id', $accessibleUserIds);
    } elseif (!$loggedInUser->hasRole('Super-Admin')) {
        // If no accessible users and not Super-Admin, show no results
        $unActiveAccountsQuery->where('user_id', -1);
    }

    $unActiveAccounts = $unActiveAccountsQuery->count();

    // Return to view
    $data = [
        'TotalAccounts' => $data->count(),
        'withBalance' => $withBalance,
        'withoutBalance' => $withoutBalance,
        'unActiveAccounts' => $unActiveAccounts,
    ];

    $status = $request->get('status');
    return view('backend.investment.index', compact('data', 'type', 'status'));
}


    public function export(Request $request, $type)
    {
        switch ($type) {
            case 'real':
                return Excel::download(new RealAccountExport($request), 'real-accounts.xlsx');
            default:
                return Excel::download(new DemoAcoountExport($request), 'demo-accounts.xlsx');
        }
    }

    public function forexAccountMap(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_number' => 'required|integer',
            'schema_id' => 'required',
            'account_type' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, ['real', 'demo'])) {
                        $fail('The ' . $attribute . ' must be either real or demo.');
                    }
                },
            ],
            'is_islamic' => [
                function ($attribute, $value, $fail) use ($request) {
                    $schema = ForexSchema::find($request->schema_id);
                    if ($request->account_type === 'real' && $value == 1 && !$schema->is_real_islamic) {
                        $fail('The selected schema does not support Islamic account for Real account type.');
                    }
                    if ($request->account_type === 'demo' && $value == 1 && !$schema->is_demo_islamic) {
                        $fail('The selected schema does not support Islamic account for Demo account type.');
                    }
                },
            ],
            'leverage' => 'required',
            'account_name' => 'required',
        ], [
            'account_type.required' => 'The account type is required.',
            'leverage.not_regex' => __('Kindly select a valid leverage.'),
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back();
        }

        $accountNumber = $request->account_number;
        $user = User::find($request->user_id);
        $schema = ForexSchema::find($request->schema_id);
        $accountType = $request->account_type;

        // Check if account number already exists in ForexAccount table
        if (ForexAccount::where('login', $accountNumber)->exists()) {
            notify()->error(__('Account number already exists.'), 'Error');
            return redirect()->back();
        }

        // Check if account number exists in MT5 database
        try {
            $response = $this->forexApiService->getUserByLogin([
                'login' => $accountNumber
            ]);
            if (!$response['success']) {
                notify()->error(__("Account number {$accountNumber} is not exist in MT5.Kindly provide a valid account"), 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            \Log::error('MT5 DB connection failed when checking account on mapping: ' . $e->getMessage());
            notify()->error(__('Failed to check account in MT5.'), 'Error');
            return redirect()->back();
        }

        $group = $this->getGroup($user, $request, $schema);
        $server = $this->getServe($request, $schema);

        $data = [
            "login" => $accountNumber,
            "group" => $group,
            "leverage" => $request->leverage,
        ];

        $this->saveAccount($request, $schema, $accountNumber, $accountType, $user, $data, $server);

        notify()->success(__('Successfully Mapped Account'), 'success');
        return redirect()->back();
    }


    public
    function forexAccountCreateNow(Request $request)
    {

//        dd($request->all());
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
                        $fail('The ' . $attribute . ' must be either real or demo.');
                    }
                },
            ],
            'is_islamic' => [
                function ($attribute, $value, $fail) use ($request) {
                    $schema = ForexSchema::find($request->schema_id);
                    if ($request->account_type === 'real' && $value == 1 && !$schema->is_real_islamic) {
                        $fail('The selected schema does not support Islamic account for Real account type.');
                    }
                    if ($request->account_type === 'demo' && $value == 1 && !$schema->is_demo_islamic) {
                        $fail('The selected schema does not support Islamic account for Demo account type.');
                    }

                },
            ],
//            'group' => 'required',
            'leverage' => 'required',
            'account_name' => 'required',
        ], [
            'main_password.required' => __('The main password field is required.'),
            'main_password.min' => __('The main password must be at least 8 characters long.'),
            'main_password.max' => __('The main password must not exceed 20 characters.'),
            'main_password.regex' => __('The main password must be 8–20 characters long, contain at least one lowercase letter, one uppercase letter, one digit, and one special character from the following: ! @ # $ % & * ( ) : { } | < >'),
            'account_type.required' => 'The account type is required.',
            'leverage.not_regex' => __('Kindly select a valid leverage.'),
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }
        $input = $request->all();
        $user = User::find($request->user_id);
        $schema = ForexSchema::find($input['schema_id']);
        $accountType = $request->account_type;
//        dd(ForexAccount::where(['user_id'=>$user->id, 'forex_schema_id'=>$schema->id, 'account_type'=>$accountType])->count(),$accountType,$schema->account_limit);
//        if (ForexAccount::where(['user_id'=> $user->id, 'forex_schema_id'=>$schema->id, 'account_type'=>$accountType])->count() >= $schema->account_limit) {
//            $message = __('Sorry, You have achieved your account creation limit of :title type . Please choose different type or contact support to increase your account limit.',['title'=> $schema->title]);
//            notify()->error($message, 'Error');
//            return redirect()->back();
//        }
        $login = 0;
        $forexAccount = ForexAccount::where('forex_schema_id', $schema->id)
            ->orderBy(DB::raw('CAST(login AS UNSIGNED)'), 'desc')
            ->first();

        if (setting('is_forex_group_range', 'global')) {
//            $forexAccount = ForexAccount::where('forex_schema_id', $schema->id)->orderBy('login', 'desc')->first();
            $forexAccount = ForexAccount::where('forex_schema_id', $schema->id)
                ->orderBy(DB::raw('CAST(login AS UNSIGNED)'), 'desc')
                ->first();
            // Check if an account exists
            if ($forexAccount) {
                // Validate if the login is within the range
                if ($forexAccount->login < $schema->start_range || $forexAccount->login >= $schema->end_range) {
                    // Reset to start_range if the login is out of range
                    $login = $schema->start_range;
                } else {
                    // Increment login if within range
                    $login = ++$forexAccount->login;
                }
            } else {
                // Start from start_range if no accounts exist
                $login = $schema->start_range;
            }
        }
        $group = '';
        if ($request->account_type === 'real') {
            $group = $request->is_islamic ? $schema->real_islamic : $schema->real_swap_free;
        } elseif ($request->account_type === 'demo') {
            $group = $request->is_islamic ? $schema->demo_islamic : $schema->demo_swap_free;
        }
        $server = $this->getServe($request, $schema);
        $group = $this->getGroup($user, $request, $schema);
        $password = $request->main_password;
        if ($user->phone) {
            $phone = $user->phone;
        } else {
            $phone = '+91';
        }

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
            "investorPassword" => 'SNNH@2024@bol'
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

            if ($mt5Login && $resResult['responseCode'] == 0) {
                $rightData = [
                    "login" => $mt5Login,
                    "rights" => 'USER_RIGHT_ENABLED',
                ];
                $this->forexApiService->setUserRights($rightData);

                // Save account in DB
                $this->saveAccount($request, $schema, $mt5Login, $accountType, $user, $data, $server);
                $this->sendNotification($user, $mt5Login, $password, $schema, $server);

                notify()->success(__('Successfully Created Account'), 'success');
                return redirect()->back();
            }
        }

        notify()->error('Some error occurred! please try again', 'Error');
        return redirect()->back();
    }

    public
    function getServe($request, $schema)

    {

        $server = '';
        if ($schema->trader_type == TraderType::MT5) {
            if ($request->account_type === 'real') {
                $server = setting('live_server', 'platform_api');
            } elseif ($request->account_type === 'demo') {
                $server = setting('demo_server', 'platform_api');
            }
        } elseif ($schema->trader_type == TraderType::X9) {
            if ($request->account_type === 'real') {
                $server = setting('x9_name', 'x9_api');
            } elseif ($request->account_type === 'demo') {
                $server = setting('x9_name', 'x9_api');
            }
        }

        return $server;
    }

    public
    function getGroup($user, $request, $schema)
    {
        $group = '';
        if ($request->account_type === 'real') {
            $referral = $user->referralRelationship;
            if ($referral && isset($referral->multi_level_id)) {
                $group = $referral->multiLevel->group_tag;
            } else {
                $group = $request->is_islamic ? $schema->real_islamic : $schema->real_swap_free;
            }
        } elseif ($request->account_type === 'demo') {
            $group = $request->is_islamic ? $schema->demo_islamic : $schema->demo_swap_free;
        }
        return $group;
    }

    public
    function saveAccount($request, $schema, $mt5Login, $accountType, $user, $data, $server)
    {
        $accountData = $request->all();

        $accountData['forex_schema_id'] = $schema->id;
        $accountData['login'] = $mt5Login;
        $accountData['account_name'] = $request->account_name;
        $accountData['account_type'] = $accountType;
        $accountData['user_id'] = $user->id;
        $accountData['currency'] = setting('site_currency', 'global');
        $accountData['group'] = $data['group'];
        $accountData['leverage'] = $data['leverage'];
        $accountData['status'] = ForexAccountStatus::Ongoing;
        $accountData['server'] = $server;
        $accountData['created_by'] = $user->id;
        $accountData['first_min_deposit_paid'] = 0;
        $accountData['trader_type'] = $schema->trader_type;
        $accountData['trading_platform'] = $schema->trader_type;
        $forexTrading = ForexAccount::create($accountData);

        return true;
    }

    public
    function sendNotification($user, $mt5Login, $password, $schema, $server)
    {
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[login]]' => $mt5Login,
            '[[password]]' => $password,
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

    public
    function getSchema(Request $request)
    {
        // Retrieve the login value from the request
        $login = $request->input('login');

        // Fetch the ForexAccount record using the provided login value
        $forexTrading = ForexAccount::where('login', $login)->firstOrFail();

        // Retrieve all ForexSchema records
        $schemas = ForexSchema::all();

        // Return the view with the fetched data
        return view('backend.investment.modal.__change_schema_render', compact('forexTrading', 'schemas'));
    }


    public
    function getLeverage(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $forexTrading = ForexAccount::find($request->id);

        return view('backend.investment.modal.__change_leverage_render', compact('forexTrading'))->render();

    }

    public function pendingLeverage(Request $request)
{
    $accessibleUserIds = getAccessibleUserIds()->pluck('id');

    if ($accessibleUserIds->isNotEmpty()) {
        $leverageUpdates = LeverageUpdate::with('user', 'forexAccount')
            ->where('status', 0)
            ->whereIn('user_id', $accessibleUserIds)
            ->get();
    } else {
        // No accessible users, return empty collection
        $leverageUpdates = collect();
    }

    return view('backend.investment.leverage.pending', compact('leverageUpdates'));
}



    public
    function handlePendingLeverage(Request $request)
    {
        // Validate the request
        $request->validate([
            'action' => 'required|string',
            'id' => 'required|integer|exists:leverage_updates,id',
        ]);

        $leverageUpdate = LeverageUpdate::findOrFail($request->input('id'));
        $user = $leverageUpdate->user;

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[login]]' => $leverageUpdate->forexAccount->login,
            '[[leverage]]' => $leverageUpdate->updated_leverage,
            '[[site_title]]' => config('app.name'),
            '[[site_url]]' => route('home'),
            '[[status]]' => $request->input('action') === 'approve' ? 'approved' : 'rejected',
        ];

        if ($request->input('action') === 'approve') {
            $leverageUpdate->status = 1; // Approved
            $leverageUpdate->approved_by = Auth::id();

            // Prepare data for the API call
            $data = [
                'login' => $leverageUpdate->forexAccount->login,
                'leverageAmount' => $leverageUpdate->updated_leverage,
            ];

            try {
                // Call the API to update leverage
                $this->forexApiService->setUserLeverage($data);

                // Update leverage in ForexAccount model
                $forexAccount = $leverageUpdate->forexAccount;
                $forexAccount->leverage = $leverageUpdate->updated_leverage;
                $forexAccount->save();

                // Send email notification
                $this->mailNotify($user->email, 'user_approved_leverage', $shortcodes);

                $message = 'Leverage Update Approved and Updated Successfully!';
            } catch (\Exception $e) {
                $message = 'Leverage Update Approved but API Update Failed. Please check the API service.';
                \Log::error('Leverage API Update Failed: ' . $e->getMessage());
            }
        } elseif ($request->input('action') === 'reject') {
            $leverageUpdate->status = 2; // Rejected
            $leverageUpdate->approved_by = Auth::id();
            $this->mailNotify($user->email, 'user_rejected_leverage', $shortcodes);
            $message = 'Leverage Update Rejected Successfully!';
        }

        // Save the LeverageUpdate status
        $leverageUpdate->save();

        // Return a JSON response with the message
        return response()->json(['message' => $message]);
    }

   public function allLeverage(Request $request)
{
    $accessibleUserIds = getAccessibleUserIds()->pluck('id');

    $query = LeverageUpdate::with('user', 'forexAccount');

    if ($accessibleUserIds->isNotEmpty()) {
        $query->whereIn('user_id', $accessibleUserIds);
    } else {
        $leverageUpdates = collect([]);
        return view('backend.investment.leverage.all', compact('leverageUpdates'));
    }

    $leverageUpdates = $query->get();

    return view('backend.investment.leverage.all', compact('leverageUpdates'));
}



    public
    function handleAllLeverage(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'id' => 'required|integer|exists:leverage_updates,id',
        ]);

        $leverageUpdate = LeverageUpdate::findOrFail($request->input('id'));
        $user = $leverageUpdate->user;
        $forexAccount = $leverageUpdate->forexAccount;
        $action = $request->input('action');

        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[login]]' => $forexAccount->login,
            '[[leverage]]' => $leverageUpdate->updated_leverage,
            '[[site_title]]' => config('app.name'),
            '[[site_url]]' => route('home'),
            '[[status]]' => $action === 'approve' ? 'approved' : 'rejected',
        ];

        try {
            if ($action === 'approve' && $leverageUpdate->status !== 1) {
                $leverageUpdate->status = 1; // Approved
                $leverageUpdate->approved_by = Auth::id();
                $forexAccount->leverage = $leverageUpdate->updated_leverage;
                $this->forexApiService->setUserLeverage([
                    'login' => $forexAccount->login,
                    'leverageAmount' => $leverageUpdate->updated_leverage,
                ]);
                $this->mailNotify($user->email, 'user_approved_leverage', $shortcodes);
                $message = 'Leverage Update Approved and Applied Successfully!';
            } elseif ($action === 'reject' && $leverageUpdate->status !== 2) {
                $leverageUpdate->status = 2; // Rejected
                $leverageUpdate->approved_by = Auth::id();
                $forexAccount->leverage = $leverageUpdate->last_leverage; // Restore last leverage
                $this->forexApiService->setUserLeverage([
                    'login' => $forexAccount->login,
                    'leverageAmount' => $leverageUpdate->last_leverage,
                ]);
                $this->mailNotify($user->email, 'user_rejected_leverage', $shortcodes);
                $message = 'Leverage Update Rejected and Restored Successfully!';
            } else {
                $message = 'No change in status was made.';
            }

            $forexAccount->save();
            $leverageUpdate->save();
        } catch (\Exception $e) {
            \Log::error('Leverage API Update Failed: ' . $e->getMessage());
            $message = 'Action succeeded locally, but API Update Failed. Please check the API service.';
        }

        return response()->json(['message' => $message]);
    }

    public
    function updateAccountInfo(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'login' => ['required', 'integer'],
            'leverage' => 'sometimes|nullable|numeric|gt:0',
            'main_password' => [
                'sometimes',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%&*():{}|<>])[A-Za-z\d!@#$%&*():{}|<>]+$/',
            ],
            'invest_password' => [
                'sometimes',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%&*():{}|<>])[A-Za-z\d!@#$%&*():{}|<>]+$/',
            ],
            'forex_schema_id' => 'sometimes|exists:forex_schemas,id',
        ]);

        // Approve/Reject via status change
        if ($request->has('set_status')) {
            $request->validate([
                'login' => ['required', 'integer'],
                'set_status' => ['required', 'in:ongoing,canceled']
            ]);
            $account = ForexAccount::where('login', $request->login)->first();
            if (!$account) {
                return response()->json(['error' => __('Invalid forex account!'), 'reload' => false]);
            }

            if ($request->set_status === ForexAccountStatus::Canceled) {
                $meta = $account->meta ? (json_decode($account->meta, true) ?: []) : [];
                if ($request->filled('comment')) { $meta['last_action_comment'] = $request->comment; }
                $account->meta = json_encode($meta);
                $account->status = ForexAccountStatus::Canceled;
                $account->save();
                // email notify user rejected
                $this->mailNotify($account->user->email, 'user_forex_account_rejected', [
                    '[[full_name]]' => $account->user->full_name,
                    '[[login]]' => $account->login,
                    '[[plan_name]]' => optional($account->schema)->title,
                    '[[message]]' => $request->comment ?? '',
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ]);
                return response()->json(['success' => __('Account rejected successfully.'), 'reload' => true]);
            }

            // Approve: create account at platform then set to ongoing
            $service = app(AdminForexAccountApprovalService::class);
            // store comment, then approve
            $meta = $account->meta ? (json_decode($account->meta, true) ?: []) : [];
            if ($request->filled('comment')) { $meta['last_action_comment'] = $request->comment; }
            $account->meta = json_encode($meta);
            $account->save();
            $result = $service->approve($account);
            if ($result['success']) {
                // email notify user approved
                $this->mailNotify($account->user->email, 'user_forex_account_approved', [
                    '[[full_name]]' => $account->user->full_name,
                    '[[login]]' => $result['login'] ?? $account->login,
                    '[[password]]' => $result['password'] ?? '',
                    '[[investor_password]]' => $result['investor_password'] ?? '',
                    '[[server]]' => $result['server'] ?? ($account->server ?? ''),
                    '[[plan_name]]' => optional($account->schema)->title,
                    '[[message]]' => $request->comment ?? '',
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ]);
                return response()->json(['success' => $result['message'], 'reload' => true]);
            }
            return response()->json(['error' => $result['message'], 'reload' => false]);
        }

        // Call respective methods based on the provided input
        if ($request->leverage) {
            return $this->updateLeverage($request);
        }

        if ($request->name) {
            return $this->updateAccountName($request);
        }
// its for change account type
        if ($request->forex_schema_id) {
            return $this->updateForexSchema($request);
        }
        if ($request->account_type) {
            return $this->updateAccountType($request);
        }

        if ($request->main_password) {
            return $this->resetMainPassword($request);
        }

        if ($request->invest_password) {
            return $this->resetInvestorPassword($request);
        }

        if ($request->archive) {
            return $this->archiveAccount($request);
        }

        if ($request->reactive) {
            return $this->reactivateAccount($request);
        }
    }

// Update leverage of a forex account
    private
    function updateLeverage($request)
    {
        // Fetch the forex account using login
        $forexAccount = ForexAccount::where('login', $request->login)->first();

        if (!$forexAccount) {
            return response()->json(['error' => __('Invalid forex account!'), 'reload' => false]);
        }

        // Prevent updating to the same leverage value
        if ($forexAccount->leverage == $request->leverage) {
            return response()->json(['error' => __('Leverage :leverage is already assigned.', ['leverage' => $request->leverage]), 'reload' => false]);
        }

        // Check if leverage change requires admin approval
        if (setting('leverage_approval', 'features') == 'by_admin') {
            LeverageUpdate::updateOrCreate(['user_id' => auth()->user()->id, 'forex_account_id' => $forexAccount->id], [
                'last_leverage' => $forexAccount->leverage,
                'updated_leverage' => $request->leverage,
            ]);

            $this->leverageMailNotify($request, 'user_pending_leverage');

            return response()->json(['success' => __('Leverage update request submitted.'), 'reload' => true]);
        } else {
            // Update leverage directly
            $this->forexApiService->setUserLeverage([
                'login' => $forexAccount->login,
                'leverageAmount' => $request->leverage,
            ]);

            $forexAccount->update(['leverage' => $request->leverage]);
            $this->leverageMailNotify($request, 'user_approved_leverage');

            return response()->json(['success' => __('Leverage updated successfully!'), 'reload' => true]);
        }
    }

// Update account name
    private
    function updateAccountName($request)
    {
        ForexAccount::where('login', $request->login)->update(['account_name' => $request->name]);
        return response()->json(['success' => __('Account name updated successfully.'), 'reload' => true]);
    }

// Update Forex Schema and Group
    private
    function updateForexSchema($request)
    {
        $forexAccount = ForexAccount::where('login', $request->login)->first();
        if (!$forexAccount) {
            return response()->json(['error' => __('Forex account not found.'), 'reload' => false]);
        }

        $schema = ForexSchema::find($request->forex_schema_id);
        if (!$schema) {
            return response()->json(['error' => __('Invalid Forex Schema.'), 'reload' => false]);
        }

        // Determine the appropriate group
        $group = ($forexAccount->account_type === 'real') ? $schema->real_swap_free : $schema->demo_swap_free;
        $forexAccount->update(['forex_schema_id' => $request->forex_schema_id, 'group' => $group]);

        $response = $this->forexApiService->updateUserGroup([
            'login' => $forexAccount->login,
            'group' => $group,
        ]);

        return $response['success']
            ? response()->json(['success' => __('Forex Schema updated successfully.'), 'reload' => true])
            : response()->json(['error' => __('Failed to update group.'), 'reload' => false]);
    }

// Reset Main Password
    private
    function resetMainPassword($request)
    {
        $response = $this->forexApiService->resetMasterPassword([
            'login' => $request->login,
            'password' => $request->main_password,
        ]);
        return response()->json(['success' => __('Password updated successfully.'), 'reload' => false]);
    }

// Reset Investor Password
    private
    function resetInvestorPassword($request)
    {
        $response = $this->forexApiService->resetInvestorPassword([
            'login' => $request->login,
            'password' => $request->invest_password,
        ]);
        return response()->json(['success' => __('Investor Password updated successfully.'), 'reload' => false]);
    }

// Archive Account
    private
    function archiveAccount($request)
    {
        ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Archive]);
        return response()->json(['success' => __('Account archived successfully.'), 'reload' => true]);
    }

// Reactivate Account
    private
    function reactivateAccount($request)
    {
        ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Ongoing]);
        return response()->json(['success' => __('Account reactivated successfully.'), 'reload' => true]);
    }

    public
    function leverageMailNotify($request, $mailType)
    {
//        dd($request->user_id);
        $user = \App\Models\User::find($request->user_id);

        if (!$user) {
            // Handle the case where the user doesn't exist (optional)
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Define shortcodes using the fetched user data
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[login]]' => $request->login,
            '[[leverage]]' => $request->leverage,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        // Send mail notification
        $this->mailNotify($user->email, $mailType, $shortcodes);
    }

    public function updateAccountType(Request $request)
    {
        $request->validate([
            'login' => ['required', 'integer'],
            'account_type' => ['required', 'in:real,demo'],
        ]);

        $updated = ForexAccount::where('login', $request->login)
            ->update(['account_type' => $request->account_type]);

        if ($updated) {
            return response()->json(['success' => __('Successfully updated your account type.'), 'reload' => true]);
        } else {
            return response()->json(['error' => __('Failed to update account type. Please try again.')], 400);
        }
    }

}
