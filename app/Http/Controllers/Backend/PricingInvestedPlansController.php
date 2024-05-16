<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ActionType;
use App\Enums\PricingPricingInvestmentStatus;
use App\Enums\SchemeStatus;
use App\Enums\UserRoles;
use App\Enums\UserStatus;
use App\Enums\TransactionType;
use App\Enums\TransactionStatus;
use App\Enums\PaymentMethodStatus;
use App\Enums\PricingInvestmentStatus;

use App\Filters\PricingPlansFilter;
use App\Helpers\MsgState;


use App\Models\PricingScheme;
use App\Models\PricingInvestment;
use App\Models\User;

use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\WithdrawMethod;

use App\Filters\TransactionFilter;
use App\Filters\PlansFilter;
use App\Jobs\ProcessEmail;

use App\Services\PricingInvestormService;
use Brick\Math\BigDecimal;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;


class PricingInvestedPlansController extends Controller
{
    private $investment;

    public function __construct(PricingInvestormService $investment)
    {
        $this->investment = $investment;
    }

    public function investedPlanList(Request $request, $status = null)
    {
        if (is_null($status) || !in_array($status, array_keys(get_enums(PricingInvestmentStatus::class)))) {
            $whereIn = [PricingInvestmentStatus::PENDING, PricingInvestmentStatus::ACTIVE, PricingInvestmentStatus::INACTIVE, PricingInvestmentStatus::COMPLETED, PricingInvestmentStatus::CANCELLED];
            $listing = 'all';
        } else {
            $whereIn = [$status];
            $listing = $status;
        }
//        dd('ss');

        $filter = new PricingPlansFilter($request);
//        dd($filter);
        $pendingCount = PricingInvestment::where('status', PricingInvestmentStatus::PENDING)->count();
        $investmentQuery = PricingInvestment::whereIn('status', $whereIn)->orderBy('id', 'desc')->filter($filter);
        $investments = $investmentQuery->paginate(10);

        return view('backend.pricing.invest.list', compact('investments', 'listing', 'pendingCount'));
    }

    public function showInvestmentDetails($id)
    {
        $invest = PricingInvestment::find(get_hash($id));

        if(blank($invest)) {
            return redirect()->route('admin.pricing.list');
        }
//        try {
//            if (in_array($invest->status, [PricingInvestmentStatus::ACTIVE])) {
//                $this->wrapInTransaction(function($invest){
//                    $this->investment->processInvestmentProfit($invest);
//                }, $invest);
//            }
//        } catch (\Exception $e) {
//            save_error_log($e, 'invest-details');
//        }

//        $invest = $invest->fresh()->load(['profits' => function($q) {
//            $q->orderBy('id', 'desc');
//        }]);

//        $plans = PricingScheme::where('status','active')->get();
        $plans = PricingScheme::where('status','active')->where('id','!=',$invest->scheme_id)->get();
//dd($plans);
        return view("backend.pricing.invest.details", compact("invest",'plans'));
    }

    public function cancelInvestment(Request $request, $id=null)
    {
        $ivID = ($request->get("uid")) ? get_hash($request->get("uid")) : get_hash($id);

        $reload = (empty($request->get('reload')) || $request->get('reload')=='false') ? false : true;

        $invest = PricingInvestment::find($ivID);

        if (blank($invest) || in_array($invest->status, [PricingInvestmentStatus::CANCELLED, PricingInvestmentStatus::ACTIVE, PricingInvestmentStatus::COMPLETED])) {
            throw ValidationException::withMessages(['invalid' => __('Sorry, unable to cancel the funded plan.') ]);
        }

        return $this->wrapInTransaction(function ($invest, $reload) {
            $this->investment->cancelSubscription($invest);
            return response()->json([ 'title' => __("Plan Cancelled"), 'msg' => __('The funded plan has been cancelled.'), 'reload' => $reload ]);
        }, $invest, $reload);

        return response()->json([ 'type' => 'error', 'msg' => __('Sorry, unable to cancel the funded plan.') ]);
    }
    public function migrateInvestment(Request $request, $id=null)
    {
        $ivID = ($request->get("invetsment_id")) ? get_hash($request->get("invetsment_id")) : get_hash($id);

        //current Funded
        $investment = PricingInvestment::find($ivID);

        //migrate plan/new plan where user want to migrate
        $plan = PricingScheme::find(get_hash($request->get('plan_id')));
        if (blank($plan) || (!blank($plan) && $plan->status != SchemeStatus::ACTIVE)) {
            return response()->json(['error' => __("The selected plan may not available or invalid.")]);
        }
        // Payment Source
        $account = 'unknown';
        $source =  'wallet';
        if (in_array($source, ['wallet', 'account'])) {
            $account = ($source == 'account') ? AccType('invest') : AccType('main');
        } else {
            return response()->json(['error' => __("Sorry, your payment account is not valid.")]);
        }
        if ($plan->id == $investment->scheme_id) {
            return response()->json(['error' => __("Sorry, you are choosing same plan for migration! choose different plan .")]);
        }
        $amountRange = PricingScheme::where('id',$plan->id)->where('amount','<=',$investment->amount)->where('maximum','>=',$investment->amount)->exists();
        if (!$amountRange) {
            return response()->json(['error' => __("Sorry, your active plan amount is not in current plan range .")]);
        }

        //clear pending profits payouts
        $payoutPending = IvProfit::whereNull('payout')->where('invest_id',$investment->id)->orderBy('id', 'asc')->get()->groupBy('user_id');
        if (!blank($payoutPending)) {
            foreach ($payoutPending as $user_id => $profits) {
                $this->wrapInTransaction(function ($profits, $user_id) {
                    $profit_ids = $profits->keyBy('id')->keys()->toArray();
                    $investormService =  new InvestormService();
                    $investormService->proceedPayout($user_id, $profit_ids);
                }, $profits, $user_id);
            }
        }

        $invests = PricingInvestment::where('status', PricingInvestmentStatus::ACTIVE)->get();
        if (!blank($invests)) {
            foreach ($invests as $invest) {
                $this->wrapInTransaction(function ($invest) {
                    $investormService =  new InvestormService();
                    $investormService->processCompleteInvestment($invest);
                }, $invest);
            }
        }

        // Amount & Balance
        $amount = ($investment->amount) ? (float)$investment->amount : 0;
        $balance = user_balance($account);


        $input['amount'] = $amount;
        $input['source'] = $account;
        $input['currency'] = $investment->currency;

        //new plan migration process
        $investormService =  new InvestormService();
        $subscription = $investormService->processSubscriptionDetailsForMigration($investment->user_id,$input, $plan, $amount);
//        dd($subscription);

        $this->wrapInTransaction(function ($subscription,$investment) {
            $investormService =  new InvestormService();
            $invest = $investormService->confirmSubscriptionForMigration($subscription);

                $investormService->approveSubscriptionForMigration($invest, 'auto-approved');
                $invest->fresh();

//            try {
//                ProcessEmail::dispatch('investment-placed-customer', data_get($invest, 'user'), null, $invest);
//                ProcessEmail::dispatch('investment-placed-admin', data_get($invest, 'user'), null, $invest);
//            } catch (\Exception $e) {
//                save_mailer_log($e, 'investment-placed');
//            }
            Transaction::where('reference',$investment->ivx)->where('user_id',$investment->user_id)->delete();
            IvLedger::where('invest_id',$investment->id)->where('user_id',$investment->user_id)->delete();
            IvProfit::where('invest_id',$investment->id)->where('user_id',$investment->user_id)->delete();
            PricingInvestment::where('id',$investment->id)->delete();

        }, $subscription,$investment);

        return  response()->json(['reload' => true, 'success' => __("Successfully migrated plan.")]);

    }
    public function generateNewInvestmentDetails(): array
    {
        $termStart = CarbonImmutable::now();
//        dd($this->scheme->term_text);
        $termEnd = $termStart->add($this->scheme->term_text)->addMinutes(1)->addSeconds(5);
        $rateShort = substr($this->scheme->rate_type, 0, 1);
        $currency = base_currency();
//        dd($termStart,$this->scheme->term_text,$this->scheme->rate_type,$termEnd,$rateShort,$currency);

        if (empty($this->totalTermCount()) || empty($this->netProfit())) {
            return [];
        }
        return [
            "user_id" => data_get($this->user, 'id', auth()->user()->id),
            "scheme_id" => $this->scheme->id,
            "amount" => $this->getInvest(),
            "profit" => $this->netProfit(),
            "total" => $this->calculateTotal(),
            "received" => 0.0,
            "currency" => $currency,
            "min_rate" => $this->scheme->min_rate,
            "rate" => $this->scheme->rate . ' ('.ucfirst($rateShort).')',
            "term" => $this->scheme->term_text,
            "term_count" => 0,
            "term_total" => $this->totalTermCount(),
            "term_calc" => $this->scheme->calc_period,
            "scheme" => $this->exceptData($this->scheme->toArray()),
            "status" => PricingInvestmentStatus::PENDING,
            "source" => $this->source,
            "desc" => data_get($this->scheme, 'name').' - '.data_get($this->scheme, 'calc_details'),
            "term_start" => $termStart,
            "term_end" => $termEnd,
            "meta" => array('source' => $this->source, 'fees' => 0, 'exchange' => 0),
            "is_weekend" => $this->scheme->is_weekend,
        ];
    }

    public function processInvestment(): PricingInvestment
    {
        $source = Arr::get($this->details, 'source', AccType('main'));
        $amount = Arr::get($this->details, 'amount');

        $userId = auth()->user()->id;
//        dd($userId, $source);
        $userAccount = get_user_account($userId, $source);
        $userBalance = $userAccount->amount;

        if (BigDecimal::of($amount)->compareTo($userBalance) > 0) {
            throw ValidationException::withMessages([
                'amount' => __('Sorry, you do not have sufficient balance in your account for investment. Please make a deposit and try again once you have sufficient balance.')
            ]);
        }

        $invest = $this->saveInvestment();

        if (!blank($invest)) {
            $fees = BigDecimal::of(data_get($invest, 'meta.fees', 0));
            $userAccount->amount = BigDecimal::of($userAccount->amount)->minus(BigDecimal::of($invest->amount))->minus($fees);
            $userAccount->save();

            $this->saveIvAction(ActionType::ORDER, "invest", $invest->id);

            return $invest;
        } else {
            throw ValidationException::withMessages(['invest' => __("Unable to invest on selected plan. Please try again or contact us if the problem persists.")]);
        }
    }

    public function approveInvestment(Request $request, $id=null)
    {
        $ivID = ($request->get("uid")) ? get_hash($request->get("uid")) : get_hash($id);
        $ivInvestment = PricingInvestment::find($ivID);
//        dd($ivInvestment);
        if (filled($ivInvestment)) {
//            try {
                $this->wrapInTransaction(function ($ivInvestment, $request){
                    $this->investment->approveSubscription($ivInvestment, strip_tags($request->get('remarks')), strip_tags($request->get('note')));
//                    try {
//                        ProcessEmail::dispatch('investment-approved-customer', data_get($ivInvestment, 'user'), null, $ivInvestment);
//                        ProcessEmail::dispatch('investment-approved-admin', data_get($ivInvestment, 'user'), null, $ivInvestment);
//                    } catch (\Exception $e) {
//                        save_mailer_log($e, 'investment-placed');
//                    }
                }, $ivInvestment, $request);
                return response()->json(['title' => __("Plan Approved"), 'msg' => __('The funded plan has been approved and stated for profit distribution.'), 'reload' => true ]);
//            } catch (\Exception $e) {
//                save_error_log($e, 'invest-approve');
//            }
        }
        return response()->json([ 'type' => 'error', 'msg' => __('Sorry, unable to approve the funded plan.') ]);
    }
}
