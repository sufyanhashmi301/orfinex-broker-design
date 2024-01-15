<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\AdvertisementMaterial;
use App\Models\IbQuestion;
use App\Models\Language;
use App\Models\LevelReferral;
use App\Models\Transaction;
use App\Traits\ForexApiTrait;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $balance = BigDecimal::of(auth()->user()->ib_balance);
//        auth()->user()->update(['ib_balance' => 123]);
//        auth()->setUser(auth()->user()->fresh());
//        dd(auth()->user()->ib_balance);
        $clientIp = request()->ip();
        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
            if (auth()->user()->ib_login) {
                $getUserResponse = $this->getUserApi(auth()->user()->ib_login);
//            dd($getUserResponse->object());
                if ($getUserResponse->status() == 200 && isset($getUserResponse->object()->Login)) {
//                $this->updateUserAccount($getUserResponse);
                    $balance = $getUserResponse->object()->Balance;
                    auth()->user()->update(['ib_balance' => $balance]);
                    auth()->setUser(auth()->user()->fresh());
                }
            }
        }
        $ibQuestions = IbQuestion::where('status', true)->get();
        $qrCode = QrCode::size(300)->generate($getReferral->link);
        return view('frontend::referral.index', compact( 'getReferral',  'level', 'balance', 'ibQuestions', 'qrCode'));
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

    public function network() {
        $level = LevelReferral::max('the_order');
        return view('frontend::referral.index', compact( 'level'));
    }

    public function download($filename)
    {
        if (!File::exists('assets/'.$filename)) {
            notify()->error('file not exists', 'Error');
            return redirect()->back();
        }
        return response()->download('assets/'.$filename);
    }

}
