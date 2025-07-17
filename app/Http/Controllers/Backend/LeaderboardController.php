<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\IbGroup;
use App\Models\Transaction;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Helpers\TxnTypeGroup;
use Illuminate\Support\Facades\DB;
use DataTables;

class LeaderboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ib-leaderboard-list', ['only' => ['index']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $date = $request->input('created_at');
        $ibGroupId = $request->input('ib_group_id');
        $currencySymbol = setting('currency_symbol', 'global');

        $from = $to = null;
        if (!empty($date)) {
            $dates = explode(' to ', $date);
            if (count($dates) === 2) {
                $from = Carbon::parse($dates[0])->startOfDay();
                $to = Carbon::parse($dates[1])->endOfDay();
            } else {
                $from = Carbon::parse($date)->startOfDay();
                $to = Carbon::parse($date)->endOfDay();
            }
        }

        if ($ibGroupId === 'all') {
            $ibGroupId = null;
        }

        $query = User::query()
            ->with('ibGroup')
            ->whereNotNull('ib_group_id')
            ->where('ib_status', 'approved')
            ->when($ibGroupId, fn($q) => $q->where('ib_group_id', $ibGroupId))
            ->withSum(['transaction as incoming_total' => function ($q) use ($from, $to) {
                $q->whereIn('type', TxnTypeGroup::incoming());
                if ($from && $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                }
            }], 'amount')
            ->withSum(['transaction as outgoing_total' => function ($q) use ($from, $to) {
                $q->whereIn('type', TxnTypeGroup::outgoing());
                if ($from && $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                }
            }], 'amount');

        $users = $query->orderByDesc('incoming_total')->get();

        foreach ($users as $user) {
            $referrals = $this->getAllReferrals($user);
            $referralUserIds = $referrals->pluck('id');

            $user->network_user_count = $referrals->count();

            $user->network_incoming = Transaction::whereIn('user_id', $referralUserIds)
                ->whereIn('type', TxnTypeGroup::incoming())
                ->when($from && $to, fn($q) => $q->whereBetween('created_at', [$from, $to]))
                ->sum('amount');

            $user->network_outgoing = Transaction::whereIn('user_id', $referralUserIds)
                ->whereIn('type', TxnTypeGroup::outgoing())
                ->when($from && $to, fn($q) => $q->whereBetween('created_at', [$from, $to]))
                ->sum('amount');
        }

        $top1 = $users->first();
        $top3 = $users->take(3);

        $top1Details = ['incoming' => [], 'outgoing' => []];

        if ($top1) {
            $referrals = $this->getAllReferrals($top1);
            $ids = $referrals->pluck('id')->prepend($top1->id);
            $transactions = Transaction::whereIn('user_id', $ids)
                ->when($from && $to, fn($q) => $q->whereBetween('created_at', [$from, $to]))
                ->get()
                ->groupBy(fn($txn) => $txn->type->value);

            foreach (getFilteredTxnTypes() as $type) {
                $key = $type->value;
                $records = $transactions->get($key, collect());

                $row = [
                    'key' => $key,
                    'type' => $type->label(),
                    'desc' => $type->description(),
                    'success' => round($records->where('status', TxnStatus::Success)->sum('amount'), 2),
                    'pending' => round($records->where('status', TxnStatus::Pending)->sum('amount'), 2),
                    'rejected' => round($records->where('status', TxnStatus::Failed)->sum('amount'), 2),
                    'none' => round($records->where('status', TxnStatus::None)->sum('amount'), 2),
                    'total' => round($records->sum('amount'), 2),
                ];

                if (in_array($key, TxnTypeGroup::incoming())) {
                    $top1Details['incoming'][] = $row;
                } elseif (in_array($key, TxnTypeGroup::outgoing())) {
                    $top1Details['outgoing'][] = $row;
                }
            }
        }

        if($request->ajax()) {
            if (!$request->has('datatable')) {
                $summaryHtml = view('backend.leaderboard.summary', [
                    'top3' => $top3,
                    'top1' => $top1,
                    'top1Details' => $top1Details,
                    'hasData' => $users->isNotEmpty(),
                    'currencySymbol' => $currencySymbol,
                ])->render();
            }

            if ($request->has('datatable')) {
                return DataTables::of($users)
                    ->addIndexColumn()
                    ->editColumn('user', function ($row) {
                        return view('backend.leaderboard.include.__user', ['user' => $row])->render();
                    })
                    ->addColumn('ib_group', function ($row) {
                        return $row->ibGroup->name ?? 'N/A';
                    })
                    ->addColumn('network_users', function ($row) {
                        return $row->network_user_count ?? 0;
                    })
                    ->addColumn('incoming_total', function ($row) {
                        $amount = $row->incoming_total + $row->network_incoming ?? 0;
                        return setting('currency_symbol', 'global') . number_format($amount, 2);
                    })
                    ->addColumn('outgoing_total', function ($row) {
                        $amount = $row->outgoing_total + $row->network_outgoing ?? 0;
                        return setting('currency_symbol', 'global') . number_format($amount, 2);
                    })
                    ->addColumn('actions', function ($row) {
                        return view('backend.leaderboard.include.__action', ['user' => $row])->render();
                    })
                    ->rawColumns(['user', 'ib_group', 'network_users', 'actions'])
                    ->make(true);
            }

            return response()->json(['summaryHtml' => $summaryHtml]);
        }

        // Breakdown for top1
        $top1 = $users->first();

        $top1Details = [
            'incoming' => [],
            'outgoing' => [],
        ];

        if ($top1) {
            $referrals = $this->getAllReferrals($top1);
            $ids = $referrals->pluck('id')->prepend($top1->id);

            // ADD DATE FILTERING HERE
            $transactions = Transaction::whereIn('user_id', $ids)
                ->when($from && $to, fn ($q) => $q->whereBetween('created_at', [$from, $to]))
                ->get();

            $groupedTxns = $transactions->groupBy(fn ($txn) => $txn->type->value);


            $incomingSummary = [];
            $outgoingSummary = [];

            foreach (getFilteredTxnTypes() as $type) {
                $key = $type->value;
                $records = $groupedTxns->get($key, collect());

                $row = [
                    'key' => $key,
                    'type' => $type->label(),
                    'desc' => $type->description(),
                    'success' => round($records->where('status', TxnStatus::Success)->sum('amount'), 2),
                    'pending' => round($records->where('status', TxnStatus::Pending)->sum('amount'), 2),
                    'rejected' => round($records->where('status', TxnStatus::Failed)->sum('amount'), 2),
                    'none' => round($records->where('status', TxnStatus::None)->sum('amount'), 2),
                    'total' => round($records->sum('amount'), 2),
                ];

                if (in_array($key, TxnTypeGroup::incoming())) {
                    $incomingSummary[] = $row;
                } elseif (in_array($key, TxnTypeGroup::outgoing())) {
                    $outgoingSummary[] = $row;
                }
            }

            $top1Details = [
                'incoming' => $incomingSummary,
                'outgoing' => $outgoingSummary
            ];
        }

        return view('backend.leaderboard.index', [
            'top3' => $users->take(3),
            'top1' => $top1,
            'top1Details' => $top1Details,
            'hasData' => $users->isNotEmpty(),
            'created_at' => $date,
            'ib_group_id' => $ibGroupId,
            'currencySymbol' => $currencySymbol,
            'ibGroups' => IbGroup::where('status', true)->get(['id', 'name']),
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAllReferrals(User $user, array &$visited = [], int $depth = 10)
    {
        $referrals = collect();

        if (in_array($user->id, $visited) || $depth <= 0) {
            return $referrals;
        }

        $visited[] = $user->id;

        $directReferrals = $user->referrals()->get();

        foreach ($directReferrals as $referral) {
            $referrals->push($referral);
            $referrals = $referrals->merge($this->getAllReferrals($referral, $visited, $depth - 1));
        }

        return $referrals->unique('id');
    }
}
