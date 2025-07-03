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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Models\DepositMethod;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct()
    {
        $this->middleware('permission:transaction-list');

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
            $filters = $request->only(['email', 'status', 'type', 'created_at']);

            // Get accessible users using helper function
            $accessibleUsersQuery = getAccessibleUserIds();
            $accessibleUserIds = $accessibleUsersQuery->pluck('id');

            // Base query (without order) for summary
            $baseQuery = Transaction::query();

            if ($id && $accessibleUserIds->contains($id)) {
                $baseQuery->where('user_id', $id);
            } elseif (!$id && $accessibleUserIds->isNotEmpty()) {
                $baseQuery->whereIn('user_id', $accessibleUserIds);
            } else {
                $baseQuery->whereNull('id');
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
        // Build the query
        $query = Transaction::select(['type', 'status', DB::raw('SUM(amount) as total')])->groupBy('type', 'status');

        $results = $query->get()->groupBy(function ($item) {
            return $item->type instanceof TxnType ? $item->type->value : (string) $item->type;
        });

        $incomingTypes = [
            'deposit',
            'manual_deposit',
            'demo_deposit',
            'receive_money',
            'receive_money_internal',
            'referral',
            'signup_bonus',
            'bonus',
            'bonus_refund',
            'ib_bonus',
            'voucher_deposit',
            'refund',
        ];
        $outgoingTypes = [
            'withdraw',
            'withdraw_auto',
            'send_money',
            'send_money_internal',
            'subtract',
            'bonus_subtract',
        ];

        $incomingSummary = [];
        $outgoingSummary = [];

        foreach (getFilteredTxnTypes() as $type) {
            $key = $type->value;

            $records = $results->get($key, collect());

            $success = round($records->filter(fn ($r) => $r->status === TxnStatus::Success)->sum('total'), 2);
            $pending = round($records->filter(fn ($r) => $r->status === TxnStatus::Pending)->sum('total'), 2);
            $rejected = round($records->filter(fn ($r) => $r->status === TxnStatus::Failed)->sum('total'), 2);
            $none = round($records->filter(fn ($r) => $r->status === TxnStatus::None)->sum('total'), 2);

            $total_amount = $records->sum('total');

            $row = [
                'type' => $type->label(),
                'desc' => $type->description(),
                'success' => $success,
                'pending' => $pending,
                'rejected' => $rejected,
                'none' => $none,
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

        // Build the query
        $query = Transaction::query()
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

        $incomingTypes = [
            'deposit',
            'manual_deposit',
            'demo_deposit',
            'receive_money',
            'receive_money_internal',
            'referral',
            'signup_bonus',
            'bonus',
            'bonus_refund',
            'ib_bonus',
            'voucher_deposit',
            'refund',
        ];
        $outgoingTypes = [
            'withdraw',
            'withdraw_auto',
            'send_money',
            'send_money_internal',
            'subtract',
            'bonus_subtract',
        ];

        $incomingSummary = [];
        $outgoingSummary = [];

        foreach (getFilteredTxnTypes() as $type) {
            $key = $type->value;

            $records = $results->get($key, collect());

            $success = round($records->filter(fn ($r) => $r->status === TxnStatus::Success)->sum('total'), 2);
            $pending = round($records->filter(fn ($r) => $r->status === TxnStatus::Pending)->sum('total'), 2);
            $rejected = round($records->filter(fn ($r) => $r->status === TxnStatus::Failed)->sum('total'), 2);
            $none = round($records->filter(fn ($r) => $r->status === TxnStatus::None)->sum('total'), 2);

            $total_amount = $records->sum('total');

            $row = [
                'type' => $type->label(),
                'desc' => $type->description(),
                'success' => $success,
                'pending' => $pending,
                'rejected' => $rejected,
                'none' => $none,
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

}
