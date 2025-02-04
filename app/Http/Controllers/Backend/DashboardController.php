<?php

namespace App\Http\Controllers\Backend;

use App\Enums\AccountType;
use App\Enums\InvestmentStatus;
use App\Enums\KYCStatus;
use App\Enums\PayoutRequestStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\AccountTypeInvestment;
use App\Models\Admin;
use App\Models\Gateway;
use App\Models\LoginActivities;
use App\Models\PayoutRequest;
use App\Models\ReferralRelationship;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserAffiliate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //admin dashboard
    public function dashboard()
    {

        $transaction = new Transaction();
        $user = new User();
        $admin = new Admin();

        $totalDeposit = $transaction->totalDeposit();

        $totalSend = Transaction::where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::SendMoney);
        })->sum('amount');

        // $activeUser = $user->where('status', 1)->count();
        

    

        $totalStaff = $admin->count();

        $latestUser = $user->latest()->take(5)->get();


        $totalGateway = Gateway::where('status', true)->count();

        $withdrawCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::Withdraw)
                ->where('status', 'pending');
        })->count();

        // $kycCount = $user->where('kyc', KYCStatus::Pending)->count();

        $depositCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::ManualDeposit)
                ->where('status', 'pending');
        })->count();


        // ============================= Start dashboard statistics =============================================

        $startDate = request()->start_date ? Carbon::createFromDate(request()->start_date) : Carbon::now()->subDays(14);
        $endDate = request()->end_date ? Carbon::createFromDate(request()->end_date) : Carbon::now();
        $dateArray = array_fill_keys(generate_date_range_array($startDate, $endDate), 0);

        $dateFilter = [request()->start_date ? $startDate : $startDate->subDays(1), $endDate->addDays(1)];

        $depositStatistics = $totalDeposit->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();

        $depositStatistics = array_replace($dateArray, $depositStatistics);



        $investStatistics = $transaction->totalInvestment()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();

        $investStatistics = array_replace($dateArray, $investStatistics);

        $withdrawStatistics = $transaction->totalWithdraw()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();
        $withdrawStatistics = array_replace($dateArray, $withdrawStatistics);

        $profitStatistics = $transaction->totalProfit()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();
        $profitStatistics = array_replace($dateArray, $profitStatistics);

        // ============================= End dashboard statistics =============================================

        $browser = LoginActivities::all()->groupBy('browser')->map(function ($browser) {
            return $browser->count();
        })->toArray();
        $platform = LoginActivities::all()->groupBy('platform')->map(function ($platform) {
            return $platform->count();
        })->toArray();

        $country = User::select('country', DB::raw('count(*) as count'))
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'country')
            ->toArray();

        $symbol = setting('currency_symbol','global');

        // --- Optimizations
        $active_users = User::whereHas('accountTypeInvestment', function ($query) {
            $query->whereIn('status', [InvestmentStatus::ACTIVE, InvestmentStatus::PASSED]);
        })->count();
        $total_active_accounts = AccountTypeInvestment::where('status', InvestmentStatus::ACTIVE)->count();
        $total_violated_accounts = AccountTypeInvestment::where('status', InvestmentStatus::VIOLATED)->count();
        $total_payout = PayoutRequest::where('status', PayoutRequestStatus::APPROVED)->sum('user_profit_share_amount'); // payout requests that have been approved
        $total_referral = UserAffiliate::sum('total_commission');

        $total_challenge_accounts = AccountTypeInvestment::whereHas('accountTypePhaseRule.accountTypePhase.accountType', function ($query) {
            $query->where('type', AccountType::CHALLENGE);
        })->count();
        $total_funded_accounts = AccountTypeInvestment::whereHas('accountTypePhaseRule.accountTypePhase.accountType', function ($query) {
            $query->where('type', AccountType::FUNDED);
        })->count();
        $total_trial_accounts = AccountTypeInvestment::where('is_trial', 1)->count();
        $total_approved_withdraws = Transaction::where('type', 'withdraw')->where('status', TxnStatus::Success)->sum('amount');
        
        // dd($challenge_accounts);
        // --- Optimizations

        $data = [
            // optimizaitons
            'active_user' => $active_users,
            'total_active_accounts' => $total_active_accounts,
            'total_violated_accounts' => $total_violated_accounts,
            'total_payout' => $total_payout,
            'total_referral' => $total_referral,
            'total_challenge_accounts' => $total_challenge_accounts,
            'total_funded_accounts' => $total_funded_accounts,
            'total_trial_accounts' => $total_trial_accounts,
            'total_approved_withdraws' => $total_approved_withdraws,
            // optimizaitons

            'withdraw_count' => $withdrawCount,
            // 'kyc_count' => $kycCount,
            'deposit_count' => $depositCount,

            'register_user' => $user->count(),
            
            'latest_user' => $latestUser,

            'total_staff' => $totalStaff,

            // 'total_deposit' => $transaction->totalDeposit()->sum('amount'),
            // 'total_send' => $totalSend,
            // 'total_investment' => $transaction->totalInvestment()->sum('amount'),
            // 'total_withdraw' => $transaction->totalWithdraw()->sum('amount'),
            // 'total_referral' => $totalReferral,

            'date_label' => $dateArray,
            'deposit_statistics' => $depositStatistics,
            'invest_statistics' => $investStatistics,
            'withdraw_statistics' => $withdrawStatistics,
            'profit_statistics' => $profitStatistics,

            'start_date' => isset(request()->start_date) ? $startDate : $startDate->addDays(1)->format('m/d/Y'),
            'end_date' => isset(request()->end_date) ? $endDate : $endDate->subDays(1)->format('m/d/Y'),

            'deposit_bonus' => $transaction->totalDepositBonus(),
            'investment_bonus' => $transaction->totalInvestBonus(),
            'total_gateway' => $totalGateway,
            'total_ticket' => Ticket::count(),

            'browser' => $browser,
            'platform' => $platform,
            'country' => $country,
            'symbol' => $symbol,
        
        ];



        if (request()->ajax()) {
            $date = [
                'date_label' => $dateArray,
                'deposit_statistics' => $depositStatistics,
                'invest_statistics' => $investStatistics,
                'withdraw_statistics' => $withdrawStatistics,
                'profit_statistics' => $profitStatistics,
                'symbol' => $symbol,
            ];
            return response()->json($date);
        }

        return view('backend.dashboard', compact('data'));
    }
}
