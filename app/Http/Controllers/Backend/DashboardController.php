<?php

namespace App\Http\Controllers\Backend;

use App\Enums\KYCStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ForexAccount;
use App\Models\Gateway;
use App\Models\Invest;
use App\Models\LoginActivities;
use App\Models\ReferralRelationship;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $loggedInUser = auth()->user(); // Get the logged-in user
        if (!$loggedInUser->hasRole('Super-Admin')) {
            return redirect()->route('admin.staff.dashboard');
        }
        $transaction = new Transaction();
        $user = new User();
        $admin = new Admin();

        $totalDeposit = $transaction->totalDeposit();

        $totalSend = Transaction::where('status', TxnStatus::Success)->where(function ($query) {
            $query->where('type', TxnType::SendMoney);
        })->sum('amount');

        $activeUser = $user->where('status', 1)->count();

        $totalStaff = $admin->count();

        $latestUser = $user->latest()->take(5)->get();

        $latestInvest = Invest::with('schema')->take(5)->latest()->get();

        $totalGateway = Gateway::where('status', true)->count();

        $withdrawCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::Withdraw)
                ->where('status', 'pending');
        })->count();

        $kycCount = $user->where('kyc', KYCStatus::Pending)->count();

        $depositCount = Transaction::where(function ($query) {
            $query->where('type', TxnType::ManualDeposit)
                ->where('status', 'pending');
        })->count();

        $totalReferral = ReferralRelationship::count();

        // ============================= Start dashboard statistics =============================================

        $schemeStatistics = Invest::whereNot('status', 'canceled')->get()->groupBy('schema.name')->map(function ($group) {
            return $group->count();
        })->toArray();

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

        $data = [
            'withdraw_count' => $withdrawCount,
            'kyc_count' => $kycCount,
            'deposit_count' => $depositCount,

            'register_user' => $user->count(),
            'active_user' => $activeUser,
            'latest_user' => $latestUser,
            'latest_invest' => $latestInvest,

            'total_staff' => $totalStaff,

            'total_deposit' => $transaction->totalDeposit()->sum('amount'),
            'total_send' => $totalSend,
            'total_investment' => $transaction->totalInvestment()->sum('amount'),
            'total_withdraw' => $transaction->totalWithdraw()->sum('amount'),
            'total_referral' => $totalReferral,

            'date_label' => $dateArray,
            'deposit_statistics' => $depositStatistics,
            'invest_statistics' => $investStatistics,
            'withdraw_statistics' => $withdrawStatistics,
            'profit_statistics' => $profitStatistics,

            'start_date' => isset(request()->start_date) ? $startDate : $startDate->addDays(1)->format('m/d/Y'),
            'end_date' => isset(request()->end_date) ? $endDate : $endDate->subDays(1)->format('m/d/Y'),

            'scheme_statistics' => $schemeStatistics,
            'deposit_bonus' => $transaction->totalDepositBonus(),
            'investment_bonus' => $transaction->totalInvestBonus(),
            'total_gateway' => $totalGateway,
            'total_ticket' => Ticket::count(),
            'total_live_forex_accounts' => ForexAccount::where('account_type', 'real')->count(),
            'total_demo_forex_accounts' => ForexAccount::where('account_type', 'demo')->count(),

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

    public function staffDashboard()
    {
        $transaction = new Transaction();
        $user = new User();
        $admin = new Admin();

        $loggedInUser = auth()->user(); // Get the logged-in user
        $attachedUserIds = !$loggedInUser->hasRole('Super-Admin') ? $loggedInUser->users->pluck('id') : null;

        if (!$loggedInUser->hasRole('Super-Admin')) {
            $user = $user->whereIn('id', $attachedUserIds);
            $transaction = $transaction->whereIn('user_id', $attachedUserIds);
        }

        $totalDeposit = (new Transaction)->totalDeposit()
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->sum('amount');

        $totalInvestment = (new Transaction)->totalInvestment()
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->sum('amount');

        $totalWithdraw = (new Transaction)->totalWithdraw()
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->sum('amount');

        $totalProfit = (new Transaction)->totalProfit()
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->sum('amount');

        $totalSend = Transaction::where('status', TxnStatus::Success)
            ->where(function ($query) {
                $query->where('type', TxnType::SendMoney);
            })
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->sum('amount');

        $activeUser = $user->where('status', 1)->count();
        $totalStaff = $admin->count();

        $latestUser = $user->latest()->take(5)->get();
        $latestInvest = Invest::with('schema')
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->take(5)
            ->latest()
            ->get();

        $totalGateway = Gateway::where('status', true)->count();
        $withdrawCount = Transaction::where('type', TxnType::Withdraw)
            ->where('status', 'pending')
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->count();

        $kycCount = $user->where('kyc', KYCStatus::Pending)->count();
        $depositCount = Transaction::where('type', TxnType::ManualDeposit)
            ->where('status', 'pending')
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->count();

        $totalReferral = ReferralRelationship::when($attachedUserIds, function ($query) use ($attachedUserIds) {
            $query->whereIn('user_id', $attachedUserIds);
        })->count();

        $schemeStatistics = Invest::whereNot('status', 'canceled')
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->get()
            ->groupBy('schema.name')
            ->map(function ($group) {
                return $group->count();
            })
            ->toArray();

        $startDate = request()->start_date ? Carbon::createFromDate(request()->start_date) : Carbon::now()->subDays(14);
        $endDate = request()->end_date ? Carbon::createFromDate(request()->end_date) : Carbon::now();
        $dateArray = array_fill_keys(generate_date_range_array($startDate, $endDate), 0);

        $dateFilter = [request()->start_date ? $startDate : $startDate->subDays(1), $endDate->addDays(1)];

        $depositStatistics = (new Transaction)->totalDeposit()
            ->whereBetween('created_at', $dateFilter)
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->get()
            ->groupBy('day')
            ->map(function ($group) {
                return $group->sum('amount');
            })
            ->toArray();

        $depositStatistics = array_replace($dateArray, $depositStatistics);

        $investStatistics = (new Transaction)->totalInvestment()
            ->whereBetween('created_at', $dateFilter)
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->get()
            ->groupBy('day')
            ->map(function ($group) {
                return $group->sum('amount');
            })
            ->toArray();

        $investStatistics = array_replace($dateArray, $investStatistics);

        $withdrawStatistics = (new Transaction)->totalWithdraw()
            ->whereBetween('created_at', $dateFilter)
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->get()
            ->groupBy('day')
            ->map(function ($group) {
                return $group->sum('amount');
            })
            ->toArray();

        $withdrawStatistics = array_replace($dateArray, $withdrawStatistics);

        $profitStatistics = (new Transaction)->totalProfit()
            ->whereBetween('created_at', $dateFilter)
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->get()
            ->groupBy('day')
            ->map(function ($group) {
                return $group->sum('amount');
            })
            ->toArray();

        $profitStatistics = array_replace($dateArray, $profitStatistics);

        $browser = LoginActivities::all()->groupBy('browser')->map->count()->toArray();
        $platform = LoginActivities::all()->groupBy('platform')->map->count()->toArray();

        $country = User::select('country', DB::raw('count(*) as count'))
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'country')
            ->toArray();

        // Apply filtering for ForexAccount and Tickets
        $totalLiveForexAccounts = ForexAccount::where('account_type', 'real')
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->count();

        $totalDemoForexAccounts = ForexAccount::where('account_type', 'demo')
            ->when($attachedUserIds, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->count();

        $totalTickets = Ticket::when($attachedUserIds, function ($query) use ($attachedUserIds) {
            $query->whereIn('user_id', $attachedUserIds);
        })->count();

        $symbol = setting('currency_symbol', 'global');

        $data = [
            'withdraw_count' => $withdrawCount,
            'kyc_count' => $kycCount,
            'deposit_count' => $depositCount,
            'register_user' => $user->count(),
            'active_user' => $activeUser,
            'latest_user' => $latestUser,
            'latest_invest' => $latestInvest,
            'total_staff' => $totalStaff,
            'total_deposit' => $totalDeposit,
            'total_send' => $totalSend,
            'total_investment' => $totalInvestment,
            'total_withdraw' => $totalWithdraw,
            'total_referral' => $totalReferral,
            'date_label' => $dateArray,
            'deposit_statistics' => $depositStatistics,
            'invest_statistics' => $investStatistics,
            'withdraw_statistics' => $withdrawStatistics,
            'profit_statistics' => $profitStatistics,
            'start_date' => isset(request()->start_date) ? $startDate : $startDate->addDays(1)->format('m/d/Y'),
            'end_date' => isset(request()->end_date) ? $endDate : $endDate->subDays(1)->format('m/d/Y'),
            'scheme_statistics' => $schemeStatistics,
            'deposit_bonus' => (new Transaction)->totalDepositBonus(),
            'investment_bonus' => (new Transaction)->totalInvestBonus(),
            'total_gateway' => $totalGateway,
            'total_live_forex_accounts' => $totalLiveForexAccounts,
            'total_demo_forex_accounts' => $totalDemoForexAccounts,
            'total_ticket' => $totalTickets,
            'browser' => $browser,
            'platform' => $platform,
            'country' => $country,
            'symbol' => $symbol,
        ];

        if (request()->ajax()) {
            return response()->json($data);
        }

        return view('backend.staff_dashboard', compact('data'));
    }




}
