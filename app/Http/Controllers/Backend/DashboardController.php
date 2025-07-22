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
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin']);
        $this->middleware('role:Super-Admin', ['only' => ['dashboard']]);
        $this->middleware('permission:show-all-users-by-default-to-staff', ['only' => ['staffDashboard']]);
    }

    public function dashboard()
    {
        // Date range for statistics
        $startDate = request()->start_date ? Carbon::createFromDate(request()->start_date) : Carbon::now()->subDays(14);
        $endDate = request()->end_date ? Carbon::createFromDate(request()->end_date) : Carbon::now();
        $dateFilter = [request()->start_date ? $startDate : $startDate->subDays(1), $endDate->addDays(1)];
        $dateArray = array_fill_keys(generate_date_range_array($startDate, $endDate), 0);


        // Get latest users with eager loading
        $latestUsers = User::latest()
            ->take(5)
            ->get();

        // Get ticket statistics with eager loading
        $tickets = Ticket::with(['user', 'assignedToUser', 'categories', 'labels'])
            ->latest()
            ->take(5)
            ->get();

        // Get login activities with eager loading
        $loginActivities = LoginActivities::select('agent', DB::raw('COUNT(*) as count'))
            ->groupBy('agent')
            ->get()
            ->map(function($activity) {
                $agent = new \Jenssegers\Agent\Agent();
                $agent->setUserAgent($activity->agent);
                return [
                    'browser' => $agent->browser(),
                    'platform' => $agent->platform(),
                    'count' => $activity->count
                ];
            })
            ->groupBy(function($item) {
                return $item['browser'] . '|' . $item['platform'];
            })
            ->map(function($group) {
                return [
                    'browser' => explode('|', $group->first()['browser'])[0],
                    'platform' => explode('|', $group->first()['platform'])[0],
                    'count' => $group->sum('count')
                ];
            })
            ->values();

        // Get transaction statistics by type with eager loading
        $schemaStats = Transaction::with(['user', 'staff', 'depositMethod', 'withdrawMethod'])
            ->select([
                'type',
                DB::raw('SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as total_deposit')
            ])
            ->whereIn('type', [TxnType::Deposit, TxnType::ManualDeposit, TxnType::VoucherDeposit])
            ->groupBy('type')
            ->get()
            ->mapWithKeys(function ($item) {
                $name = match($item->type->value) {
                    TxnType::Deposit->value => 'Direct Deposit',
                    TxnType::ManualDeposit->value => 'Manual Deposit',
                    TxnType::VoucherDeposit->value => 'Voucher Deposit',
                    default => ucfirst(str_replace('_', ' ', $item->type->value))
                };
                return [$name => $item->total_deposit];
            })
            ->toArray();

        // Get transaction statistics with eager loading
        $transactionStats = Transaction::with(['user', 'staff', 'depositMethod', 'withdrawMethod'])
            ->select([
                DB::raw('COUNT(CASE WHEN (type = "withdraw" OR type = "withdraw_auto") AND status = "pending" THEN 1 END) as withdraw_count'),
                DB::raw('COUNT(CASE WHEN type = "manual_deposit" AND status = "pending" THEN 1 END) as deposit_count'),
                DB::raw('SUM(CASE WHEN status = "success" AND type = "send_money" THEN amount ELSE 0 END) as total_send'),
                DB::raw('SUM(CASE WHEN status = "success" AND (type = "manual_deposit" OR type = "deposit" OR type = "voucher_deposit") THEN amount ELSE 0 END) as total_deposit'),
                DB::raw('SUM(CASE WHEN status = "success" AND (type = "withdraw" OR type = "withdraw_auto") THEN amount ELSE 0 END) as total_withdraw'),
                DB::raw('SUM(CASE WHEN status = "success" AND type = "ib_bonus" THEN amount ELSE 0 END) as total_ib_bonus'),
                DB::raw('SUM(CASE WHEN status = "success" AND (type = "interest" OR type = "bonus" OR type = "signup_bonus") THEN amount ELSE 0 END) as total_profit'),
                DB::raw('SUM(CASE WHEN status = "success" AND type = "deposit" THEN amount ELSE 0 END) as total_investment')
            ])->first();

        // Get daily statistics with eager loading
        $dailyStats = Transaction::with(['user', 'staff'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                'type',
                DB::raw('SUM(amount) as total')
            )
            ->where('status', TxnStatus::Success)
            ->whereBetween('created_at', $dateFilter)
            ->groupBy('date', 'type')
            ->get();

        // Process daily statistics maintaining model scope conditions
        $depositStatistics = array_replace($dateArray, $this->processDailyStats($dailyStats, ['deposit', 'manual_deposit', 'voucher_deposit']));
        $investStatistics = array_replace($dateArray, $this->processDailyStats($dailyStats, ['deposit']));
        $withdrawStatistics = array_replace($dateArray, $this->processDailyStats($dailyStats, ['withdraw', 'withdraw_auto']));
        $profitStatistics = array_replace($dateArray, $this->processDailyStats($dailyStats, ['interest', 'bonus', 'signup_bonus']));
        $ibBonusStatistics = array_replace($dateArray, $this->processDailyStats($dailyStats, ['ib_bonus']));
        $demoDepositStatistics = array_replace($dateArray, $this->processDailyStats($dailyStats, ['demo_deposit']));

        // Get user statistics
        $userStats = User::select(
            DB::raw('COUNT(*) as total_users'),
            DB::raw('COUNT(CASE WHEN status = 1 THEN 1 END) as active_users'),
            DB::raw('COUNT(CASE WHEN kyc = "pending" THEN 1 END) as kyc_count')
        )->first();

        // Get latest investments with eager loading
        $latestInvests = Invest::with('schema')->latest()->take(5)->get();

        // Get scheme statistics
        $schemeStatistics = Invest::whereNot('status', 'canceled')
            ->select('schema_id', DB::raw('COUNT(*) as count'))
            ->groupBy('schema_id')
            ->get()
            ->pluck('count', 'schema_id')
            ->toArray();

        // Get forex account counts
        $forexStats = ForexAccount::select(
            DB::raw('COUNT(CASE WHEN account_type = "real" THEN 1 END) as live_count'),
            DB::raw('COUNT(CASE WHEN account_type = "demo" THEN 1 END) as demo_count')
        )->first();

        // Get ticket statistics
        $ticketStats = Ticket::selectRaw('
            COUNT(*) as total_tickets,
            COUNT(CASE WHEN status = "open" THEN 1 END) as open_tickets,
            COUNT(CASE WHEN status = "closed" THEN 1 END) as closed_tickets
        ')->first();

        // Get country statistics
        $country = User::select('country', DB::raw('count(*) as count'))
            ->groupBy('country')
            ->orderByDesc('count')
            ->limit(5)
            ->pluck('count', 'country')
            ->toArray();

        $data = [
            'withdraw_count' => $transactionStats->withdraw_count,
            'kyc_count' => $userStats->kyc_count,
            'deposit_count' => $transactionStats->deposit_count,
            'register_user' => $userStats->total_users,
            'active_user' => $userStats->active_users,
            'latest_user' => $latestUsers,
            'total_staff' => Admin::count(),
            'total_deposit' => $transactionStats->total_deposit,
            'total_send' => $transactionStats->total_send,
            'total_ib_bonus' => $transactionStats->total_ib_bonus,
            'total_withdraw' => $transactionStats->total_withdraw,
            'total_referral' => ReferralRelationship::count(),
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
            'total_gateway' => Gateway::where('status', true)->count(),
            'total_live_forex_accounts' => $forexStats->live_count,
            'total_demo_forex_accounts' => $forexStats->demo_count,
            'browser' => $loginActivities->groupBy('browser')->map->sum('count')->toArray(),
            'platform' => $loginActivities->groupBy('platform')->map->sum('count')->toArray(),
            'country' => $country,
            'symbol' => setting('currency_symbol', 'global'),
            'latest_tickets' => $tickets,
            'closed_tickets' => $ticketStats->closed_tickets,
            'open_tickets' => $ticketStats->open_tickets,
            'resolved_tickets' => $ticketStats->total_tickets - ($ticketStats->open_tickets + $ticketStats->closed_tickets),
            'total_ticket' => $ticketStats->total_tickets,
        ];

        if (request()->ajax()) {
            return response()->json([
                'date_label' => $dateArray,
                'deposit_statistics' => $depositStatistics,
                'invest_statistics' => $investStatistics,
                'withdraw_statistics' => $withdrawStatistics,
                'profit_statistics' => $profitStatistics,
                'ib_bonus_statistics' => $ibBonusStatistics,
                'demo_deposit_statistics' => $demoDepositStatistics,
                'symbol' => setting('currency_symbol', 'global')
            ]);
        }

        return view('backend.dashboard', compact('dateArray'))
            ->with([
                'data' => [
                    'withdraw_count' => $transactionStats->withdraw_count ?? 0,
                    'kyc_count' => $userStats->kyc_count,
                    'deposit_count' => $transactionStats->deposit_count ?? 0,
                    'register_user' => $userStats->total_users ?? 0,
                    'active_user' => $userStats->active_users ?? 0,
                    'latest_user' => $latestUsers,
                    'total_staff' => Admin::count(),
                    'total_deposit' => $transactionStats->total_deposit ?? 0,
                    'total_withdraw' => $transactionStats->total_withdraw ?? 0,
                    'total_referral' => ReferralRelationship::count(),
                    'total_send' => $transactionStats->total_send ?? 0,
                    'total_ib_bonus' => $transactionStats->total_ib_bonus ?? 0,
                    'total_live_forex_accounts' => $forexStats->live_count ?? 0,
                    'total_demo_forex_accounts' => $forexStats->demo_count ?? 0,
                    'total_gateway' => Gateway::where('status', true)->count(),
                    'total_ticket' => $ticketStats->total_tickets ?? 0,
                    'browser' => $loginActivities->groupBy('browser')->map->sum('count')->toArray(),
                    'platform' => $loginActivities->groupBy('platform')->map->sum('count')->toArray(),
                    'country' => $country,
                    'symbol' => setting('currency_symbol', 'global'),
                    'latest_tickets' => $tickets,
                    'open_tickets' => $ticketStats->open_tickets ?? 0,
                    'closed_tickets' => $ticketStats->closed_tickets ?? 0,
                    'resolved_tickets' => $ticketStats->total_tickets - (($ticketStats->open_tickets ?? 0) + ($ticketStats->closed_tickets ?? 0)),
                    // Chart data
                    'date_label' => $dateArray,
                    'deposit_statistics' => $depositStatistics,
                    'invest_statistics' => $investStatistics,
                    'withdraw_statistics' => $withdrawStatistics,
                    'profit_statistics' => $profitStatistics,
                    'ib_bonus_statistics' => $ibBonusStatistics,
                    'demo_deposit_statistics' => $demoDepositStatistics,
                    // Schema chart data
                    'total_deposit_statistics' => $schemaStats,
                    // Date range
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d')
                ],
                'currencySymbol' => setting('currency_symbol', 'global')
            ]);
    }

    /**
     * Process daily statistics by transaction types
     */
    private function processDailyStats($stats, array $types): array
    {
        return $stats->whereIn('type', $types)
            ->groupBy('date')
            ->map(function ($group) {
                return $group->sum('total');
            })
            ->toArray();
    }

    public function staffDashboard()
    {
        $loggedInUser = Auth::guard('admin')->user();
        $showAllUsers = true; // Permission is handled by middleware

        $transaction = new Transaction();
        $user = new User();
        $admin = new Admin();

        // Get the query builder first
        $userQuery = getAccessibleUserIds();
        
        // Check if we should show all users or if there are any accessible users
        $hasAccessibleUsers = $showAllUsers || $userQuery->exists();
        
        // Get the user IDs only if we're not showing all users and there are accessible users
        $attachedUserIds = $showAllUsers ? null : ($hasAccessibleUsers ? $userQuery->pluck('id')->toArray() : [-1]);

        if (!$showAllUsers) {
            $user = $user->whereIn('id', $attachedUserIds);
            $transaction = $transaction->whereIn('user_id', $attachedUserIds);
        }

        // Update all your queries to use this pattern:
        $totalDeposit = (new Transaction)->totalDeposit()
            ->when(!$showAllUsers, function ($query) use ($attachedUserIds) {
                $query->whereIn('user_id', $attachedUserIds);
            })
            ->sum('amount');

        $totalIbBonus = (new Transaction)->totalIbBonus()
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

        $tickets = Ticket::with('user', 'categories', 'labels', 'assignedToUser')
            ->where('assigned_to', auth()->user()->id)
            ->latest()
            ->take(5)
            ->get();

        $totalTickets = Ticket::when($attachedUserIds, function ($query) use ($attachedUserIds) {
            $query->whereIn('user_id', $attachedUserIds);
        })->count();

        $closedTickets = Ticket::when($attachedUserIds, function ($query) use ($attachedUserIds) {
            $query->whereIn('user_id', $attachedUserIds);
        })->where('assigned_to', $loggedInUser->id)->closed()->count();

        $openTickets = Ticket::when($attachedUserIds, function ($query) use ($attachedUserIds) {
            $query->whereIn('user_id', $attachedUserIds);
        })->where('assigned_to', $loggedInUser->id)->opened()->count();

        $resolvedTickets = Ticket::when($attachedUserIds, function ($query) use ($attachedUserIds) {
            $query->whereIn('user_id', $attachedUserIds);
        })->where('assigned_to', $loggedInUser->id)->resolved()->count();

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
            'deposit_bonus' => (new Transaction)->totalDepositBonus(),
            'investment_bonus' => (new Transaction)->totalInvestBonus(),
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
