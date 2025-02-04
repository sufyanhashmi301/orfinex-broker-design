<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\KycStatusEnums;
use App\Models\Banner;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Traits\ForexApiTrait;
use App\Http\Controllers\Controller;
use App\Models\KYC;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\Slider;
use App\Models\User;

class DashboardController extends Controller
{
    use ForexApiTrait;
    public function dashboard(Request $request)
    {
        $user = User::find(Auth::id());
        
        // Create kyc field if not created
        if(!$user->kyc) {
            $kyc = new KYC();
            $kyc->user_id = Auth::id();
            $kyc->method = '';
            $kyc->status = KycStatusEnums::UNVERIFIED;
            $kyc->save();
        }

        $transactions = Transaction::where('user_id', Auth::id())->latest()->take(5)->get();
        
        $banners = Banner::where('type', 'user_dashboard')->where('status', 'enabled')->get();
        
        $accounts = AccountTypeInvestment::where('user_id', Auth::id())->get();
        $valid_accounts = AccountTypeInvestment::where('user_id', Auth::id())->where('login', '!=', null)->whereHas('accountTypeInvestmentStat')->orderBy('id', 'DESC')->get();
        $slider = Slider::where('status', 'enabled')->first();

        return view('frontend::dashboard.index', compact('transactions', 'banners', 'accounts', 'valid_accounts', 'slider'));
    }
}
