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
        // if ($loggedInUser->hasRole('Super-Admin')) {
        //     return redirect()->route('admin.user.active');
        // }
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
        
        $totalDepositStatistics = Transaction::select('type', DB::raw('SUM(amount) as total'))
        ->whereIn('type', [
            TxnType::Deposit,
            TxnType::DemoDeposit,
            TxnType::VoucherDeposit,
        ])->where('status', TxnStatus::Success)->groupBy('type')->pluck('total', 'type')->toArray();
        
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
        
        $ibBonusStatistics = $transaction->totalIbBonus()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();
        $ibBonusStatistics = array_replace($dateArray, $ibBonusStatistics);

        $demoDepositStatistics = $transaction->totalDemoDeposit()->whereBetween('created_at', $dateFilter)->get()->groupBy('day')->map(function ($group) {
            return $group->sum('amount');
        })->toArray();
        $demoDepositStatistics = array_replace($dateArray, $demoDepositStatistics);

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

        $tickets = Ticket::with('user', 'categories', 'labels', 'assignedToUser')
            ->latest()
            ->take(5)
            ->get();
        $closedTickets = Ticket::closed()->count();
        $openTickets = Ticket::opened()->count();
        $resolvedTickets = Ticket::resolved()->count();

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
            'total_ib_bonus' => $transaction->totalIbBonus()->sum('amount'),
            'total_withdraw' => $transaction->totalWithdraw()->sum('amount'),
            'total_referral' => $totalReferral,

            'date_label' => $dateArray,
            'deposit_statistics' => $depositStatistics,
            'invest_statistics' => $investStatistics,
            'withdraw_statistics' => $withdrawStatistics,
            'profit_statistics' => $profitStatistics,
            'ib_bonus_statistics' => $ibBonusStatistics,
            'demo_deposit_statistics' => $demoDepositStatistics,

            'start_date' => isset(request()->start_date) ? $startDate : $startDate->addDays(1)->format('m/d/Y'),
            'end_date' => isset(request()->end_date) ? $endDate : $endDate->subDays(1)->format('m/d/Y'),

            'scheme_statistics' => $schemeStatistics,
            'total_deposit_statistics' => $totalDepositStatistics,
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

            'latest_tickets' => $tickets,
            'closed_tickets' => $closedTickets,
            'open_tickets' => $openTickets,
            'resolved_tickets' => $resolvedTickets,
        ];



        if (request()->ajax()) {
            $date = [
                'date_label' => $dateArray,
                'deposit_statistics' => $depositStatistics,
                'demo_deposit_statistics' => $demoDepositStatistics,
                'withdraw_statistics' => $withdrawStatistics,
                'ib_bonus_statistics' => $ibBonusStatistics,
                'symbol' => $symbol,
            ];
            return response()->json($date);
        }

        return view('backend.dashboard', compact('data'));
    }

    public function staffDashboard()
{
    $loggedInUser = auth()->user();
    
    $transaction = new Transaction();
    $user = new User();
    $admin = new Admin();

    // Get the query builder first with branch filtering applied
    $userQuery = getAccessibleUserIds();
    
    // Get accessible user IDs (this already includes branch filtering)
    $accessibleUserIds = $userQuery->pluck('id')->toArray();
    $hasAccessibleUsers = !empty($accessibleUserIds);
    
    // If no accessible users, use [-1] to ensure no results
    if (!$hasAccessibleUsers) {
        $accessibleUserIds = [-1];
    }

    // Apply user filtering to all queries
    $user = $user->whereIn('id', $accessibleUserIds);
    $transaction = $transaction->whereIn('user_id', $accessibleUserIds);

    // Update all your queries to use accessible user IDs:
    $totalDeposit = (new Transaction)->totalDeposit()
        ->whereIn('user_id', $accessibleUserIds)
        ->sum('amount');

    $totalIbBonus = (new Transaction)->totalIbBonus()
        ->whereIn('user_id', $accessibleUserIds)
        ->sum('amount');

    $totalWithdraw = (new Transaction)->totalWithdraw()
        ->whereIn('user_id', $accessibleUserIds)
        ->sum('amount');

    $totalProfit = (new Transaction)->totalProfit()
        ->whereIn('user_id', $accessibleUserIds)
        ->sum('amount');

    $totalSend = Transaction::where('status', TxnStatus::Success)
        ->where(function ($query) {
            $query->where('type', TxnType::SendMoney);
        })
        ->whereIn('user_id', $accessibleUserIds)
        ->sum('amount');

    $activeUser = $user->where('status', 1)->count();
    $totalStaff = $admin->count();

    $latestUser = $user->latest()->take(5)->get();

    $totalGateway = Gateway::where('status', true)->count();
    $withdrawCount = Transaction::where('type', TxnType::Withdraw)
        ->where('status', 'pending')
        ->whereIn('user_id', $accessibleUserIds)
        ->count();

    $kycCount = $user->where('kyc', KYCStatus::Pending)->count();
    $depositCount = Transaction::where('type', TxnType::ManualDeposit)
        ->where('status', 'pending')
        ->whereIn('user_id', $accessibleUserIds)
        ->count();

    $totalReferral = ReferralRelationship::whereIn('user_id', $accessibleUserIds)->count();

    $totalLiveForexAccounts = ForexAccount::where('account_type', 'real')
        ->whereIn('user_id', $accessibleUserIds)
        ->count();

    $totalDemoForexAccounts = ForexAccount::where('account_type', 'demo')
        ->whereIn('user_id', $accessibleUserIds)
        ->count();

    $tickets = Ticket::with('user', 'categories', 'labels', 'assignedToUser')
        ->where('assigned_to', auth()->user()->id)
        ->latest()
        ->take(5)
        ->get();

    $totalTickets = Ticket::whereIn('user_id', $accessibleUserIds)->count();

    $closedTickets = Ticket::whereIn('user_id', $accessibleUserIds)
        ->where('assigned_to', $loggedInUser->id)->closed()->count();

    $openTickets = Ticket::whereIn('user_id', $accessibleUserIds)
        ->where('assigned_to', $loggedInUser->id)->opened()->count();

    $resolvedTickets = Ticket::whereIn('user_id', $accessibleUserIds)
        ->where('assigned_to', $loggedInUser->id)->resolved()->count();

    $symbol = setting('currency_symbol', 'global');

    $data = [
        'withdraw_count' => $withdrawCount,
        'kyc_count' => $kycCount,
        'deposit_count' => $depositCount,
        'register_user' => $user->count(),
        'active_user' => $activeUser,
        'latest_user' => $latestUser,
        'total_staff' => $totalStaff,
        'total_deposit' => $totalDeposit,
        'total_send' => $totalSend,
        'total_ib_bonus' => $totalIbBonus,
        'total_withdraw' => $totalWithdraw,
        'total_referral' => $totalReferral,
        'deposit_bonus' => Transaction::where('status', TxnStatus::Success)
            ->where(function ($query) {
                $query->where('target_id', '!=', null)
                    ->where('target_type', 'deposit')
                    ->where('type', TxnType::Referral);
            })
            ->whereIn('user_id', $accessibleUserIds)
            ->sum('amount'),
        'investment_bonus' => Transaction::where('status', TxnStatus::Success)
            ->where(function ($query) {
                $query->where('target_id', '!=', null)
                    ->where('target_type', 'investment')
                    ->where('type', TxnType::Referral);
            })
            ->whereIn('user_id', $accessibleUserIds)
            ->sum('amount'),
        'total_gateway' => $totalGateway,
        'total_live_forex_accounts' => $totalLiveForexAccounts,
        'total_demo_forex_accounts' => $totalDemoForexAccounts,
        'symbol' => $symbol,

        'total_ticket' => $totalTickets,
        'latest_tickets' => $tickets,
        'closed_tickets' => $closedTickets,
        'open_tickets' => $openTickets,
        'resolved_tickets' => $resolvedTickets,

    ];

    if (request()->ajax()) {
        return response()->json($data);
    }

    return view('backend.staff_dashboard', compact('data'));
}




}
