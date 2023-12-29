<?php

namespace App\Http\Controllers\Frontend;

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
    use ImageUpload, NotifyTrait,ForexApiTrait;

    public function forexAccountCreateNow(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'schema_id' => 'required',
            'main_password' => ['required',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),-.?":{}|<>])[A-Za-z\d!@#$%^&*(),-.?":{}|<>]+$/',
            ],
            'group' => 'required',
            'leverage' => 'required',
            'account_name' => 'required',
        ],[
            'main_password.required' => __('The main password field is required.'),
            'main_password.min' => __('The main password must be at least 8 characters long.'),
            'main_password.regex' => __('The main password must contain at least one lowercase letter, one uppercase letter, one digit, and one special character.'),

            'leverage.not_regex' => __('Kindly select a valid leverage.'),
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $user = Auth::user();
        $schema = ForexSchema::find($input['schema_id']);

        $group = $schema[$request->group];


        $server = config('forextrading.server');
        $password = $request->main_password;

//        $dataArray = array(

        $data['Name'] = auth()->user()->full_name;
        $data['Leverage'] = $request->leverage;
        $data['Group'] = $group;
        $data['MasterPassword'] = $password;
        $data['InvestorPassword'] = $password;
//        $data['PhonePassword'] = $password;
        $data['Email'] = auth()->user()->email;
        $data['Phone'] = auth()->user()->phone;
        $data['Country'] = auth()->user()->country;
        $data['Login'] = 0;
        $data['Language'] = 0;
        $data['Rights'] = 'USER_RIGHT_ALL';

        $URL = config('forextrading.createUserUrl');
//        dd($data);
        $response = $this->sendApiPostRequest($URL, $data);
//        dd($response->object());
//        if ($response->serverError() || $response->failed()) {
//            notify()->error('Some error occurred! please try again', 'Error');
//            return redirect()->route('user.schema.preview', $schema->id);
//        }
        if ($response->status() == 200 && $response->successful() && $response->object()->ResponseCode == 0) {
            $resData = $response->object();
//            dd($response,$response->data[0]->Login);
            if ($resData->Login) {
                $accountData = $request->all();

                $accountData['forex_schema_id'] = $schema->id;
                $accountData['login'] = $resData->Login;
                $accountData['account_name'] = $request->account_name;
                $accountData['account_type'] = implode('_', array_slice(explode('_', $request->group), 0, 1));
                $accountData['user_id'] = auth()->user()->id;
                $accountData['currency'] = setting('site_currency', 'global');
//                $accountData['invest_password'] = $investPassword;
//                $accountData['phone_password'] = $resData->PhonePassword;
                $accountData['group'] = $data['Group'];
                $accountData['leverage'] = $data['Leverage'];
                $accountData['status'] = ForexAccountStatus::Ongoing;
                $accountData['server'] = $server;
                $accountData['created_by'] = auth()->user()->id;
                $accountData['first_min_deposit_paid'] = 0;
                $accountData['trading_platform'] = config('forextrading.tradingPlatform');
                $forexTrading = ForexAccount::create($accountData);

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

                notify()->success('Successfully Created Forex Account', 'success');
                return redirect()->route('user.forex-account-logs');
            }
//            return redirect()->back()->withErrors(['msg' => 'Some error occurred! please try again']);

        }

            notify()->error('Some error occurred! please try again', 'Error');
            return redirect()->route('user.schema.preview', $schema->id);

//        $periodHours = $schema->schedule->time;
//        $profitClearHours = $schema->profitWithdrawSchedule->time;
//        $nextProfitTime = Carbon::now()->addHour($periodHours);
//        $nextProfitClearTime = Carbon::now()->addHour($profitClearHours);
//        $siteName = setting('site_title', 'global');
//        $data = [
//            'user_id' => $user->id,
//            'schema_id' => $schema->id,
//            'invest_amount' => $investAmount,
//            'next_profit_time' => $nextProfitTime,
//            'next_profit_clear_time' => $nextProfitClearTime,
//            'profit_clear_hours' => $profitClearHours,
//            'capital_back' => $schema->capital_back,
//            'min_interest' => $schema->min_return_interest,
//            'interest' => $schema->return_interest,
//            'interest_type' => $schema->interest_type,
//            'return_type' => $schema->return_type,
//            'number_of_period' => $schema->number_of_period,
//            'period_hours' => $periodHours,
//            'wallet' => $input['wallet'],
//            'status' => InvestStatus::Ongoing,
//        ];


        return redirect()->route('user.forex-account-logs');
    }
    public function userAccountExist($account)
    {
//        dd($account);
        $forexAccount = ForexAccount::where('login', $account)->where('status',ForexAccountStatus::Ongoing)->first();

        if ($forexAccount) {
            $data = 'Name: '.$forexAccount->user->first_name.' '.$forexAccount->user->last_name;
        } else {
            $data = 'Account Not Found';
        }

        return $data;
    }

    public function forexAccountLogs(Request $request)
    {
//        $this->getUserApi(9996792);
//        $this->syncForexAccounts(auth()->id());
        $realForexAccounts = ForexAccount::realActiveAccount()
            ->orderBy('balance','desc')
            ->get();
        $demoForexAccounts = ForexAccount::demoActiveAccount()
            ->orderBy('balance','desc')
            ->get();
        $archiveForexAccounts = ForexAccount::archiveAccount()
            ->orderBy('balance','desc')
            ->get();

        return view('frontend::user.forex.log',compact('realForexAccounts','demoForexAccounts','archiveForexAccounts'));
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
            'login' => 'required',
            'leverage' => 'sometimes|nullable|numeric|gt:0',
//            'password' => 'sometimes|nullable|'.Password::min(8)->mixedCase(),
            'main_password' => ['sometimes',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),-.?":{}|<>])[A-Za-z\d!@#$%^&*(),-.?":{}|<>]+$/',
            ],
            'invest_password' => ['sometimes',
                'min:8',     // Minimum length requirement
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*(),-.?":{}|<>])[A-Za-z\d!@#$%^&*(),-.?":{}|<>]+$/',
            ],
        ]);
//        dd('s');
        $updateUserUrl = config('forextrading.updateUserUrl');

        $dataArray = [];
        $dataArray['Login'] = $request->login;

        if ($request->leverage) {
            $updateUserApiResponse = $this->updateLeverage($request->login, $request->leverage);
//            dd($updateUserApiResponse->object(),$request->login, $request->leverage);
            if ($updateUserApiResponse->status() == 200 && $updateUserApiResponse->object() == 0) {
                return response()->json(['success' => __('Successfully updated Leverage.'), 'reload' => true]);
            } else {
                notify()->error('Opps! We unable to process your request. Please reload the page and try again.', 'Error');
            }
        }
//        if ($request->name) {
//            ForexAccount::where('login', $request->login)->update(['account_name' => $request->name]);
//            return response()->json(['success' => __('Successfully updated your account name.'), 'reload' => true]);
//
//        }
        if ($request->main_password) {
            $dataArray['MainPassword'] = $request->main_password;
            $updateUserApiResponse = $this->updateMainPassword($request->login, $request->main_password);
//        dd($updateUserApiResponse->object());
            if ($updateUserApiResponse->status() == 200 && $updateUserApiResponse->object() == 0) {
                return response()->json(['success' => __('Successfully updated.'), 'reload' => true]);
            } else {
                notify()->error('Opps! We unable to process your request. Please reload the page and try again.', 'Error');
            }
        }
        if ($request->invest_password) {
            $updateUserApiResponse = $this->updateInvestorPassword($request->login, $request->invest_password);
//        dd($updateUserApiResponse->object());
            if ($updateUserApiResponse->status() == 200 && $updateUserApiResponse->object() == 0) {
                return response()->json(['success' => __('Successfully updated.'), 'reload' => true]);
            } else {
                notify()->error('Opps! We unable to process your request. Please reload the page and try again.', 'Error');
            }
//            $dataArray['InvestPassword'] = $request->password;
        }
        if ($request->archive) {
            $updateUserApiResponse = $this->disableAccount($request->login);
//        dd($updateUserApiResponse->object());
            if (($updateUserApiResponse ? $updateUserApiResponse->status() == 200 && isset($updateUserApiResponse->object()->data->Login) : false)) {
                ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Archive]);
                return response()->json(['success' => __('Successfully archived your account.'), 'reload' => true]);
            } else {
                notify()->error('Opps! We unable to process your request. Please reload the page and try again.', 'Error');
            }
        }
        if ($request->reactive) {
//            dd($request->all());
            $updateUserApiResponse = $this->enableAccount($request->login);
//        dd($updateUserApiResponse->object());
            if (($updateUserApiResponse ? $updateUserApiResponse->status() == 200 && isset($updateUserApiResponse->object()->data->Login) : false)) {
                ForexAccount::where('login', $request->login)->update(['status' => ForexAccountStatus::Ongoing]);
                return response()->json(['success' => __('Successfully reactive your account.'), 'reload' => true]);
            } else {
                return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again'), 'reload' => false]);
            }
        }
        $dataArray['Email'] = 'sufyan@gmail.com';
        $dataArray['status'] = 'active user';
        $updateUserApiResponse = $this->sendApiPostRequest($updateUserUrl, $dataArray);
//        dd($updateUserApiResponse->object()->data);
        if (($updateUserApiResponse->status() == 200 && $updateUserApiResponse->object() == 0)) {
//            $getUserResponse = $this->getUserApi($request->login);
//            if ($getUserResponse->status() == 200) {
//                $this->updateUserAccount($getUserResponse);
//            }
            return response()->json(['success' => __('Successfully updated.'), 'reload' => true]);
        } else {
            notify()->error('Opps! We unable to process your request. Please reload the page and try again.', 'Error');

        }

    }
}
