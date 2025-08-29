<?php

namespace App\Http\Controllers\Backend;

use App\Enums\GatewayType;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use DataTables;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Models\DepositMethod;
use App\Models\User;
use App\Models\LevelReferral;
use App\Helpers\TxnTypeGroup;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('permission:transaction-list', ['only' => ['transactions']]);
        $this->middleware('permission:customer-transactions-stats', ['only' => ['report', 'userTransactionSummary']]);
        $this->middleware('permission:customer-network-stats', ['only' => ['referralNetworkReport']]);

    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws \Exception
     */
    public function transactions(Request $request, $id = null)
    {
        $loggedInUser = auth()->user();

        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'status', 'type', 'created_at']);

            // Get accessible users using helper function
            $accessibleUsersQuery = getAccessibleUserIds();
            $accessibleUserIds = $accessibleUsersQuery->pluck('id');

            // Base query (without order) for summary - exclude none status transactions
            $baseQuery = Transaction::query()->where('status', '!=', \App\Enums\TxnStatus::None);

            if ($id && $accessibleUserIds->contains($id)) {
                $baseQuery->where('user_id', $id);
            } elseif (!$id && $accessibleUserIds->isNotEmpty()) {
                $baseQuery->whereIn('user_id', $accessibleUserIds);
            } else {
                $baseQuery->whereNull('id');
            }

        // Apply global search filter
        if (!empty($filters['global_search'])) {
            $searchTerm = $filters['global_search'];
            $baseQuery->where(function($query) use ($searchTerm) {
                // Search user-related fields
                $query->whereHas('user', function($q) use ($searchTerm) {
                    $q->where('email', 'like', "%$searchTerm%")
                      ->orWhere('username', 'like', "%$searchTerm%")
                      ->orWhere('first_name', 'like', "%$searchTerm%")
                      ->orWhere('last_name', 'like', "%$searchTerm%")
                      ->orWhere('phone', 'like', "%$searchTerm%");
                })
                // Search transaction fields
                ->orWhere('tnx', 'like', "%$searchTerm%")
                ->orWhere('target_id', 'like', "%$searchTerm%");
            });
        }

            // Apply filters (email, status, etc.)
            $baseQuery->applyFilters($filters);
            $data = (clone $baseQuery)->latest();
            $summaryQuery = clone $baseQuery;
            $total = (clone $summaryQuery)->sum('amount');

            $statusSums = (clone $summaryQuery)
            ->select('status', DB::raw('SUM(amount) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at . '</span>';
                })
                ->addColumn('action_by', function ($row) {
                    return '<span class="text-nowrap">' . optional($row->staff)->name ?? '-' . '</span>';
                })
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('final_amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge . ' ' . setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.transaction.include.__action')
                ->editColumn('created_at', function ($row) {
                    if (!empty($row->manual_field_data) && $row->manual_field_data !== '[]') {
                        $manualData = json_decode($row->manual_field_data, true);
                        if (is_array($manualData) && isset($manualData['time'])) {
                            return \Carbon\Carbon::parse($manualData['time'])->format('M d, Y h:i A');
                        }
                    }
                    return $row->created_at;
                })
                ->rawColumns(['created_at', 'status', 'action_by', 'type', 'final_amount', 'username', 'action'])
                ->with([
                    'summary' => [
                        'total' => round($total, 2),
                        'success' => round($statusSums['success'] ?? 0, 2),
                        'pending' => round($statusSums['pending'] ?? 0, 2),
                        'rejected' => round($statusSums['rejected'] ?? 0, 2),
                    ]
                ])
                ->make(true);
        }

        return view('backend.transaction.index');
    }

    public function export(Request $request)
    {
        return Excel::download(new TransactionsExport($request), 'transactions.xlsx');
    }

    public function view($id)
    {
        $data = Transaction::find($id);

        if($data->status->value=='pending' && ($data->type == TxnType::Withdraw || $data->type == TxnType::WithdrawAuto)){
            return view('backend.withdraw.include.__withdraw_action', compact('data', 'id'))->render();
        }elseif($data->status->value=='pending' && ($data->type == TxnType::Deposit || $data->type == TxnType::ManualDeposit)){
            $gateway = $this->gateway($data->method);
            return view('backend.deposit.include.__deposit_action', compact('data', 'id', 'gateway'))->render();
        }else{
            return view('backend.transaction.modals.view', compact('data', 'id'))->render();
        }

    }

    public function gateway($code)
    {
        $gateway = DepositMethod::code($code)->first();
        if($gateway){
            if ($gateway->type == GatewayType::Manual->value) {
                $fieldOptions = $gateway->field_options;
                $paymentDetails = $gateway->payment_details;
                $gateway = array_merge($gateway->toArray(), ['credentials' => view('frontend::gateway.include.manual', compact('fieldOptions', 'paymentDetails'))->render()]);
            }else{
                $gatewayCurrency =  is_custom_rate($gateway->gateway->gateway_code) ?? $gateway->currency;
                $gateway['currency'] = $gatewayCurrency;
            }
            return $gateway;
        }
    }

    public function report()
    {
        $users = User::all();
        // Build the query - exclude none status for reports
        $query = Transaction::where('status', '!=', TxnStatus::None)
            ->select(['type', 'status', DB::raw('SUM(amount) as total')])
            ->groupBy('type', 'status');

        $results = $query->get()->groupBy(function ($item) {
            return $item->type instanceof TxnType ? $item->type->value : (string) $item->type;
        });

        $incomingTypes = TxnTypeGroup::incoming();
        $outgoingTypes = TxnTypeGroup::outgoing();

        $incomingSummary = [];
        $outgoingSummary = [];

        foreach (getFilteredTxnTypes() as $type) {
            $key = $type->value;

            $records = $results->get($key, collect());

            $success = round($records->filter(fn ($r) => $r->status === TxnStatus::Success)->sum('total'), 2);
            $pending = round($records->filter(fn ($r) => $r->status === TxnStatus::Pending)->sum('total'), 2);
            $rejected = round($records->filter(fn ($r) => $r->status === TxnStatus::Failed)->sum('total'), 2);


            $total_amount = $records->sum('total');

            $row = [
                'type' => $type->label(),
                'desc' => $type->description(),
                'success' => $success,
                'pending' => $pending,
                'rejected' => $rejected,

                'total' => $total_amount,
            ];

            if (in_array($type->value, $incomingTypes)) {
                $incomingSummary[] = $row;
            } elseif (in_array($type->value, $outgoingTypes)) {
                $outgoingSummary[] = $row;
            }
        }

        return view('backend.transaction.report', compact('incomingSummary', 'outgoingSummary', 'users'));
    }

    public function userTransactionSummary(Request $request)
    {
        $users = User::all();

        $userId = $request->route('user_id') ?? $request->input('user_id');
        $date = $request->input('created_at');

        $selectedUser = $userId ? User::find($userId) : null;

        // Build the query - exclude none status for user transaction summary
        $query = Transaction::where('status', '!=', TxnStatus::None)
            ->select(['type', 'status', DB::raw('SUM(amount) as total')])
            ->groupBy('type', 'status');

        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        // Date range support
        if (!empty($date)) {
            $dates = explode(' to ', $date);
            if (count($dates) === 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            } else {
                $query->whereDate('created_at', Carbon::parse($date));
            }
        }

        $results = $query->get()->groupBy(function ($item) {
            return $item->type instanceof TxnType ? $item->type->value : (string) $item->type;
        });

        $incomingTypes = TxnTypeGroup::incoming();
        $outgoingTypes = TxnTypeGroup::outgoing();

        $incomingSummary = [];
        $outgoingSummary = [];

        foreach (getFilteredTxnTypes() as $type) {
            $key = $type->value;

            $records = $results->get($key, collect());

            $success = round($records->filter(fn ($r) => $r->status === TxnStatus::Success)->sum('total'), 2);
            $pending = round($records->filter(fn ($r) => $r->status === TxnStatus::Pending)->sum('total'), 2);
            $rejected = round($records->filter(fn ($r) => $r->status === TxnStatus::Failed)->sum('total'), 2);


            $total_amount = $records->sum('total');

            $row = [
                'type' => $type->label(),
                'desc' => $type->description(),
                'success' => $success,
                'pending' => $pending,
                'rejected' => $rejected,

                'total' => $total_amount,
            ];

            if (in_array($type->value, $incomingTypes)) {
                $incomingSummary[] = $row;
            } elseif (in_array($type->value, $outgoingTypes)) {
                $outgoingSummary[] = $row;
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('backend.transaction.include.__report_table', [
                    'incomingSummary' => $incomingSummary,
                    'outgoingSummary' => $outgoingSummary,
                ])->render()
            ]);
        }

        return view('backend.transaction.report', compact('incomingSummary', 'outgoingSummary', 'users', 'selectedUser'));

    }

    public function referralNetworkReport(Request $request)
    {
        $email = $request->input('email');
        if ($request->ajax()) {
            $date = $request->input('created_at');

            if (!$email) {
                return response()->json([
                    'data' => [],
                    'message' => 'Search by email to view the referral network and their payments.'
                ]);
            }

            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'data' => [],
                    'message' => 'User not found.'
                ]);
            }

            $referralTree = $this->getAllReferralsWithLevel($user);
            $allUsers = collect([
                ['user' => $user, 'level' => 0]
            ])->merge($referralTree);

            $userIds = $allUsers->pluck('user.id')->toArray();
            $currency = setting('site_currency', 'global');

            // Build base transaction query - exclude none status for referral network report
            $txnQuery = Transaction::where('status', '!=', TxnStatus::None)
                ->select(['user_id', 'type', 'status', DB::raw('SUM(amount) as total')])
                ->whereIn('user_id', $userIds)
                ->groupBy('user_id', 'type', 'status');

            // Apply optional date filter
            if (!empty($date)) {
                $dates = explode(' to ', $date);
                if (count($dates) === 2) {
                    $startDate = Carbon::parse($dates[0])->startOfDay();
                    $endDate = Carbon::parse($dates[1])->endOfDay();
                    $txnQuery->whereBetween('created_at', [$startDate, $endDate]);
                } else {
                    $txnQuery->whereDate('created_at', Carbon::parse($date));
                }
            }

            $allTransactions = $txnQuery->get()->groupBy('user_id');

            $incomingTypes = TxnTypeGroup::incoming();
            $outgoingTypes = TxnTypeGroup::outgoing();
            $listedTypes   = TxnTypeGroup::listed();

            // Prepare DataTable data
            $data = $allUsers->map(function ($item) use ($allTransactions, $incomingTypes, $outgoingTypes, $currency) {
                $ref = $item['user'];
                $level = $item['level'];

                $grouped = $allTransactions->get(
                    $ref->id, collect())->groupBy(fn($txn) => $txn->type instanceof TxnType ? $txn->type->value : $txn->type);

                $incomingSummary = [];
                $outgoingSummary = [];

                foreach (getFilteredTxnTypes() as $type) {
                    $key = $type->value;
                    $records = $grouped->get($key, collect());

                    $row = [
                        'key' => $key, // raw enum value e.g., 'ib_bonus'
                        'type' => $type->label(), // e.g., 'IB Bonus'
                        'desc' => $type->description(),
                        'success' => round($records->where('status', TxnStatus::Success)->sum('total'), 2),
                        'pending' => round($records->where('status', TxnStatus::Pending)->sum('total'), 2),
                        'rejected' => round($records->where('status', TxnStatus::Failed)->sum('total'), 2),

                        'total' => round($records->sum('total'), 2),
                    ];

                    if (in_array($key, $incomingTypes)) {
                        $incomingSummary[] = $row;
                    } elseif (in_array($key, $outgoingTypes)) {
                        $outgoingSummary[] = $row;
                    }
                }

                $summary = [
                    'incoming' => $incomingSummary,
                    'outgoing' => $outgoingSummary
                ];

                return [
                    'user' => $ref,
                    'level' => $level,
                    'currency' => $currency,
                    'summary' => $summary,
                    'details' => view('backend.transaction.include.__txn_summary', [
                        'summary' => $summary,
                        'user' => $ref
                    ])->render(),
                ];
            });

            // Flatten all transactions from all users
            $flatTxn = $data->flatMap(fn($row) =>
            collect($row['summary']['incoming'])->merge($row['summary']['outgoing'])
            );

            // Separate key totals
            $demoDeposit = $flatTxn->firstWhere('key', 'demo_deposit') ?? [
                    'type' => 'Demo Deposit',
                    'total' => 0,
                    'success' => 0,
                    'pending' => 0,
                    'rejected' => 0,
                ];

            $ibBonus = $flatTxn->firstWhere('key', 'ib_bonus') ?? [
                    'type' => 'IB Bonus',
                    'total' => 0,
                    'success' => 0,
                    'pending' => 0,
                    'rejected' => 0,
                ];

            // Incoming total (excluding ib_bonus and demo_deposit)
            $incomingTotal = $flatTxn
                ->filter(fn($item) =>
                    in_array($item['key'], $incomingTypes)
                    && !in_array($item['key'], ['ib_bonus', 'demo_deposit'])
                )
                ->reduce(function ($carry, $item) {
                    $carry['total'] += $item['total'];
                    $carry['success'] += $item['success'];
                    $carry['pending'] += $item['pending'];
                    $carry['rejected'] += $item['rejected'];
                    return $carry;
                }, [
                    'type' => 'Incoming Total',
                    'desc' => 'All incoming payments except IB Bonus and Demo Deposit',
                    'total' => 0,
                    'success' => 0,
                    'pending' => 0,
                    'rejected' => 0,
                ]);

            // Outgoing total
            $outgoingTotal = $flatTxn
                ->filter(fn($item) => in_array($item['key'], $outgoingTypes))
                ->reduce(function ($carry, $item) {
                    $carry['total'] += $item['total'];
                    $carry['success'] += $item['success'];
                    $carry['pending'] += $item['pending'];
                    $carry['rejected'] += $item['rejected'];
                    return $carry;
                }, [
                    'type' => 'Outgoing Total',
                    'desc' => 'All outgoing payments',
                    'total' => 0,
                    'success' => 0,
                    'pending' => 0,
                    'rejected' => 0,
                ]);

            // Final totals list
            $networkTotals = collect([
                $incomingTotal,
                $outgoingTotal,
                [
                    'type' => 'Demo Deposit',
                    'desc' => 'Virtual funds added in demo accounts',
                    'total' => $demoDeposit['total'],
                    'success' => $demoDeposit['success'],
                    'pending' => $demoDeposit['pending'],
                    'rejected' => $demoDeposit['rejected'],
                ],
                [
                    'type' => 'IB Bonus',
                    'desc' => 'Commission for introducing brokers',
                    'total' => $ibBonus['total'],
                    'success' => $ibBonus['success'],
                    'pending' => $ibBonus['pending'],
                    'rejected' => $ibBonus['rejected'],
                ],
            ]);

            $level = LevelReferral::where('type', 'investment')->max('the_order') + 1;

            $treeHtml = view('backend.transaction.include.__tree_wrapper', [
                'user' => $user,
                'level' => $level
            ])->render();

            return DataTables::of($data)
                ->editColumn('user', function ($row) {
                    return view('backend.transaction.include.__user', [
                        'user' => $row['user'],
                        'user_id' => $row['user']['id'],
                    ])->render();
                })
                ->addColumn('incoming', function ($row) {
                    $total = collect($row['summary']['incoming'] ?? [])
                        ->reject(fn($txn) => strtolower($txn['key'] ?? '') === 'ib_bonus')
                        ->sum('total');

                    return number_format($total, 2).' '.$row['currency'];
                })
                ->addColumn('outgoing', function ($row) {
                    $total = collect($row['summary']['outgoing'] ?? [])
                        ->sum('total');

                    return number_format($total, 2).' '.$row['currency'];
                })
                ->addColumn('ib_bonus', function ($row) {
                    $bonus = collect($row['summary']['incoming'] ?? [])
                            ->firstWhere('key', 'ib_bonus')['total'] ?? 0;

                    return number_format($bonus, 2).' '.$row['currency'];
                })
                ->addColumn('action', function () {
                    return '<button class="action-btn toggle-details">+</button>';
                })
                ->rawColumns(['user', 'incoming', 'outgoing', 'ib_bonus', 'action', 'details'])
                ->with([
                    'tree_html' => $treeHtml,
                    'network_totals' => $networkTotals
                ])
                ->make(true);
        }

        // Handle normal GET request (view)
        return view('backend.transaction.referral_network_report');
    }

    public function getAllReferralsWithLevel(User $user, int $level = 1, array &$result = [], array &$visited = [])
    {
        // Prevent cyclic references or duplicates
        if (in_array($user->id, $visited)) {
            return collect($result);
        }

        $visited[] = $user->id;

        // Get direct referrals once
        $referrals = $user->referrals;

        foreach ($referrals as $referral) {
            $result[] = [
                'user' => $referral,
                'level' => $level
            ];

            // Recurse
            $this->getAllReferralsWithLevel($referral, $level + 1, $result, $visited);
        }

        return collect($result);
    }

}
