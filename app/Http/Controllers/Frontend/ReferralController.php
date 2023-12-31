<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\IbQuestion;
use App\Models\LevelReferral;
use App\Models\Transaction;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReferralController extends Controller
{
    use ForexApiTrait;
    public function referral()
    {
//        dd('ss');
        if (! setting('sign_up_referral', 'permission')) {
            abort('404');
        }
        $user = auth()->user();

//        if (setting('site_referral', 'global') == 'level') {
//            $referrals = Transaction::where('user_id', $user->id)->where('target_type', '!=', null)->where('is_level', '=', 1)->get()->groupBy('level');
//        } else {
//            $referrals = Transaction::where('user_id', $user->id)->where('target_type', '!=', null)->get()->groupBy('target');
//        }
//
//        $generalReferrals = Transaction::where('user_id', $user->id)->where('target_type', null)->where('type', TxnType::Referral)->latest()->paginate(8);
//
        $getReferral = $user->getReferrals()->first();
//        $totalReferralProfit = $user->totalReferralProfit();

        $level = LevelReferral::max('the_order');
        $balance = BigDecimal::of(0);
//        dd(auth()->user()->ib_login);
        if(auth()->user()->ib_login) {
            $getUserResponse = $this->getUserApi(auth()->user()->ib_login);
//            dd($getUserResponse->object());
            if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
//                $this->updateUserAccount($getUserResponse);
                $balance = $getUserResponse->object()->Balance;
            }
        }
        $ibQuestions = IbQuestion::where('status', true)->get();
        $data =
        $qrCode = QrCode::size(300)->generate($getReferral->link);
        return view('frontend::referral.index', compact( 'getReferral',  'level', 'balance', 'ibQuestions', 'qrCode'));
    }
    public function advertisementMaterial()
    {

        return view('frontend::referral.index');
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

    public function network() {
        $level = LevelReferral::max('the_order');
        return view('frontend::referral.index', compact( 'level'));
    }

}
