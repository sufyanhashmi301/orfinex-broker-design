<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Boolean;
use App\Enums\InterestRateType;
use App\Enums\PricingInvestmentStatus;
use App\Enums\SchemeStatus;


use App\Models\PricingInvestment;
use App\Models\PricingScheme;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Services\PricingInvestormService;

class PricingInvestmentSchemeController extends Controller
{
    public function schemeList(Request $request, $status = null)
    {
        $schemes = PricingScheme::query();

        if ($status) {
            $schemes->withoutGlobalScope('exceptArchived')->where('status', $status);
        }

        $schemes = $schemes->orderBy('id', 'asc')->get();
//        dd($schemes);
        if ($request->isXmlHttpRequest()) {
            return view("backend.pricing.schemes.cards", compact('schemes', 'status'))->render();
        }

        return view("backend.pricing.schemes.list", compact('schemes', 'status'));
    }

    public function actionScheme(Request $request, $action=null)
    {
//        dd($request->all(),$action);
        $type = (!empty($action)) ? $action : $request->get('view');
//dd($type);
        if(!in_array($type, ['new', 'edit'])) {
            throw ValidationException::withMessages(['action' => __("Sorry, we are unable to proceed your action.")]);
        }
        $scheme = compact([]);
        $schmeID = ($request->get('uid')) ? $request->get('uid') : $request->get('id');
//dd(get_hash($schmeID));
        if ($schmeID) {
            $scheme = PricingScheme::find(get_hash($schmeID));
            if (blank($scheme)) {
                throw ValidationException::withMessages(['id' => __("The scheme id invalid or may not avialble.")]);
            }
        }
        if ($type=='new' && !has_route('admin.pricing.scheme.save')) {
//            dd($type);
            return view("backend.pricing.schemes.form", compact('scheme', 'type'));

//            throw ValidationException::withMessages(['extended' => __("Sorry, the adding feature is not avilable. It may avialble in extended / pro version.")]);
        }

        return view("backend.pricing.schemes.form", compact('scheme', 'type'));
    }
    public function updateSchemeStatus(Request $request)
    {
        $input = $request->validate([
            'uid' => 'required',
            'action' => 'required',
        ]);
//        dd($request->all());

        $ivScheme = PricingScheme::withoutGlobalScope('exceptArchived')->find(get_hash(Arr::get($input, 'uid')));

        if (blank($ivScheme)) {
            throw ValidationException::withMessages(["id" => __("The funded scheme id is invalid or not found.")]);
        }

        $allowedStatuses = PricingScheme::NEXT_STATUSES[$ivScheme->status] ?? [];

        $status = Arr::get($input, 'action');
        if (!in_array($status, $allowedStatuses)) {
            throw ValidationException::withMessages(['status' => __("Scheme status cannot be updated to :state", ["state" => $status]) ]);
        }

        // $ivList = $request->route('status');
        $ivlistStatus =  session()->get('ivlistStatus');
        $ivScheme->status = $status;
        $ivScheme->save();

//        if($status == SchemeStatus::ARCHIVED){
//            $ivScheme->delete();
//        }

        return response()->json([
            'type' => 'success',
            'title' => __("Status Updated"),
            'msg' => __('The funded scheme (:name) status updated to :state', ['name' => $ivScheme->name, "state" => $status]),
            'embed' => $this->schemeList($request, $ivlistStatus)
        ]);
    }
    public function updateScheme(Request $request, $id=null)
    {
//        dd($request->all());
        $schemeID = (!empty($id)) ? get_hash($id) : get_hash($request->get('uid'));

        if($schemeID != $request->get('id')) {
            throw ValidationException::withMessages([ 'invalid' => __('The funded scheme id is invalid or not found.') ]);
        }

        $scheme = PricingScheme::find($schemeID);
//        dd($scheme);
        $slug = isset($scheme->slug) ? $scheme->slug : '';

        $request->validate([
            "name" => 'required|string',
            "short" => 'required|string',
            "desc" => 'nullable|string',
            "amount" => 'required|numeric|gte:0.01',
            "amount_allotted" => 'required|numeric|gte:0.01',
            "profit_share_user" => 'required|numeric|gte:0.01|max:100',
            "profit_share_admin" => 'required|numeric|gte:0.01|max:100',
            "daily_drawdown_limit" => 'nullable|numeric',
            "swap_group" => 'required|string',
            "swap_amount" => 'nullable|numeric',
            "leverage" => 'required|integer',
            "swap_free_group" => 'required|string',
            "swap_free_amount" => 'nullable|numeric',
            "max_drawdown_limit" => 'nullable|numeric',
            "min_trading_days" => 'nullable|numeric',
            "max_trading_days" => 'nullable|numeric',
            "discount_price" => 'nullable|numeric',
            "profit_target" => 'nullable|numeric',
        ], [
            "amount.numeric" => __("The funded amount should be valid number."),

        ]);

//        if($this->existNameSlug($request->get('name'), $slug)==true) {
//            throw ValidationException::withMessages([ 'name' => __('The funded scheme (:name) already exist. Please try with different name.', ['name' => $request->get('name')]) ]);
//        }

//        if( !($request->get('fixed')) && $request->get('maximum') > 0 && $request->get('amount') >= $request->get('maximum') ) {
//            throw ValidationException::withMessages(['maximum' => __('The maximum amount should be zero or more than minimum amount of investment.')]);
//        }
//
//        if( !array_key_exists($request->get("period"), PricingInvestormService::TERM_CONVERSION[$request->get('duration')]) ) {
//            throw ValidationException::withMessages(['period' => __('Interest period is not valid for term duration.')]);
//        }
//dd($request->get('amount'));
        $data = [
            "name" => strip_tags($request->get("name")),
            "slug" => Str::slug(strip_tags($request->get("name"))),
            "short" => strip_tags($request->get('short')),
            "desc" => strip_tags($request->get('desc')),
            "amount" => $request->get('amount'),
            "amount_allotted" => $request->get('amount_allotted'),
            "leverage" =>$request->get('leverage'),
            "groups" => $request->get('groups'),
            "type" => $request->get('type'),
            "sub_type" => $request->get('sub_type'),
            "profit_share_user" => $request->get('profit_share_user'),
            "profit_share_admin" => $request->get('profit_share_admin'),
            "profit_target" => $request->get('profit_target'),
//            "is_fixed" => $request->get('fixed') ? Boolean::YES : Boolean::NO,
            "daily_drawdown_limit" => $request->get("daily_drawdown_limit"),
            "max_drawdown_limit" => $request->get("max_drawdown_limit"),
            "min_trading_days" => $request->get("min_trading_days"),
            "max_trading_days" => $request->get("max_trading_days"),
            "discount_price" => $request->get("discount_price"),
            "stage" => $request->get("stage"),
            "is_highlighted" => $request->get("is_highlighted"),
            "approval" => $request->get("approval"),
            "swap_group" => $request->get("swap_group"),
            "swap_amount" => $request->get("swap_amount"),
            "swap_free_group" => $request->get("swap_free_group"),
            "swap_free_amount" => $request->get("swap_free_amount"),
//            "min_rate" => $request->get("min_rate"),
//            "rate" => $request->get("rate"),
//            "rate_type" => $request->get("types"),
//            "calc_period" => $request->get("period"),
//            "days_only" => $request->get("daysonly") ? Boolean::YES : Boolean::NO,
//            "capital" => $request->get("capital") ? Boolean::YES : Boolean::NO,
//            "payout" => $request->get("payout"),
//            "featured" => $request->get('featured') ? Boolean::YES : Boolean::NO,
            "ea_boat" => $request->get('ea_boat') ? Boolean::YES : Boolean::NO,
            "trading_news" => $request->get('trading_news') ? Boolean::YES : Boolean::NO,
            "re_attempt_discount" => $request->get('re_attempt_discount') ? Boolean::YES : Boolean::NO,
            "weekend_holding" => $request->get('weekend_holding') ? Boolean::YES : Boolean::NO,
            "refundable" => $request->get('refundable') ? Boolean::YES : Boolean::NO,
            "is_discount" => $request->get('is_discount') ? Boolean::YES : Boolean::NO,
            "status" => $request->get('status') ? SchemeStatus::ACTIVE : SchemeStatus::INACTIVE

        ];
//        dd($data);
        if (!blank($scheme)){
//            $referral_data =$data;


            $data['status'] = $request->get('status') ? SchemeStatus::ACTIVE : SchemeStatus::INACTIVE;


            $scheme->fill($data);
            $scheme->save();
//            dd($scheme);

//            $rateShort = substr($scheme->rate_type, 0, 1);

            $totalInvests = PricingInvestment::where('pricing_scheme_id',$scheme->id)->get();
            foreach ($totalInvests as $totalInvest) {
                $totalInvest->account_name = $scheme->name;
                $totalInvest->profit_share_user = $scheme->profit_share_user;
                $totalInvest->profit_share_admin = $scheme->profit_share_admin;
                $totalInvest->max_drawdown_limit = $scheme->max_drawdown_limit;
                $totalInvest->daily_drawdown_limit = $scheme->daily_drawdown_limit;

                $totalInvest->save();
            }

            return response()->json([
                'msg' => __('The Funded scheme has been updated.'),
                'title' => 'Scheme Updated',
                'modal' => 'hide',
                'embed' => $this->schemeList($request, $request->route('status')),
            ]);
        } else {
            throw ValidationException::withMessages(['failed' => __('Unable to update the scheme, please try again.')]);
        }
    }
    public function saveScheme(Request $request, $id=null)
    {
//        dd($request->all(),$id);
//        $schemeID = (!empty($id)) ? get_hash($id) : get_hash($request->get('uid'));
//
//        if($schemeID != $request->get('id')) {
//            throw ValidationException::withMessages([ 'invalid' => __('The funded scheme id is invalid or not found.') ]);
//        }

//        $scheme = PricingScheme::find($schemeID);
//        dd($scheme);
//        $slug = isset($scheme->slug) ? $scheme->slug : '';

        $request->validate([
            "name" => 'required|string',
            "short" => 'required|string',
            "desc" => 'nullable|string',

            "amount" => 'required|numeric|gte:0.01',
            "amount_allotted" => 'required|numeric|gte:0.01',
            "profit_share_user" => 'required|numeric|gte:0.01|max:100',
            "profit_share_admin" => 'required|numeric|gte:0.01|max:100',
            "daily_drawdown_limit" => 'nullable|numeric',
            "swap_group" => 'required|string',
            "swap_amount" => 'nullable|numeric',
            "leverage" => 'required|integer',
            "swap_free_group" => 'required|string',
            "swap_free_amount" => 'nullable|numeric',
            "max_drawdown_limit" => 'nullable|numeric',
            "min_trading_days" => 'nullable|numeric',
            "max_trading_days" => 'nullable|numeric',
            "discount_price" => 'nullable|numeric',
            "profit_target" => 'nullable|numeric',
        ], [
            "amount.numeric" => __("The funded amount should be valid number."),

        ]);
        $slug = Str::slug(strip_tags($request->get("name")));
//        dd($slug,$this->existNameSlug($request->get('name'),$slug));
//        if($this->existNameSlug($request->get('name'))==true) {
//            throw ValidationException::withMessages([ 'name' => __('The funded scheme (:name) already exist. Please try with different name.', ['name' => $request->get('name')]) ]);
//        }

//        if( !($request->get('fixed')) && $request->get('maximum') > 0 && $request->get('amount') >= $request->get('maximum') ) {
//            throw ValidationException::withMessages(['maximum' => __('The maximum amount should be zero or more than minimum amount of investment.')]);
//        }
//
//        if( !array_key_exists($request->get("period"), PricingInvestormService::TERM_CONVERSION[$request->get('duration')]) ) {
//            throw ValidationException::withMessages(['period' => __('Interest period is not valid for term duration.')]);
//        }
//dd($request->get('amount'));
        $data = [
            "name" => strip_tags($request->get("name")),
            "slug" => $slug,
            "short" => strip_tags($request->get('short')),
            "desc" => strip_tags($request->get('desc')),
            "amount" => $request->get('amount'),
            "amount_allotted" => $request->get('amount_allotted'),
            "leverage" =>$request->get('leverage'),
            "groups" => $request->get('groups'),
            "type" => $request->get('type'),
            "profit_share_user" => $request->get('profit_share_user'),
            "profit_share_admin" => $request->get('profit_share_admin'),
            "profit_target" => $request->get('profit_target'),
//            "is_fixed" => $request->get('fixed') ? Boolean::YES : Boolean::NO,
            "daily_drawdown_limit" => $request->get("daily_drawdown_limit"),
            "max_drawdown_limit" => $request->get("max_drawdown_limit"),
            "min_trading_days" => $request->get("min_trading_days"),
            "max_trading_days" => $request->get("max_trading_days"),
            "discount_price" => $request->get("discount_price"),
            "stage" => $request->get("stage"),
//            "is_highlighted" => $request->get("is_highlighted"),
            "approval" => $request->get("approval"),
            "swap_group" => $request->get("swap_group"),
            "swap_amount" => $request->get("swap_amount"),
            "swap_free_group" => $request->get("swap_free_group"),
            "swap_free_amount" => $request->get("swap_free_amount"),
//            "min_rate" => $request->get("min_rate"),
//            "rate" => $request->get("rate"),
//            "rate_type" => $request->get("types"),
//            "calc_period" => $request->get("period"),
//            "days_only" => $request->get("daysonly") ? Boolean::YES : Boolean::NO,
//            "capital" => $request->get("capital") ? Boolean::YES : Boolean::NO,
//            "payout" => $request->get("payout"),
//            "featured" => $request->get('featured') ? Boolean::YES : Boolean::NO,
            "ea_boat" => $request->get('ea_boat') ? Boolean::YES : Boolean::NO,
            "trading_news" => $request->get('trading_news') ? Boolean::YES : Boolean::NO,
            "re_attempt_discount" => $request->get('re_attempt_discount') ? Boolean::YES : Boolean::NO,
            "weekend_holding" => $request->get('weekend_holding') ? Boolean::YES : Boolean::NO,
            "refundable" => $request->get('refundable') ? Boolean::YES : Boolean::NO,
            "is_discount" => $request->get('is_discount') ? Boolean::YES : Boolean::NO,
            "status" => $request->get('status') ? SchemeStatus::ACTIVE : SchemeStatus::INACTIVE

        ];
//        dd($data);
//        if (!blank($scheme)){
//            $referral_data =$data;


            $data['status'] = $request->get('status') ? SchemeStatus::ACTIVE : SchemeStatus::INACTIVE;

            PricingScheme::create($data);
//            $scheme->fill($data);
//            $scheme->save();
//            dd($scheme);

//            $rateShort = substr($scheme->rate_type, 0, 1);

//            $totalInvests = PricingInvestment::where('pricing_scheme_id',$scheme->id)->get();
//            foreach ($totalInvests as $totalInvest) {
//                $termStart = CarbonImmutable::parse($totalInvest->term_start);
//                $termEnd = $termStart->add($scheme->term. ' '.ucfirst($scheme->term_type))->addMinutes(1)->addSeconds(5);
//
//                $totalInvest->min_rate = $scheme->min_rate;
//                $totalInvest->rate = $scheme->rate . ' ('.ucfirst($rateShort).')';
//                $totalInvest->term_total = $scheme->term;
//                $totalInvest->profit = $this->netProfit($scheme,$totalInvest->amount,$scheme->term,$totalInvest->currency);
//                $totalInvest->term = $scheme->term. ' '.ucfirst($scheme->term_type);
//                $totalInvest->total = to_sum($totalInvest->amount, $totalInvest->profit);
//                $totalInvest->term_end = $termEnd;
//                $totalInvest->scheme =  $this->exceptData($scheme->toArray());
//                $totalInvest->desc =  data_get($scheme, 'name').' - '.data_get($scheme, 'calc_details');
//                $totalInvest->save();
//            }

            return response()->json([
                'msg' => __('The Funded scheme has been updated.'),
                'title' => 'Scheme Updated',
                'modal' => 'hide',
                'embed' => $this->schemeList($request, $request->route('status')),
            ]);
//        } else {
//            throw ValidationException::withMessages(['failed' => __('Unable to update the scheme, please try again.')]);
//        }
    }
    private function netProfit($scheme,$amount,$totalTermCount,$currency)
    {
        $rate = data_get($scheme, 'rate');
        $rateType = data_get($scheme, 'rate_type');
        $amount = $amount;
        $count = $totalTermCount;
        $scale = (is_crypto($currency)) ? dp_calc('crypto') : dp_calc('fiat');
//        dd($rate,$rateType,$amount,$count,$scale);

        $profitAmount = 0;
        if ($rateType == InterestRateType::PERCENT) {
            $profitAmount = BigDecimal::of($amount)->multipliedBy(BigDecimal::of($rate))->multipliedBy(BigDecimal::of($count))->dividedBy(100, $scale, RoundingMode::CEILING);
        } elseif($rateType == InterestRateType::FIXED) {
            $profitAmount = BigDecimal::of($rate)->multipliedBy(BigDecimal::of($count));
        }
        $finalAmount = is_object($profitAmount) ? (string) $profitAmount : $profitAmount;
        return (float) $finalAmount;
    }
    private function exceptData($data)
    {
        $except = ['id', 'desc', 'slug', 'featured', 'created_at', 'updated_at'];

        if (is_array($data)) {
            return Arr::except($data, $except);
        }

        return $data;
    }

    private function existNameSlug($name, $old=null) {
        $slug = Str::slug($name);
//        $scheme = PricingScheme::where('slug', $slug)->first();
        $scheme = DB::table('pricing_schemes')->where('slug', $slug)->first();


        if ($slug==$old || blank($scheme)) return false;

        return true;
    }
}
