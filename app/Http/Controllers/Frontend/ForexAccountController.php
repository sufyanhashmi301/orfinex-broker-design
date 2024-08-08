<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\IBStatus;
use App\Enums\InvestStatus;
use App\Enums\ForexAccountStatus;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\DepositMethod;
use App\Models\ForexAccount;
use App\Models\ForexSchema;
use App\Models\Invest;
use App\Models\LevelReferral;
use App\Models\Schema;
use App\Models\User;
use App\Rules\ForexLoginBelongsToUser;
use App\Services\ForexApiService;
use App\Rules\ForexLoginBelongsToUserGeneral;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
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
        $user = Auth::user();
        $schema = ForexSchema::find($input['schema_id']);
        $accountType = $request->account_type ;
//        dd(ForexAccount::where(['user_id'=>$user->id, 'forex_schema_id'=>$schema->id, 'account_type'=>$accountType])->count(),$accountType,$schema->account_limit);
        if (ForexAccount::where(['user_id'=>$user->id, 'forex_schema_id'=>$schema->id, 'account_type'=>$accountType])->count() >= $schema->account_limit) {
            $message = __('Sorry, You have achieved your account creation limit of :title type . Please choose different type or contact support to increase your account limit.',['title'=> $schema->title]);
            notify()->error($message, 'Error');
            return redirect()->back();
        }
        $login = 0;
        $forexAccount = ForexAccount::where('forex_schema_id',$schema->id)->orderBY('login','desc')->first();
        if($forexAccount) {
            if($forexAccount->login >= $schema->end_range){
                $message = __('Sorry, The account creation range is completed of :title type. Please choose different type or contact support to increase the account range.',['title'=> $schema->title]);
                notify()->error($message, 'Error');
                return redirect()->back();
            }
            $login = $forexAccount->login++;
        }else{
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
        if($accountType == 'real'){
            $response = $this->forexApiService->createUser($data);
        }else{
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
                return redirect()->route('user.forex-account-logs');
            }

//            return redirect()->back()->withErrors(['msg' => 'Some error occurred! please try again']);

        }

        notify()->error('Some error occurred! please try again', 'Error');
        return redirect()->route('user.schema.preview', $schema->id);

        return redirect()->route('user.forex-account-logs');
    }

    public function userAccountExist($account)
    {
//        dd($account);
        $forexAccount = ForexAccount::where('login', $account)->where('status', ForexAccountStatus::Ongoing)->first();

        if ($forexAccount) {
            $data = 'Name: ' . $forexAccount->user->first_name . ' ' . $forexAccount->user->last_name;
        } else {
            $data = 'Account Not Found';
        }

        return $data;
    }

    public function forexAccountLogs(Request $request)
    {

        $clientIp = request()->ip();
        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
//            sync_forex_accounts(auth()->id());
        }
        $realForexAccounts = ForexAccount::realActiveAccount()
            ->orderBy('balance', 'desc')
            ->get();
//        dd($realForexAccounts);
        $demoForexAccounts = ForexAccount::demoActiveAccount()
            ->orderBy('balance', 'desc')
            ->get();
        $archiveForexAccounts = ForexAccount::archiveAccount()
            ->orderBy('balance', 'desc')
            ->get();

        return view('frontend::user.forex.log', compact('realForexAccounts', 'demoForexAccounts', 'archiveForexAccounts'));
    }

    public function testForexAccount(Request $request)
    {
        $data = [
            'login' => 600952
        ];
        $response = $this->forexApiService->getBalance($data);
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
        $realForexAccounts = ForexAccount::realActiveAccount()
            ->orderBy('balance', 'desc')
            ->get();
        $demoForexAccounts = ForexAccount::demoActiveAccount()
            ->orderBy('balance', 'desc')
            ->get();
        $archiveForexAccounts = ForexAccount::archiveAccount()
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
            'login' => ['required','integer', new ForexLoginBelongsToUserGeneral],
            'leverage' => 'sometimes|nullable|numeric|gt:0',
//            'password' => 'sometimes|nullable|'.Password::min(8)->mixedCase(),
            'main_password' => ['sometimes',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),?:{}|<>])[A-Za-z\d!@#$%^&*(),?:{}|<>]+$/',
            ],
            'invest_password' => ['sometimes',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),?:{}|<>])[A-Za-z\d!@#$%^&*(),?:{}|<>]+$/',
            ],
        ]);
//        dd('s');
        $updateUserUrl = config('forextrading.updateUserUrl');

        $dataArray = [];
        $dataArray['Login'] = $request->login;

        if ($request->leverage) {
//            $updateUserApiResponse = $this->updateLeverage($request->login, $request->leverage);
            $data = [
                'login' => $request->login,
                'leverageAmount' => $request->leverage,
            ];

            $updateUserApiResponse = $this->forexApiService->setUserLeverage($data);

            if ($updateUserApiResponse['success']) {
                ForexAccount::where('login', $request->login)->update(['leverage' => $request->leverage]);
                return response()->json(['success' => __('Successfully updated Leverage.'), 'reload' => true]);
            } else {
                return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again.'), 'reload' => false]);
            }
        }

//        if ($request->name) {
//            ForexAccount::where('login', $request->login)->update(['account_name' => $request->name]);
//            return response()->json(['success' => __('Successfully updated your account name.'), 'reload' => true]);
//
//        }
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
