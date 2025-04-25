<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\AdvertisementMaterial;
use App\Models\IbQuestion;
use App\Models\Language;
use App\Models\LevelReferral;
use App\Models\MultiLevel;
use App\Models\ReferralRelationship;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReferralController extends Controller
{
    use ForexApiTrait;
    public function ibRequest()
    {
        if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED || isset(auth()->user()->ref_id)){
            return redirect()->route('user.multi-level.ib.dashboard');
        }
//        dd('ss');
        if (! setting('sign_up_referral', 'permission')) {
            abort('404');
        }
        $user = auth()->user();

        $getReferral = $user->getReferrals()->first();
//        $totalReferralProfit = $user->totalReferralProfit();

        $level = LevelReferral::max('the_order');
        $balance = isset(auth()->user()->ib_balance) ? BigDecimal::of(auth()->user()->ib_balance) : BigDecimal::of(0);

        $ibQuestions = IbQuestion::where('status', true)->get();
        $qrCode = QrCode::size(300)->generate($getReferral->link);

        return view('frontend::partner.ib_request', compact( 'getReferral',  'level', 'balance', 'ibQuestions', 'qrCode'));
    }
    public function referral()
    {
        if(auth()->user()->ib_status != \App\Enums\IBStatus::APPROVED && !isset(auth()->user()->ref_id)){

        }

        if(auth()->user()->ib_status == \App\Enums\IBStatus::APPROVED){
            return redirect()->route('user.multi-level.ib.dashboard');
        }
        $user = auth()->user();

        $getReferral = $user->getReferrals()->first();
//        $totalReferralProfit = $user->totalReferralProfit();

        $level = LevelReferral::max('the_order');


        return view('frontend::partner.referrals', compact( 'getReferral',  'level'));
    }
    public function referralMembers(Request $request)
    {
        $user = auth()->user();

        // Capture the selected level order from the request, defaulting to '0' for all levels
        $selectedLevel = $request->input('level_order', 0);

        $referrals = User::where('ref_id', $user->id)->get();

        $maxLevelOrder = 0;

        $maxLevelOrderCount = $maxLevelOrder;

        return view('frontend::referral.index', compact('maxLevelOrderCount', 'referrals', 'selectedLevel'));
    }

    public function advertisementMaterial(Request $request)
    {
//        dd($request->all());
        $language = $request->input('language');
        $advertisements = AdvertisementMaterial::where('status', true);

        if ($language) {
            $advertisements->where('language', $language);
        }
        $advertisements = $advertisements->get()->groupBy('type');
//        dd($advertisements);

        if ($request->ajax()) {
            return view('frontend::referral.include.__advertisement_material_partial', compact('advertisements'));
        }
        $languages = Language::where('status', true)->get();
        return view('frontend::referral.index', compact('advertisements','languages'));
    }

    public function reports()
    {

        $user = auth()->user();
        if (setting('site_referral', 'global') == 'level') {
            $referrals = Transaction::where('user_id', $user->id)->where('target_type', '!=', null)->where('is_level', '=', 1)->get()->groupBy('level');
        } else {
            $referrals = Transaction::where('user_id', $user->id)->where('target_type', '!=', null)->get()->groupBy('target');
        }
        $generalReferrals = Transaction::where('user_id', $user->id)->where('target_type', null)->where('type', TxnType::Referral)->latest()->paginate(8);

        $getReferral = $user->getReferrals()->first();
        $totalReferralProfit = $user->totalReferralProfit();

        return view('frontend::referral.index', compact('referrals', 'getReferral', 'totalReferralProfit', 'generalReferrals'));
    }

    public function history()
    {
        $query = Transaction::where('user_id', auth()->user()->id)->where('type', '=', 'ib_bonus');;

        if (request('transaction_date')) {
            $filter = request('transaction_date');

            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $query->where(function ($q) use ($dateRange) {
                    $start = $dateRange[0]->toDateTimeString();
                    $end = $dateRange[1]->toDateTimeString();

                    $q->whereRaw("
                COALESCE(
                    JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.time')),
                    created_at
                ) BETWEEN ? AND ?
            ", [$start, $end]);
                });
            }
        }


        if (request('transaction_status')) {
            $query->where('status', request('transaction_status'));
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10)->appends(request()->query());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('frontend::user.transaction.include.__transaction_row', compact('transactions'))->render(),
                'pagination' => (string) $transactions->links(),
                'total' => $transactions->total(),
            ]);
        }

        return view('frontend::referral.index', compact('transactions'));
    }

    public function network() {
        $level = LevelReferral::max('the_order');
//        dd($level);
        return view('frontend::referral.index', compact( 'level'));
    }

    public function download($filename)
    {
        if (!File::exists('assets/'.$filename)) {
            notify()->error(__('file not exists'), __('Error'));
            return redirect()->back();
        }
        return response()->download('assets/'.$filename);
    }

}
