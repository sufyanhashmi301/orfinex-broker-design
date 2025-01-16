<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Enums\IBStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Exports\RealAccountExport;
use App\Exports\DemoAccountExport;
use App\Models\LeverageUpdate;
use App\Models\ForexSchema;
use App\Models\Invest;
use App\Models\User;
use App\Services\ForexApiService;
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
        $loggedInUser = auth()->user(); // Get the logged-in admin or user

        // Get attached user IDs for non-Super-Admin users
        $attachedUserIds = $loggedInUser->hasRole('Super-Admin')
            ? null
            : $loggedInUser->users->pluck('id');

        // Query for Forex Accounts
        $data = ForexAccount::query()
            ->with('schema')
            ->where('account_type', $type);

        if (!$loggedInUser->hasRole('Super-Admin')) {
            // Apply attached user filter for non-Super-Admin
            if ($attachedUserIds->isNotEmpty()) {
                $data->whereIn('user_id', $attachedUserIds);
            } else {
                $data = collect(); // Empty collection if no attached users
            }
        }

        if ($id) {
            $data->where('user_id', $id);
        }

        // Apply additional filters
        $filters = $request->only(['global_search', 'login', 'country', 'status', 'created_at', 'tag']);
        $data->applyFilters($filters);

        // If request is Ajax, return data in Datatables format
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('ib_number', 'backend.user.include.__ib_number')
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('balance', 'backend.investment.include.__balance_mt5')
                ->addColumn('equity', 'backend.investment.include.__equity_mt5')
                ->addColumn('credit', 'backend.investment.include.__credit_mt5')
                ->addColumn('schema', 'backend.investment.include.__invest_schema')
                ->addColumn('status', 'backend.investment.include.__status')
                ->addColumn('action', 'backend.investment.include.__action')
                ->rawColumns(['ib_number', 'schema', 'username', 'balance', 'equity', 'credit', 'status', 'action'])
                ->make(true);
        }

        // Calculate balance-related statistics
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
                    ->where('Balance', 0)
                    ->count();
            }
        } catch (\Exception $e) {
            \Log::error('MT5 DB connection failed when retrieving account: ' . $e->getMessage());
        }

        // Count inactive accounts
        $unActiveAccounts = ForexAccount::where('account_type', $type)
            ->where('status', '!=', ForexAccountStatus::Ongoing);

        if (!$loggedInUser->hasRole('Super-Admin')) {
            // Apply attached user filter for non-Super-Admin
            $unActiveAccounts->whereIn('user_id', $attachedUserIds);
        }

        $unActiveAccounts = $unActiveAccounts->count();

        // Prepare final data to pass to the view
        $data = [
            'TotalAccounts' => $data->count(),
            'withBalance' => $withBalance,
            'withoutBalance' => $withoutBalance,
            'unActiveAccounts' => $unActiveAccounts,
        ];

        return view('backend.investment.index', compact('data', 'type'));
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


    public function forexAccountCreateNow(Request $request)
    {

//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'schema_id' => 'required',
            'main_password' => ['required',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),?:{}|<>])[A-Za-z\d!@#$%^&*(),?:{}|<>]+$/',
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
            'main_password.regex' => __('The main password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.'),
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

        if ($forexAccount) {
            if ($forexAccount->login >= $schema->end_range) {
                $message = __('Sorry, The account creation range is completed of :title type. Please choose different type or contact support to increase the account range.', ['title' => $schema->title]);
                notify()->error($message, 'Error');
                return redirect()->back();
            }
            $login = $forexAccount->login++;
        } else {
            $login = $schema->start_range;
        }
        $group = '';
        if ($request->account_type === 'real') {
            $group = $request->is_islamic ? $schema->real_islamic : $schema->real_swap_free;
        } elseif ($request->account_type === 'demo') {
            $group = $request->is_islamic ? $schema->demo_islamic : $schema->demo_swap_free;
        }

        $server = config('forextrading.server');
        $password = $request->main_password;

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
            "phone" => $user->phone,
            "email" => $user->email,
            "agent" => 0,
            "account" => "",
            "company" => env('APP_NAME', 'Company'),
            "language" => 0,
            "phonePassword" => 'SNNH@2024@bol',
            "status" => "RE",
            "masterPassword" => $password,
            "investorPassword" => 'SNNH@2024@bol'
        ];
//        dd($data,$accountType);
        if ($accountType == 'real') {
            $response = $this->forexApiService->createUser($data);
        } else {
            $response = $this->forexApiService->createUserDemo($data);
        }
        if ($response['success']) {
            $resResult = $response['result'];
            $mt5Login = $resResult['login'];
//            dd($response,$response->data[0]->Login);
            if ($mt5Login && $resResult['responseCode'] == 0) {
                $rightData =  [
                    "login" => $mt5Login,
                    "rights" => 'USER_RIGHT_ENABLED',

                ];
                $this->forexApiService->setUserRights($rightData);

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
                $accountData['trading_platform'] = config('forextrading.tradingPlatform');
                $forexTrading = ForexAccount::create($accountData);

                if ($user->ref_id) {
                    $referrer = User::find($user->ref_id);
                    if ($referrer->ib_status == IBStatus::APPROVED && isset($referrer->ib_login)) {
                        $data = [
                            'login' => $mt5Login,
                            'agent' => $referrer->ib_login,
                        ];
                        $this->forexApiService->updateAgentAccount($data);
                    }
                }
//                if($forexTrading->account_type == ForexTradingAccountTypesStatus::REAL)
//                    event(new NewForexAccountEvent($forexTrading));


//                $shortcodes = [
//                    '[[full_name]]' => $tnxInfo->user->full_name,
//                    '[[txn]]' => $tnxInfo->tnx,
//                    '[[plan_name]]' => $tnxInfo->invest->schema->name,
//                    '[[invest_amount]]' => $tnxInfo->amount.setting('site_currency', 'global'),
//                    '[[site_title]]' => setting('site_title', 'global'),
//                    '[[site_url]]' => route('home'),
//                ];
//
//                $this->mailNotify($tnxInfo->user->email, 'user_investment', $shortcodes);
//                $this->pushNotify('user_investment', $shortcodes, route('user.forex-account-logs'), $tnxInfo->user->id);
//                $this->smsNotify('user_investment', $shortcodes, $tnxInfo->user->phone);

                notify()->success('Successfully Created Account', 'success');
                return redirect()->back();

//                return redirect()->route('user.forex-account-logs');
            }

//            return redirect()->back()->withErrors(['msg' => 'Some error occurred! please try again']);

        }

        notify()->error('Some error occurred! please try again', 'Error');
        return redirect()->back();
    }

    public function getLeverage(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $forexTrading = ForexAccount::find($request->id);

        return view('backend.investment.modal.__change_leverage_render', compact('forexTrading'))->render();

    }

    public function pendingLeverage(Request $request)
    {
        $loggedInUser = auth()->user();

        if ($loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin can view all leverage updates
            $leverageUpdates = LeverageUpdate::with('user', 'forexAccount')
                ->where('status', 0)
                ->get();
        } else {
            // Get attached user IDs for non-Super-Admin users
            $attachedUserIds = $loggedInUser->users->pluck('id');

            if ($attachedUserIds->isNotEmpty()) {
                // Show leverage updates for attached users only
                $leverageUpdates = LeverageUpdate::with('user', 'forexAccount')
                    ->where('status', 0)
                    ->whereIn('user_id', $attachedUserIds)
                    ->get();
            } else {
                // If no users are attached, return an empty collection
                $leverageUpdates = collect(); // Empty collection
            }
        }

        return view('backend.investment.leverage.pending', compact('leverageUpdates'));
    }


    public function handlePendingLeverage(Request $request)
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
        // Fetch all leverage updates with their associated user and forexAccount relationships
        $leverageUpdates = LeverageUpdate::with('user', 'forexAccount')->get();
        $loggedInUser = auth()->user();
        if ($loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin can view all leverage updates
            $leverageUpdates = LeverageUpdate::with('user', 'forexAccount')
                ->get();
        } else {
            // Get attached user IDs for non-Super-Admin users
            $attachedUserIds = $loggedInUser->users->pluck('id');
            if ($attachedUserIds->isNotEmpty()) {
                // Show leverage updates for attached users only
                $leverageUpdates = LeverageUpdate::with('user', 'forexAccount')
                    ->whereIn('user_id', $attachedUserIds)
                    ->get();
            } else {
                // If no users are attached, return an empty collection
                $leverageUpdates = collect(); // Empty collection
            }
        }

        return view('backend.investment.leverage.all', compact('leverageUpdates'));
    }


    public function handleAllLeverage(Request $request)
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

    public function updateAccountInfo(Request $request)
    {
        $request->validate([
            'login' => ['required', 'integer', new ForexLoginBelongsToUserGeneral],
            'leverage' => 'sometimes|nullable|numeric|gt:0',
            'main_password' => [
                'sometimes',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),?:{}|<>])[A-Za-z\d!@#$%^&*(),?:{}|<>]+$/',
            ],
            'invest_password' => [
                'sometimes',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),?:{}|<>])[A-Za-z\d!@#$%^&*(),?:{}|<>]+$/',
            ],
        ]);
        $updateUserUrl = config('forextrading.updateUserUrl');

        $dataArray = [];
        $dataArray['Login'] = $request->login;

        if ($request->leverage) {
            $forexAccount = ForexAccount::where('login',$request->login)->first();
            if($forexAccount->leverage == $request->leverage){
                return response()->json(['error' => __('Kindly provide a different leverage! The leverage :leverage has already been assigned.',['leverage'=>$request->leverage]), 'reload' => false]);

            }
            if(!$forexAccount) {
                return response()->json(['error' => __('Kindly provide valid forex account and try again!'), 'reload' => false]);
            }
            $data = [
                'last_leverage' => $forexAccount->leverage,
                'updated_leverage' => $request->leverage,
            ];
            if(setting('leverage_approval','features')  == 'by_admin') {
                LeverageUpdate::updateOrCreate(['user_id' => auth()->user()->id,
                    'forex_account_id' => $forexAccount->id], $data);
                $mailType   = 'user_pending_leverage';
                $this->leverageMailNotify($request,$mailType);
                return response()->json(['success' => __('Leverage update request successfully submitted. An admin will review and process it shortly.'), 'reload' => true]);
            }else{
                // Prepare data for the API call
                $data = [
                    'login' => $forexAccount->login,
                    'leverageAmount' => $request->leverage,
                ];

                // Call the API to update leverage
                $response = $this->forexApiService->setUserLeverage($data);

                // Update leverage in ForexAccount model

                $forexAccount->leverage = $request->leverage;
                $forexAccount->save();

                $mailType   = 'user_approved_leverage';
                $this->leverageMailNotify($request,$mailType);

                // Send email notification
                return response()->json(['success' => __('Leverage Update Approved and Updated Successfully!.'), 'reload' => true]);

                $message = 'Leverage Update Approved and Updated Successfully!';
            }
        }

        if ($request->name) {
            ForexAccount::where('login', $request->login)->update(['account_name' => $request->name]);
            return response()->json(['success' => __('Successfully updated your account name.'), 'reload' => true]);

        }
        if ($request->main_password) {
            $dataArray['MainPassword'] = $request->main_password;

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

                $this->mailNotify(auth()->user()->email, 'user_update_master_password', $shortcodes);

                return response()->json(['success' => __('Successfully updated Password.'), 'reload' => false]);
            } else {
                return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again.'), 'reload' => false]);
            }
        }
        if ($request->invest_password) {
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

                $this->mailNotify(auth()->user()->email, 'user_update_investor_password', $shortcodes);

                return response()->json(['success' => __('Successfully updated Password.'), 'reload' => false]);
            } else {
                return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again.'), 'reload' => false]);
            }
        }
        if ($request->archive) {
            ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Archive]);

            $shortcodes = [
                '[[full_name]]' => auth()->user()->full_name,
                '[[login]]' => $request->login,
                '[[password]]' =>  $request->invest_password,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            $this->mailNotify(auth()->user()->email, 'user_update_investor_password', $shortcodes);

            return response()->json(['success' => __('Successfully archived your account.'), 'reload' => true]);
        }
        if ($request->reactive) {
            ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Ongoing]);
            return response()->json(['success' => __('Successfully reactive your account.'), 'reload' => true]);
        }

    }

    public function leverageMailNotify($request, $mailType)
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

}
