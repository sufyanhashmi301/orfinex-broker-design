<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\AccountBalanceType;
use App\Enums\FundedApproval;
use App\Enums\FundedSchemeSubTypes;
use App\Enums\FundedSchemeTypes;
use App\Enums\PricingInvestmentStatus;
use App\Enums\SchemeStatus;
use App\Enums\InvestmentStatus;
use App\Events\FundedEvent;
use App\Models\ForexTrading;


use App\Jobs\ProcessEmail;
use App\Helpers\MsgState;
use App\Models\PricingInvestment;
use App\Models\PricingScheme;


use App\Services\PricingInvestormService;
use App\Traits\ForexApi;
use Brick\Math\BigDecimal;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class PricingInvestController extends Controller
{
    use ForexApi;
    private $investment;

    public function __construct(PricingInvestormService $investment)
    {
        $this->investment = $investment;
    }

    public function showPlans(Request $request, $ucode = null)
    {
//        dd($request->all(),$ucode);
        $invest = PricingScheme::find(get_hash($ucode));
        if (blank($invest)) {
            throw ValidationException::withMessages(['invest' => 'Invalid Funded!']);
        }
//        dd($invest);

        $balance = user_balance(AccType('main'));

        // All Scheme Listing
        $plans = $this->getSchemes();

        if (empty($plans)) {
            $errors = MsgState::of('no-plan', 'invest');
            return view("frontend::investment.invest.errors", $errors);
        }
        $type = $request->funded_main_type;
        $subType = $request->funded_sub_type;
        $stage = $request->funded_stage;
//        dd($type,$subType,$stage);

        return view("frontend::investment.invest", compact('plans','invest','type','subType','stage','ucode'));
    }

    public function previewInvest(Request $request)
    {
//        dd($request->all());
        $currency = base_currency();

        $input = $request->validate([
            'scheme' => 'required',
//            'leverage' => 'required',
            'amount' => 'required|numeric|not_in:0',
            'account_from' => 'required',
            'profit_share_user' => 'required',
            'profit_share_admin' => 'required',
            'payouts' => 'required',
            'leverage_amount' => 'nullable',
            'days_to_pass' => 'nullable',
            'profit_split_amount' => 'nullable',
            'weekly_payout' => 'nullable',
            'swap_free' => 'nullable',
        ],
            [
                'scheme.required' => __("Please choose an funded plan."),
                'amount.*' => __("Please select payment plan."),
                'account_from.required' => __("Please select your payment source.")
            ]);

        $plan = PricingScheme::find(get_hash($input['scheme']));
//        dd($plan);

        $input['currency'] = $currency;
        $input['account_type'] = 'normal';
//        dd($input);
        // Payment Source
        $account = 'unknown';
        $amount = ($plan->amount) ? (float)$plan->amount : 0;
        if($plan->is_discount == 1 && $plan->discount_price > 0) {
            // Amount & Balance
            $amount = ($plan->discount_price) ? (float)$plan->discount_price : 0;
        }

        $discount = isset($request['discount']) ? (float)percentage_calc($request['discount'],$amount): 0;
//        dd($request->get('discount'),$discount);
        $leverage_amount = isset($request['leverage_amount']) ? (float)percentage_calc(get_hash($request['leverage_amount']),$amount): 0;
        $days_to_pass_amount = isset($request['day_to_pass']) ? (float)percentage_calc(get_hash($request['day_to_pass']),$amount) : 0;
        $profit_split_amount = isset($request['profit_split_amount']) ? (float)percentage_calc(get_hash($request['profit_split_amount']),$amount) : 0;
        $payout_amount = isset($request['weekly_payout']) ? (float)(percentage_calc(get_hash($request['weekly_payout']),$amount)) : 0;
        $swap_free_amount = isset($request['swap_free']) ? (float)(percentage_calc(get_hash($request['swap_free']),$amount)) : 0;
//        dd( isset($request['swap_free']),get_hash($request['swap_free']),$swap_free_amount);
        $totalAmount = BigDecimal::of($amount)->plus($leverage_amount)->plus($days_to_pass_amount)->plus($profit_split_amount)->plus($payout_amount)->plus($swap_free_amount);
        $payableAmount = to_minus($totalAmount , $discount);
//        $actualWithdraw = $payableAmount;
//        dd($totalAmount,$discount,$swap_free_amount);

        $input['bank'] = [];
        if($request->payment_type == 'wallets') {
            $accountFrom = explode('_', $request->get('account_from'));

        }
//        elseif($request->payment_type == 'banks'){
//            $accountFrom = explode('_', $request->get('account_from_banks'));
//            $bank = Bank::find(get_hash($accountFrom[1]));
//            $input['bank'] = $bank->toArray();
//        }
//        dd($input['bank']);
        $type = get_hash($accountFrom[0]);
        $source = get_hash($accountFrom[1]);
//        dd($source);

        if ($type == 'wallets') {
            $accountName = w2n($source);
//            $source = AccountBalanceType::MAIN;
            $accountId = $source;
            $balance = getAccountBalance($source, true);
            $fx = null;
        }
        else if($type == 'forex') {
            $forexTrading = ForexTrading::find($source);
//            dd($forexTrading);
            $accountName = $forexTrading->account_name;
            $source = $forexTrading->login;
            $accountId = $forexTrading->id;
            $login = $forexTrading->login;
//            dd($login);
            $balance = $this->getForexAccountBalance($login);
            $fx = $forexTrading;
            if ($fx->currency == 'USC') {
                $scale = (is_crypto($fx->currency)) ? dp_calc('crypto') : dp_calc('fiat');
//                $amount = BigDecimal::of($amount)->dividedBy(100, $scale, RoundingMode::HALF_DOWN);
                $payableAmount = round($payableAmount * 100, $scale);
            }
        }

        if(in_array($type,['wallets','forex'])) {
            if (empty($payableAmount)) {
                throw ValidationException::withMessages([
                    'amount' => __('Sorry, the funded amount is not valid.')
                ]);
            } elseif (empty($balance)) {
                throw ValidationException::withMessages([
                    'account' => __('Sorry, not enough balance in selected account.')
                ]);
            }
            if (BigDecimal::of($payableAmount)->compareTo($balance) > 0) {
                throw ValidationException::withMessages(['amount' => ['title' => __('Insufficient balance!'), 'message' => __('The amount exceeds your available funds.')]]);

            }
        }
        // Funded Plan
        if (blank($plan) || (!blank($plan) && $plan->status != PricingInvestmentStatus::ACTIVE)) {
            throw ValidationException::withMessages(['plan' => __('The selected plan may not available or invalid.')]);
        }

        $input['source'] = $source;
        $input['max_drawdown_limit'] = $plan->max_drawdown_limit;
        $input['daily_drawdown_limit'] = $plan->daily_drawdown_limit;
        $input['profit_share_user'] = $plan->profit_share_user;
        $input['profit_share_admin'] = $plan->profit_share_admin;
        $input['leverage'] = $plan->leverage;
        $input['type'] = $type;
        $input['receipt'] = '';
//        if($type == 'banks'){
            $input['name'] = $request->name;
            $input['email'] = $request->email;
            $input['phone_number'] = $request->phone_number;
            $input['profile_whatsapp'] = $request->profile_whatsapp;
//        }
//        if($leverage_amount > 0){
//            $input['leverage'] = $input['leverage'] + 50;
//        }
        if($days_to_pass_amount > 0){
            $input['days_to_pass'] ='unlimited';
        }
//        if($profit_split_amount > 0){
//            $input['profit_share_user'] = 90;
//            $input['profit_share_admin'] = 10;
//        }
        if($payout_amount > 0){
            $input['payouts'] ='weekly';
        }
        if($swap_free_amount > 0){
//            $input['payouts'] ='weekly';
        }
//        dd($input);
        $subscription = $this->investment->processSubscriptionDetails($input, $plan, $amount,$discount,$leverage_amount,$days_to_pass_amount,$profit_split_amount,$payout_amount,$swap_free_amount);
//        dd($subscription);

        if (empty($subscription)) {
            throw ValidationException::withMessages(["scheme" => __("Sorry unable process subscription")]);
        }

        return $this->wrapInTransaction(function ($subscription,$plan) {
            $invest = $this->investment->confirmSubscription($subscription);
//            event(new FundedEvent($invest));
            if ($plan->approval == FundedApproval::AUTO) {
            $this->investment->approveSubscription($invest, 'auto-approved');
            $invest->fresh();
            }

//            try {
//                ProcessEmail::dispatch('investment-placed-customer', data_get($invest, 'user'), null, $invest);
//                ProcessEmail::dispatch('investment-placed-admin', data_get($invest, 'user'), null, $invest);
//            } catch (\Exception $e) {
//                save_mailer_log($e, 'investment-placed');
//            }

            return ['append'=> view("frontend::investment.invest.success", compact('invest'))->render()];
//       return response()->json(['append'=>$append]);
            }, $subscription,$plan);

    }

    public function confirmInvest($subscription)
    {
//        $subscription = $request->session()->get('invest_details');
//        dd($subscription);

        if (empty($subscription)) {
            $errors = MsgState::of('wrong', 'invest');
            return view("frontend::investment.invest.failed", $errors);
        }
        return $this->wrapInTransaction(function ($subscription) {
            $invest = $this->investment->confirmSubscription($subscription);
//            dd(iv_start_automatic());
//            if (iv_start_automatic()) {
                $this->investment->approveSubscription($invest, 'auto-approved');
                $invest->fresh();
//            }
//            event(new NewInvestmentEvent($invest));
//            try {
//                ProcessEmail::dispatch('investment-placed-customer', data_get($invest, 'user'), null, $invest);
//                ProcessEmail::dispatch('investment-placed-admin', data_get($invest, 'user'), null, $invest);
//            } catch (\Exception $e) {
//                save_mailer_log($e, 'investment-placed');
//            }

            return view("frontend::investment.invest.success", compact('invest'));
        }, $subscription);
    }

    public function cancelInvestment($id, Request $request)
    {
        $invest = PricingInvestment::loggedUser()->where('id', get_hash($id))
            ->where('status', InvestmentStatus::PENDING)
            ->first();

        if (blank($invest) || (data_get($invest, 'user_can_cancel') == false)) {
            throw ValidationException::withMessages(['id' => __('Sorry unable to cancel investment!')]);
        }

        return $this->wrapInTransaction(function ($invest) {
            $this->investment->cancelSubscription($invest);

//            try {
//                ProcessEmail::dispatch('investment-cancel-user-customer', data_get($invest, 'user'), null, $invest);
//                ProcessEmail::dispatch('investment-cancel-user-admin', data_get($invest, 'user'), null, $invest);
//            } catch (\Exception $e) {
//                save_mailer_log($e, 'investment-cancel-user-customer');
//            }

            return response()->json(['msg' => __('Funded cancelled successfully!')]);
        }, $invest);
    }

    private function getID($uid)
    {
        $theID = str_replace('IV', '', substr($uid, 0, -3));
        return (int)$theID;
    }

    private function getPlan($uid)
    {
        $id = $this->getID($uid);
        $plan = PricingScheme::where('id', $id)->where('status', SchemeStatus::ACTIVE)->first();

        if (!blank($plan)) {
            $data[$plan->uid_code] = [
                'amount' => $plan->amount,
//                'fixed' => $plan->is_fixed,
//                'min' => ($plan->amount) ? money($plan->amount, base_currency()) : 0,
//                'max' => ($plan->maximum) ? money($plan->maximum, base_currency()) : 0
            ];

            return (object)['plan' => $plan, 'data' => $data];
        }

        return false;
    }

    private function getSchemes()
    {
        $schemeQuery = PricingScheme::query();
//        $schemeOrder = sys_settings('iv_plan_order');

//        switch ($schemeOrder) {
//            case "reverse":
//                $schemeQuery->orderBy('id', 'desc');
//                break;
//            case "random":
//                $schemeQuery->inRandomOrder();
//                break;
//            case "featured":
//                $schemeQuery->orderBy('featured', 'desc');
//                break;
//        }

        $schemes = $schemeQuery->where('status', SchemeStatus::ACTIVE)->get();

        return (!blank($schemes)) ? $schemes : false;
    }

    public function showData(Request $request)
    {
//        dd($request->all());
        $schemeQuery = PricingScheme::query();
//        $schemeOrder = sys_settings('iv_plan_order');
        $type = $request->type;
        if($type == 'challenge'){
            $type = FundedSchemeTypes::CHALLENGE_FUNDING;
        }elseif($type == 'direct'){
            $type = FundedSchemeTypes::DIRECT_FUNDING;
        }

        $subType = $request->sub_type;
        $stage = $request->stage;
        // if(isset($type)){
        //     $schemeQuery->where('type', $type);
        // }
        // if(isset($subType)){
        //     $schemeQuery->where('sub_type', $subType);
        // }
        // if($type == FundedSchemeTypes::CHALLENGE_FUNDING && $subType == FundedSchemeSubTypes::TWO_STEP_CHALLENGE){
        //     $schemeQuery->where('stage', $stage);
        // }
//        switch ($schemeOrder) {
//            case "reverse":
//                $schemeQuery->orderBy('id', 'desc');
//                break;
//            case "random":
//                $schemeQuery->inRandomOrder();
//                break;
//            case "featured":
                $schemeQuery->orderBy('amount', 'asc');
//                break;
//        }

        $plans = $schemeQuery->where('status', SchemeStatus::ACTIVE)->orderBy('amount', 'desc')->get();
    //    dd($plans);

        $response = view('frontend::investment.invest-plans-render',compact('plans'))->render() ;
        return response()->json(['append' => $response, 'reload' => false]);

    }
    public function showDataInvest(Request $request)
    {
    //    dd($request->all());
        $investCode = $request->invest_id;
        $invest = PricingScheme::find(get_hash($investCode));
//        dd($invest);

        $schemeQuery = PricingScheme::query();
//        $schemeOrder = sys_settings('iv_plan_order');
        $type = $request->type;

        if($type == 'challenge'){
            $type = FundedSchemeTypes::CHALLENGE_FUNDING;
        }elseif($type == 'direct'){
            $type = FundedSchemeTypes::DIRECT_FUNDING;
        }

        $subType = $request->sub_type;
        $stage = $request->stage;
        // if(isset($type)){
        //     $schemeQuery->where('type', $type);
        // }
        // if(isset($subType)){
        //     $schemeQuery->where('sub_type', $subType);
        // }
        // if($type == FundedSchemeTypes::CHALLENGE_FUNDING && $subType == FundedSchemeSubTypes::TWO_STEP_CHALLENGE){
        //     $schemeQuery->where('stage', $stage);
        // }
//        switch ($schemeOrder) {
//            case "reverse":
//                $schemeQuery->orderBy('id', 'desc');
//                break;
//            case "random":
//                $schemeQuery->inRandomOrder();
//                break;
//            case "featured":
                $schemeQuery->orderBy('amount', 'asc');
//                break;
//        }

        $plans = $schemeQuery->where('status', SchemeStatus::ACTIVE)->orderBy('amount', 'desc')->get();
//        dd($plans);
        $valueToCheck = $invest->id;

        if ($plans->contains(function ($item) use ($valueToCheck) {
            return $item['id'] === $valueToCheck;
        })) {
            // Value exists in the collection
//            echo "Value exists.";
        } else {
            // Value does not exist in the collection
            $invest = $plans->first();;
        }
//        dd($plans,$invest);
        $response = view('frontend::investment.invest-render',compact('plans','invest'))->render() ;
//       dd($response);
        return response()->json(['append' => $response, 'reload' => false]);

    }
}
