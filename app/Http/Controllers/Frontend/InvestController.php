<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\InvestmentStatus;
use App\Enums\InvestStatus;
use App\Enums\TraderType;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\DepositMethod;
use App\Models\ForexSchemaInvestment;
use App\Models\Invest;
use App\Models\LevelReferral;
use App\Models\Schema;
use App\Services\ForexApiService;
use App\Services\x9ApiService;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Auth;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;
use Txn;

class InvestController extends GatewayController
{
    use ImageUpload, NotifyTrait;

    public function investNow(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'schema_id' => 'required',
            'invest_amount' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'wallet' => 'in:main,profit,gateway',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();

        $user = Auth::user();
        $schema = Schema::with('schedule')->find($input['schema_id']);

        $investAmount = $input['invest_amount'];

        //Insufficient Balance validation
        if ($input['wallet'] == 'main' && $user->balance < $investAmount) {
            notify()->error('Insufficient Balance Your Main Wallet', 'Error');

            return redirect()->route('user.schema.preview', $schema->id);
        } elseif ($input['wallet'] == 'profit' && $user->profit_balance < $investAmount) {
            notify()->error('Insufficient Balance Your Profit Wallet', 'Error');

            return redirect()->route('user.schema.preview', $schema->id);
        }

        //invalid Amount
        if (($schema->type == 'range' && ($schema->min_amount > $investAmount || $schema->max_amount < $investAmount)) || ($schema->type == 'fixed' && $schema->fixed_amount != $investAmount)) {
            notify()->error('Invest Amount Out Of Range', 'Error');

            return redirect()->route('user.schema.preview', $schema->id);
        }

        $periodHours = $schema->schedule->time;
        $profitClearHours = $schema->profitWithdrawSchedule->time;
        $nextProfitTime = Carbon::now()->addHour($periodHours);
        $nextProfitClearTime = Carbon::now()->addHour($profitClearHours);
        $siteName = setting('site_title', 'global');
        $data = [
            'user_id' => $user->id,
            'schema_id' => $schema->id,
            'invest_amount' => $investAmount,
            'next_profit_time' => $nextProfitTime,
            'next_profit_clear_time' => $nextProfitClearTime,
            'profit_clear_hours' => $profitClearHours,
            'capital_back' => $schema->capital_back,
            'min_interest' => $schema->min_return_interest,
            'interest' => $schema->return_interest,
            'interest_type' => $schema->interest_type,
            'return_type' => $schema->return_type,
            'number_of_period' => $schema->number_of_period,
            'period_hours' => $periodHours,
            'wallet' => $input['wallet'],
            'status' => InvestStatus::Ongoing,
        ];

        if ($input['wallet'] == 'main') {
            $user->decrement('balance', $input['invest_amount']);

        } elseif ($input['wallet'] == 'profit') {
            $user->decrement('profit_balance', $input['invest_amount']);
        } else {

            $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();

            $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $investAmount) : $gatewayInfo->charge;
            $finalAmount = (float) $investAmount + (float) $charge;
            $payAmount = $finalAmount * $gatewayInfo->rate;
            $payCurrency = $gatewayInfo->currency;

            $manualData = null;
            if (isset($input['manual_data'])) {
                $manualData = $input['manual_data'];
                foreach ($manualData as $key => $value) {

                    if (is_file($value)) {
                        $manualData[$key] = self::imageUploadTrait($value);
                    }
                }

            }

            $txnInfo = Txn::new($investAmount, $charge, $finalAmount, $gatewayInfo->name, $schema->name.' Invested', TxnType::Investment, TxnStatus::Pending, $payCurrency, $payAmount, $user->id, null, 'user', $manualData ?? []);
            $data = array_merge($data, ['status' => InvestStatus::Pending, 'transaction_id' => $txnInfo->id]);
            Invest::create($data);

            return self::depositAutoGateway($input['gateway_code'], $txnInfo);

        }

        $tnxInfo = Txn::new($input['invest_amount'], 0, $input['invest_amount'], 'system', $schema->name.' Plan Invested', TxnType::Investment, TxnStatus::Success, null, null, $user->id);
        $data = array_merge($data, ['transaction_id' => $tnxInfo->id]);
        Invest::create($data);

        if (setting('site_referral', 'global') == 'level' && setting('investment_level')) {
            $level = LevelReferral::where('type', 'investment')->max('the_order') + 1;
            creditReferralBonus($user, 'investment', $input['invest_amount'], $level);
        }

        $shortcodes = [
            '[[full_name]]' => $tnxInfo->user->full_name,
            '[[txn]]' => $tnxInfo->tnx,
            '[[plan_name]]' => $tnxInfo->invest->schema->name,
            '[[invest_amount]]' => $tnxInfo->amount.setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        $this->mailNotify($tnxInfo->user->email, 'user_investment', $shortcodes);
        $this->pushNotify('user_investment', $shortcodes, route('user.invest-logs'), $tnxInfo->user->id);
        $this->smsNotify('user_investment', $shortcodes, $tnxInfo->user->phone);

        notify()->success('Successfully Investment', 'success');

        return redirect()->route('user.invest-logs');
    }

    public function showPlans(Request $request, $ucode = null)
    {
//        dd($request->all(),$ucode);
        $invest = ForexSchemaInvestment::find(get_hash($ucode));
        if (blank($invest)) {
//            throw ValidationException::withMessages(['invest' => 'Invalid Funded!']);
        }
//        dd($invest);

//        $balance = user_balance(AccType('main'));

        // All Scheme Listing
        $plans = $this->getSchemes();

        if (empty($plans)) {
//            $errors = MsgState::of('no-plan', 'invest');
//            return view("investment.user.pricing.invest.errors", $errors);
        }
        $type = $request->funded_main_type;
        $subType = $request->funded_sub_type;
        $stage = $request->funded_stage;
//        dd($type,$subType,$stage);

        return view("investment.user.pricing.invest", compact('plans','invest','type','subType','stage','ucode'));
    }
    public function investmentDetails($id)
    {
        $invest = ForexSchemaInvestment::find(get_hash($id));
//        dd($invest);
        if (blank($invest)) {
            notify()->error(__('Investment not found! Try Again'), 'Error');
            return redirect()->back();
//            throw ValidationException::withMessages(['invest' => 'Invalid Funded!']);
        }
        if ($invest->status == InvestmentStatus::ACTIVE) {
            $response = $this->syncPricingAccount($invest->login);
            $invest = $invest->fresh();

            $growthPercentage = percentage_of_total_calc($invest->profit, $invest->amount_allotted);

            $traderType = $invest->trader_type;

            if($traderType == TraderType::MT5) {
                $forexApi = new ForexApiService();
                $data = [
                    'login'=>$invest->login
//                'login'=>555561
                ];
//                $todayScore = $forexApi->getTodayRiskScore($data);
//                $weeklyScore = $forexApi->getWeekRiskScore($data);
//                $totalScore = $forexApi->getTotalRiskScore($data);
//                $totalBalance = $forexApi->getBalance($data);

                $todayScore = [];
                $weeklyScore = [];
                $totalScore = [];
                $totalBalance = [];

            }elseif($traderType == TraderType::X9) {
                $forexApi = new x9ApiService();
                $data = [
                    'login_id'=>$invest->login
//                'login'=>555561
                ];
                $todayScore['result'] = [];
                $weeklyScore['result']= [];
                $totalScore['result'] = [];
                $totalBalance = $forexApi->getBalance($data);
                $totalBalance['result'] = $totalBalance['result']['trading_account_details']['balance_details'];
//                dd($totalBalance);
            }

//            dd($totalBalance);
            $todayDrawddown = 0;
            if (BigDecimal::of(to_minus($invest->snap_equity, $invest->current_equity))->isGreaterThan(BigDecimal::of(0))) {
                $todayDrawddown = to_minus($invest->snap_equity, $invest->current_equity);
            }
            $remainingLoss = to_minus($invest->daily_drawdown_limit, $todayDrawddown);

            return view("frontend::fund_board.active_plan", compact("invest", "todayDrawddown", "remainingLoss", "growthPercentage", "todayScore", "weeklyScore", "totalScore", "totalBalance"));
        }

        $plans = PricingScheme::where('status', 'active')->get();

        return view("investment.user.pricing.plan", compact("invest", "plans"));
    }

    public function syncPricingAccount($login)
    {
//        $getUserResponse = get_mt5_account($login);
//        if () {
//            $this->updatePricingInvestmentAccount($getUserResponse);
//        }else{
//            $invest = ForexSchemaInvestment::where('login',$login)->first();
//            $invest->status = PricingInvestmentStatus::VIOLATED;
//            $invest->violated_at = Carbon::now();
//            $invest->save();
//        }
    }
    public function updatePricingInvestmentAccount($getUserResponse)
    {
        $resData = $getUserResponse;
//        dd($resData);
        if ($getUserResponse) {
            $pricingInvestment = ForexSchemaInvestment::where('login', $resData->Login)->first();
//            $pricingInvestment->leverage = $resData->Leverage;
            $pricingInvestment->current_balance = $resData->Balance;
            $pricingInvestment->current_equity = $resData->Equity;

            $profit = 0;
            if(to_minus($resData->Equity,$pricingInvestment->amount_allotted) > 0){
                $profit = to_minus($resData->Equity,$pricingInvestment->amount_allotted);
            }
            $pricingInvestment->profit = $profit;
//            $pricingInvestment->group = $resData->Group;
            $pricingInvestment->save();
        }
    }
    public function investLogs(Request $request)
    {

        if ($request->ajax()) {
            $data = Invest::with('schema')->where('user_id', auth()->id())->latest();
//            dd($data->get());
//            dd('frontend::user.include.__invest_rio');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('icon', 'frontend::user.include.__invest_icon')
                ->addColumn('schema', 'frontend::user.include.__invest_schema')
                ->addColumn('rio', 'frontend::user.include.__invest_rio')
                ->addColumn('profit', 'frontend::user.include.__invest_profit')
                ->addColumn('period_remaining', function ($raw) {
                    if ($raw->return_type != 'period') {
                        return 'Unlimited';
                    }

                    return $raw->number_of_period.($raw->number_of_period < 2 ? ' Time' : ' Times');
                })
                ->editColumn('capital_back', 'frontend::user.include.__invest_capital_back')
                ->editColumn('next_profit_time', 'frontend::user.include.__invest_next_profit_time')
                ->rawColumns(['icon', 'schema', 'rio', 'profit', 'capital_back', 'next_profit_time'])
                ->make(true);
        }

        return view('frontend::user.invest.log');
    }

    public function investCancel($id)
    {
        $investment = Invest::find($id);

        //daily limit
        $todayTransaction = Invest::where('status', InvestStatus::Canceled)->whereDate('created_at', Carbon::today())->count();
        $dayLimit = (float) Setting('send_money_day_limit', 'fee');
        if ($todayTransaction >= $dayLimit) {
            notify()->error(__('Today Investment Cancel limit has been reached'), 'Error');

            return redirect()->back();
        }

        if ($investment->is_cancel && $investment->status == InvestStatus::Ongoing) {
            $investment->update([
                'status' => InvestStatus::Canceled,
            ]);

            $user = $investment->user;

            $user->balance += $investment->invest_amount;
            $user->save();

            Txn::new($investment->invest_amount, 0, $investment->invest_amount, 'system', $investment->schema->name.' '.'Money Refund in Main Wallet from System', TxnType::Refund, TxnStatus::Success, null, null, $user->id);
            notify()->success('Cancel Investment Successfully', 'success');

            return redirect()->route('user.invest-logs');
        }
        abort_if(! $investment->schema->schema_cancel, 403, 'Can Not Be Cancel Investment');
        notify()->warning('Can Not Be Cancel Investment', 'warning');

        return redirect()->route('user.invest-logs');
    }
}
