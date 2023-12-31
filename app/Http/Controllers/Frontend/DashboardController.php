<?php

namespace App\Http\Controllers\Frontend;

use App\Models\ForexAccount;
use App\Traits\ForexApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    use ForexApiTrait;
    public function dashboard(Request $request)
    {
//        dd(getLocation());
        $clientIp = request()->ip();
        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
            $this->syncForexAccounts(auth()->id());
        }

        $user = auth()->user();
        $transactions = Transaction::where('user_id', $user->id);

        $recentTransactions = $transactions->latest()->take(5)->get();

        $referral = $user->getReferrals()->first();

        $dataCount = [
            'total_transaction' => $transactions->count(),
            'total_deposit' => $user->totalDeposit(),
            'total_investment' => $user->totalInvestment(),
            'total_profit' => $user->totalProfit(),
            'profit_last_7_days' => $user->totalProfit(7),
            'total_withdraw' => $user->totalWithdraw(),
            'total_transfer' => $user->totalTransfer(),
            'total_referral_profit' => $user->totalReferralProfit(),
            'total_referral' => $referral->relationships()->count(),

            'deposit_bonus' => $user->totalDepositBonus(),
            'investment_bonus' => $user->totalInvestBonus(),
            'rank_achieved' => $user->rankAchieved(),
            'total_ticket' => $user->ticket->count(),
            'total_forex_balance' => $user->totalForexBalance(),
            'total_forex_equity' => $user->totalForexEquity(),
        ];

        $referral = $user->getReferrals()->first();
        $realForexAccounts = ForexAccount::realActiveAccount()
            ->orderBy('balance','desc')
            ->take(3)
            ->get();
        $demoForexAccounts = ForexAccount::demoActiveAccount()
            ->orderBy('balance','desc')
            ->take(3)
            ->get();

        return view('frontend::user.dashboard', compact('dataCount', 'recentTransactions', 'referral', 'realForexAccounts', 'demoForexAccounts'));
    }
}
