<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Enums\IBStatus;
use App\Http\Controllers\Controller;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\Invest;
use App\Models\User;
use App\Services\ForexApiService;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class   AccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
        $this->middleware('permission:investment-list');

    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function forexAccounts(Request $request, $type = 'real', $id = null)
    {
//        dd($request->all(),$type,$id);
        if ($request->ajax()) {

            if ($id) {
                $data = ForexAccount::with('schema')->where('user_id', $id)->where('account_type', $type)->latest();
            } else {
                $data = ForexAccount::query()->with('schema')->where('account_type', $type)->latest();
            }
//dd($data);
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
        $realForexAccounts = ForexAccount::where('account_type', $type)
            ->where('status', ForexAccountStatus::Ongoing)->pluck('login');
//dd($realForexAccounts);
        $withBalance = 0.0;
        $withoutBalance = 0.0;
        try {
            if($realForexAccounts) {
                $withBalance = DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->whereIn('Login', $realForexAccounts)
                    ->where('Balance', '>', 0)->count();
            }
        } catch (\Exception $e) {
            \Log::error('MT5 DB connection failed when retrieving account: ' . $e->getMessage());

        }

        try {
            if($realForexAccounts) {
                $withoutBalance = DB::connection('mt5_db')
                    ->table('mt5_accounts')
                    ->whereIn('Login', $realForexAccounts)
                    ->where('Balance', 0)->count();
            }
        } catch (\Exception $e) {
            \Log::error('MT5 DB connection failed when retrieving account: ' . $e->getMessage());

        }
        $unActiveAccounts = ForexAccount::where('account_type', $type)->where('status', '!=', ForexAccountStatus::Ongoing)->count();

        $data = [
            'TotalAccounts' => ForexAccount::where('account_type', $type)->count(),
            'withBalance' => $withBalance,
            'withoutBalance' => $withoutBalance,
            'unActiveAccounts' => $unActiveAccounts,
        ];
        return view('backend.investment.index', compact('data', 'type'));
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
        $forexAccount = ForexAccount::where('forex_schema_id', $schema->id)->orderBY('login', 'desc')->first();
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

    public function changeLeverage(){
        return view('backend.investment.change_leverage');
    }

}
