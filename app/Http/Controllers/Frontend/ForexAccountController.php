<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Brick\Math\BigDecimal;
use Illuminate\Http\Request;
use App\Models\ForexAccount;
use App\Enums\InvestmentStatus;
use App\Models\ForexSchemaInvestment;
use App\Models\ForexSchemaPhaseRule;
use App\Services\ForexApiService;
use App\Services\ForexSchemaInvestormService;

class ForexAccountController extends GatewayController
{
    protected $forexApiService;
    private $investment;

    public function __construct(ForexApiService $forexApiService, ForexSchemaInvestormService $investment)
    {
        $this->forexApiService = $forexApiService;
        $this->investment = $investment;
    }

    public function forexAccountCreateNow(Request $request)
    {
//        dd($request->all());
        $currency = setting('site_currency', 'global');

        $input = $request->validate([
            'rule_id' => 'required|exists:forex_schema_phase_rules,id',
//            'leverage' => 'required',
//            'amount' => 'required|numeric|not_in:0',
//            'account_from' => 'required',
//            'profit_share_user' => 'required',
//            'profit_share_admin' => 'required',
//            'payouts' => 'required',
//            'leverage_amount' => 'nullable',
//            'days_to_pass' => 'nullable',
//            'profit_split_amount' => 'nullable',
            'weekly_payout' => 'nullable',
            'swap_free' => 'nullable',
        ],
            [
                'rule_id.required' => __("Please choose an funded plan."),
                'amount.*' => __("Please select payment plan."),
            ]);

        $rule = ForexSchemaPhaseRule::find($input['rule_id']);
//        dd($rule);

        $input['currency'] = $currency;
        $input['account_type'] = 'normal';
//        dd($input);
        // Payment Source
//        $account = 'unknown';
        $amount = ($rule->amount) ? (float)$rule->amount : 0;
//        if($rule->discount > 0) {
//            // Amount & Balance
//            $amount = ($rule->discount) ? (float)$rule->discount : 0;
//        }

        $discount = $rule->discount;
//        dd($request->get('discount'),$discount);
//        $leverage_amount = isset($request['leverage_amount']) ? (float)percentage_calc(get_hash($request['leverage_amount']),$amount): 0;
//        $days_to_pass_amount = isset($request['day_to_pass']) ? (float)percentage_calc(get_hash($request['day_to_pass']),$amount) : 0;
//        $profit_split_amount = isset($request['profit_split_amount']) ? (float)percentage_calc(get_hash($request['profit_split_amount']),$amount) : 0;
        $weekly_payout_amount = isset($request['weekly_payout']) ? (float)(percentage_calc(get_hash($request['weekly_payout']), $amount)) : 0;
//        dd($request['weekly_payout'],$weekly_payout_amount);
        $swap_free_amount = isset($request['swap_free']) ? (float)(percentage_calc(get_hash($request['swap_free']), $amount)) : 0;
//        dd( isset($request['swap_free']),get_hash($request['swap_free']),$swap_free_amount);
        $totalAmount = BigDecimal::of($amount)->plus($weekly_payout_amount)->plus($swap_free_amount);
        $payableAmount = to_minus($totalAmount, $discount);

//        $actualWithdraw = $payableAmount;
//        dd($totalAmount,$discount,$swap_free_amount);

//        $input['bank'] = [];
//        if($request->payment_type == 'wallets') {
//            $accountFrom = explode('_', $request->get('account_from'));
//
//        }
//        elseif($request->payment_type == 'banks'){
//            $accountFrom = explode('_', $request->get('account_from_banks'));
//            $bank = Bank::find(get_hash($accountFrom[1]));
//            $input['bank'] = $bank->toArray();
//        }
//        dd($input['bank']);
//        $type = get_hash($accountFrom[0]);
//        $source = get_hash($accountFrom[1]);
//        dd($source);

//        if ($type == 'wallets') {
//            $accountName = w2n($source);
////            $source = AccountBalanceType::MAIN;
//            $accountId = $source;
//            $balance = getAccountBalance($source, true);
//            $fx = null;
//        }
//        else if($type == 'forex') {
//            $forexTrading = ForexTrading::find($source);
////            dd($forexTrading);
//            $accountName = $forexTrading->account_name;
//            $source = $forexTrading->login;
//            $accountId = $forexTrading->id;
//            $login = $forexTrading->login;
////            dd($login);
//            $balance = $this->getForexAccountBalance($login);
//            $fx = $forexTrading;
//            if ($fx->currency == 'USC') {
//                $scale = (is_crypto($fx->currency)) ? dp_calc('crypto') : dp_calc('fiat');
////                $amount = BigDecimal::of($amount)->dividedBy(100, $scale, RoundingMode::HALF_DOWN);
//                $payableAmount = round($payableAmount * 100, $scale);
//            }
//        }

//        if(in_array($type,['wallets','forex'])) {
//            if (empty($payableAmount)) {
//                throw ValidationException::withMessages([
//                    'amount' => __('Sorry, the funded amount is not valid.')
//                ]);
//            } elseif (empty($balance)) {
//                throw ValidationException::withMessages([
//                    'account' => __('Sorry, not enough balance in selected account.')
//                ]);
//            }
//            if (BigDecimal::of($payableAmount)->compareTo($balance) > 0) {
//                throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);
//
//            }
//        }
        // Funded Plan
//        if (blank($rule) || (!blank($rule) && $rule->status != PricingInvestmentStatus::ACTIVE)) {
//            throw ValidationException::withMessages(['plan' => __('The selected plan may not available or invalid.')]);
//        }

//        $input['source'] = $source;
        $input['max_drawdown_limit'] = $rule->max_drawdown_limit;
        $input['daily_drawdown_limit'] = $rule->daily_drawdown_limit;
        $input['profit_share_user'] = 50;
        $input['profit_share_admin'] = 50;
        $input['leverage'] = $rule->forexSchemaPhase->forexSchema->leverage;
//        $input['type'] = $type;

//        if($days_to_pass_amount > 0){
//            $input['days_to_pass'] ='unlimited';
//        }
//        if($profit_split_amount > 0){
//            $input['profit_share_user'] = 90;
//            $input['profit_share_admin'] = 10;
//        }
        if ($weekly_payout_amount > 0) {
            $input['payouts'] = 'weekly';
        }
        if ($swap_free_amount > 0) {
//            $input['payouts'] ='weekly';
        }
//        dd($input);
        $subscription = $this->investment->processSubscriptionDetails($input, $rule, $amount, $discount, $weekly_payout_amount, $swap_free_amount);
//        dd($subscription);

        if (empty($subscription)) {
            throw ValidationException::withMessages(["scheme" => __("Sorry unable process subscription")]);
        }

//        return $this->wrapInTransaction(function ($subscription,$rule) {
        $invest = $this->investment->confirmSubscription($subscription);
//        dd($invest);
//            event(new FundedEvent($invest));
//            if ($rule->approval == FundedApproval::AUTO) {
//                $this->investment->approveSubscription($invest, 'auto-approved');
//                $invest->fresh();
//            }

//            try {
//                ProcessEmail::dispatch('investment-placed-customer', data_get($invest, 'user'), null, $invest);
//                ProcessEmail::dispatch('investment-placed-admin', data_get($invest, 'user'), null, $invest);
//            } catch (\Exception $e) {
//                save_mailer_log($e, 'investment-placed');
//            }

//            return ['append'=> view("investment.user.pricing.invest.success", compact('invest'))->render()];
//       return response()->json(['append'=>$append]);
//        }, $subscription,$rule);

//        notify()->error('Some error occurred! please try again', 'Error');
//        if ($invest) {
        return redirect()->route('user.deposit.amount',['id'=> the_hash($invest->pvx)]);
//        }
        notify()->error('Some error occurred! please try again', 'Error');

        return redirect()->back();

    }

    public function forexAccountLogs(Request $request)
    {
        $user = auth()->user();

        $investments = ForexSchemaInvestment::whereIn('status', [
            InvestmentStatus::PENDING,
            InvestmentStatus::ACTIVE,
            InvestmentStatus::COMPLETED,
            InvestmentStatus::VIOLATED
        ])->where('user_id', $user->id)
            ->orderBy('id', 'desc')->get();
        $investments = $investments->groupBy('status');

        return view('frontend::user.forex.log', compact('investments'));
    }
}
