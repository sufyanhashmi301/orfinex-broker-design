<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\IbGroup;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Helpers\TxnTypeGroup;
use App\Services\IBTransactionQueryService;
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
            try {
                $dates = explode(' to ', trim($date));
                if (count($dates) === 2) {
                    $from = Carbon::parse(trim($dates[0]))->startOfDay();
                    $to = Carbon::parse(trim($dates[1]))->endOfDay();
                } else {
                    $from = Carbon::parse(trim($date))->startOfDay();
                    $to = Carbon::parse(trim($date))->endOfDay();
                }
            } catch (\Exception $e) {
                // Invalid date format, ignore date filter
                $from = $to = null;
            }
        }

        if ($ibGroupId === 'all') {
            $ibGroupId = null;
        }

        // Get IB users
        $query = User::query()
            ->with('ibGroup')
            ->whereNotNull('ib_group_id')
            ->where('ib_status', 'approved')
            ->when($ibGroupId, fn($q) => $q->where('ib_group_id', $ibGroupId));

        $users = $query->get();

        // Calculate user transactions
        // ib_bonus from IB transaction tables + other transactions from main transactions table
        foreach ($users as $user) {
            // Get ib_bonus from IB transaction tables (incoming)
            $ibBonusAmount = IBTransactionQueryService::getUserIBTransactionsSum(
                $user->id,
                $from,
                $to
            );
            
            // Get other incoming transactions from main transactions table (excluding ib_bonus)
            $otherIncomingAmount = IBTransactionQueryService::getUserOtherTransactionsSum(
                $user->id,
                TxnTypeGroup::incoming(),
                $from,
                $to
            );
            
            // Get outgoing transactions from main transactions table
            $outgoingAmount = IBTransactionQueryService::getUserOtherTransactionsSum(
                $user->id,
                TxnTypeGroup::outgoing(),
                $from,
                $to
            );
            
            $user->incoming_total = $ibBonusAmount + $otherIncomingAmount;
            $user->outgoing_total = $outgoingAmount;
        }

        // Calculate network transactions
        foreach ($users as $user) {
            $referrals = $this->getAllReferrals($user);
            $referralUserIds = $referrals->pluck('id');

            $user->network_user_count = $referrals->count();

            // Get ib_bonus from IB transaction tables (incoming)
            $networkIBBonusAmount = IBTransactionQueryService::getNetworkIBTransactionsSum(
                $referralUserIds->toArray(),
                $from,
                $to
            );
            
            // Get other incoming transactions from main transactions table (excluding ib_bonus)
            $networkOtherIncomingAmount = IBTransactionQueryService::getNetworkOtherTransactionsSum(
                $referralUserIds->toArray(),
                TxnTypeGroup::incoming(),
                $from,
                $to
            );
            
            // Get outgoing transactions from main transactions table
            $networkOutgoingAmount = IBTransactionQueryService::getNetworkOtherTransactionsSum(
                $referralUserIds->toArray(),
                TxnTypeGroup::outgoing(),
                $from,
                $to
            );
            
            $user->network_incoming = $networkIBBonusAmount + $networkOtherIncomingAmount;
            $user->network_outgoing = $networkOutgoingAmount;
        }

        // Sort by incoming_total descending
        $users = $users->sortByDesc('incoming_total')->values();

        $top1 = $users->first();
        $top3 = $users->take(3);

        // Calculate top1 details (reused for both AJAX and non-AJAX)
        $top1Details = $this->getTop1Details($top1, $from, $to);

        if ($request->ajax() || $request->wantsJson()) {
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
                        $amount = ($row->incoming_total ?? 0) + ($row->network_incoming ?? 0);
                        return setting('currency_symbol', 'global') . number_format($amount, 2);
                    })
                    ->addColumn('outgoing_total', function ($row) {
                        $amount = ($row->outgoing_total ?? 0) + ($row->network_outgoing ?? 0);
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

    /**
     * Get top1 user transaction details
     *
     * @param User|null $top1
     * @param Carbon|null $from
     * @param Carbon|null $to
     * @return array
     */
    private function getTop1Details($top1, $from = null, $to = null)
    {
        $top1Details = [
            'incoming' => [],
            'outgoing' => [],
        ];

        if (!$top1) {
            return $top1Details;
        }

        $referrals = $this->getAllReferrals($top1);
        $ids = $referrals->pluck('id')->prepend($top1->id);

        // Get ib_bonus transactions from IB transaction tables
        $ibTransactions = IBTransactionQueryService::getIBTransactionsForUsers(
            $ids->toArray(),
            $from,
            $to
        );

        // Get other transactions from main transactions table
        $otherTransactions = IBTransactionQueryService::getOtherTransactionsForUsers(
            $ids->toArray(),
            $from,
            $to
        );

        // Normalize amount field for IB transactions (use final_amount if available, otherwise amount)
        // Also exclude none status transactions
        $ibTransactions = $ibTransactions->filter(function ($transaction) {
            $status = $transaction->status instanceof TxnStatus ? $transaction->status->value : (string)$transaction->status;
            return $status !== TxnStatus::None->value;
        })->map(function ($transaction) {
            // Use final_amount for IB transactions if available, otherwise use amount
            $transaction->amount = isset($transaction->final_amount) && $transaction->final_amount !== null
                ? (float) $transaction->final_amount
                : (float) ($transaction->amount ?? 0);
            return $transaction;
        });

        // Normalize amount field for regular transactions
        // Also exclude none status transactions
        $otherTransactions = $otherTransactions->filter(function ($transaction) {
            $status = $transaction->status instanceof TxnStatus ? $transaction->status->value : (string)$transaction->status;
            return $status !== TxnStatus::None->value;
        })->map(function ($transaction) {
            $transaction->amount = (float) ($transaction->amount ?? 0);
            return $transaction;
        });

        // Merge both collections
        $transactions = $ibTransactions->merge($otherTransactions);

        // Group by type value (from DB it's a string)
        $groupedTxns = $transactions->groupBy('type');

        foreach (getFilteredTxnTypes() as $type) {
            $key = $type->value;
            $records = $groupedTxns->get($key, collect());

            // Normalize status comparison (handle both enum and string)
            $success = round($records->filter(function ($r) {
                $status = $r->status instanceof TxnStatus ? $r->status->value : (string)$r->status;
                return $status === TxnStatus::Success->value;
            })->sum('amount'), 2);

            $pending = round($records->filter(function ($r) {
                $status = $r->status instanceof TxnStatus ? $r->status->value : (string)$r->status;
                return $status === TxnStatus::Pending->value;
            })->sum('amount'), 2);

            $rejected = round($records->filter(function ($r) {
                $status = $r->status instanceof TxnStatus ? $r->status->value : (string)$r->status;
                return $status === TxnStatus::Failed->value;
            })->sum('amount'), 2);

            $row = [
                'key' => $key,
                'type' => $type->label(),
                'desc' => $type->description(),
                'success' => $success,
                'pending' => $pending,
                'rejected' => $rejected,
                'total' => round($records->sum('amount'), 2),
            ];

            // Categorize by incoming/outgoing
            if (in_array($key, TxnTypeGroup::incoming())) {
                $top1Details['incoming'][] = $row;
            } elseif (in_array($key, TxnTypeGroup::outgoing())) {
                $top1Details['outgoing'][] = $row;
            }
        }

        return $top1Details;
    }
}
