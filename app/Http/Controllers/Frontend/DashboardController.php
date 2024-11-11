<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\InvestmentStatus;
use App\Jobs\AgentReferralJob;
use App\Models\Banner;
use App\Models\ForexAccount;
use App\Models\ForexSchemaInvestment;
use App\Traits\ForexApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DashboardController extends Controller
{
    use ForexApiTrait;
    public function dashboard(Request $request)
    {
//        dd(getLocation(),'dashboar');
        $user = auth()->user();

//        if(!$user->ref_id) {
//            AgentReferralJob::dispatch($user);
//        }

        $transactions = Transaction::where('user_id', $user->id);

        $recentTransactions = $transactions->latest()->take(5)->get();

        $referral = $user->getReferrals()->first();

        $dataCount = [
            'total_transaction' => $transactions->count(),
            'total_deposit' => $user->totalDeposit(),
//            'total_investment' => $user->totalInvestment(),
//            'total_profit' => $user->totalProfit(),
            'profit_last_7_days' => $user->totalProfit(7),
            'total_withdraw' => $user->totalWithdraw(),
//            'total_transfer' => $user->totalTransfer(),
//            'total_referral_profit' => $user->totalReferralProfit(),
//            'total_referral' => $referral->relationships()->count(),
//
//            'deposit_bonus' => $user->totalDepositBonus(),
//            'investment_bonus' => $user->totalInvestBonus(),
//            'rank_achieved' => $user->rankAchieved(),
//            'total_ticket' => $user->ticket->count(),
           'total_forex_balance' => mt5_total_balance($user->id),
            'total_forex_equity' => mt5_total_equity($user->id),
        ];
        $referral = $user->getReferrals()->first();
//        $realForexAccounts = ForexAccount::realActiveAccount()
//            ->orderBy('balance','desc')
//            ->paginate(3)->withQueryString();
//        $demoForexAccounts = ForexAccount::demoActiveAccount()
//            ->orderBy('balance','desc')
//            ->paginate(3)->withQueryString();
        $getReferral = $user->getReferrals()->first();
        $qrCode = QrCode::size(300)->generate($getReferral->link);
        $banners = Banner::where('status', 1)->get();

        $investments = ForexSchemaInvestment::traderType()->whereIn('status', [
            InvestmentStatus::PENDING,
            InvestmentStatus::ACTIVE,
            InvestmentStatus::VIOLATED
        ])->where('user_id', $user->id)
            ->orderBy('id', 'desc')->get();
        $investments = $investments->groupBy('status');

        return view('frontend::user.dashboard', compact('dataCount', 'recentTransactions', 'referral',  'qrCode', 'banners', 'investments'));
    }
}
