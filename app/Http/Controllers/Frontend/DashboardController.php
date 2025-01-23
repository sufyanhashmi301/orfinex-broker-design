<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Banner;
use App\Models\Transaction;
use App\Models\ForexAccount;
use Illuminate\Http\Request;
use App\Traits\ForexApiTrait;
use App\Jobs\AgentReferralJob;
use App\Enums\InvestmentStatus;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\ForexSchemaInvestment;
use App\Models\Slider;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DashboardController extends Controller
{
    use ForexApiTrait;
    public function dashboard(Request $request)
    {
        $transactions = Transaction::where('user_id', Auth::id())->latest()->take(5)->get();
        
        $banners = Banner::where('type', 'user_dashboard')->where('status', 'enabled')->get();
        
        $accounts = AccountTypeInvestment::where('user_id', Auth::id())->get();
        $valid_accounts = AccountTypeInvestment::where('user_id', Auth::id())->where('login', '!=', null)->whereHas('accountTypeInvestmentStat')->orderBy('id', 'DESC')->get();
        $slider = Slider::where('status', 'enabled')->first();

        return view('frontend::dashboard.index', compact('transactions', 'banners', 'accounts', 'valid_accounts', 'slider'));
    }
}
