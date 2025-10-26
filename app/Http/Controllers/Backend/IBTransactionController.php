<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\IBTransactionQueryService;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class IBTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:transaction-list');
    }

    /**
     * Display IB transactions for all users
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Prepare filters from request
            $filters = $request->only([
                'created_at', 'status', 'type', 'amount_min', 'amount_max', 
                'tnx', 'description', 'login', 'deal', 'order', 'symbol', 'user_id'
            ]);
            
            // Get IB transactions from quarter tables
            $data = IBTransactionQueryService::getAllIBTransactions($filters);
            
            if (!$data) {
                return Datatables::of(collect([]))->make(true);
            }
            
            return Datatables::of($data->orderBy('created_at', 'desc'))
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    $user = \App\Models\User::find($row->user_id);
                    if ($user) {
                        return '<div class="flex items-center">
                                    <div class="flex-none">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-900 text-slate-900 dark:text-white flex flex-col items-center justify-center text-xs font-medium">
                                            ' . strtoupper(substr($user->first_name ?? 'U', 0, 1)) . '
                                        </div>
                                    </div>
                                    <div class="flex-1 ltr:ml-2 rtl:mr-2">
                                        <div class="text-slate-600 dark:text-slate-300 text-sm font-medium mb-1">
                                            ' . ($user->first_name . ' ' . $user->last_name) . '
                                        </div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            ' . $user->email . '
                                        </div>
                                    </div>
                                </div>';
                    }
                    return 'User #' . $row->user_id;
                })
                ->editColumn('status', function ($row) {
                    $statusClass = match($row->status) {
                        'success' => 'bg-success-500',
                        'pending' => 'bg-warning-500', 
                        'rejected' => 'bg-danger-500',
                        default => 'bg-slate-500'
                    };
                    return '<span class="badge ' . $statusClass . ' text-white capitalize">' . $row->status . '</span>';
                })
                ->editColumn('type', function ($row) {
                    return '<span class="badge bg-primary-500 text-white">' . $row->type . '</span>';
                })
                ->editColumn('amount', function ($row) {
                    return '<span class="font-medium text-slate-900 dark:text-slate-100">$' . number_format($row->amount, 2) . '</span>';
                })
                ->editColumn('created_at', function ($row) {
                    if (!empty($row->manual_field_data) && $row->manual_field_data !== '[]') {
                        $manualData = json_decode($row->manual_field_data, true);
                        if (is_array($manualData) && isset($manualData['time'])) {
                            return Carbon::parse($manualData['time'])->format('M d, Y h:i A');
                        }
                    }
                    return Carbon::parse($row->created_at)->format('M d, Y h:i A');
                })
                ->addColumn('deal_info', function ($row) {
                    if (!empty($row->manual_field_data) && $row->manual_field_data !== '[]') {
                        $manualData = json_decode($row->manual_field_data, true);
                        if (is_array($manualData)) {
                            $deal = $manualData['deal'] ?? '-';
                            $symbol = $manualData['symbol'] ?? '-';
                            $login = $manualData['login'] ?? '-';
                            $order = $manualData['order'] ?? '-';
                            
                            return '<div class="text-xs">
                                        <div><strong>Deal:</strong> ' . $deal . '</div>
                                        <div><strong>Symbol:</strong> ' . $symbol . '</div>
                                        <div><strong>Login:</strong> ' . $login . '</div>
                                        <div><strong>Order:</strong> ' . $order . '</div>
                                    </div>';
                        }
                    }
                    return '<span class="text-slate-400">-</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="flex space-x-2">
                                <button class="action-btn" data-bs-toggle="tooltip" title="View Details" onclick="viewTransaction(\'' . $row->tnx . '\')">
                                    <iconify-icon icon="lucide:eye"></iconify-icon>
                                </button>
                            </div>';
                })
                ->rawColumns(['user', 'status', 'type', 'amount', 'deal_info', 'action'])
                ->make(true);
        }
        
        // Get available quarter tables for filter dropdown
        $quarterTables = IBTransactionQueryService::getAvailableQuarterTables();
        
        return view('backend.ib-transactions.index', compact('quarterTables'));
    }
    
    /**
     * Get IB transactions summary/statistics
     */
    public function summary(Request $request)
    {
        $filters = $request->only([
            'created_at', 'status', 'type', 'amount_min', 'amount_max', 
            'tnx', 'description', 'login', 'deal', 'order', 'symbol', 'user_id'
        ]);
        
        $data = IBTransactionQueryService::getAllIBTransactions($filters);
        
        if (!$data) {
            return response()->json([
                'total_amount' => 0,
                'total_count' => 0,
                'success_amount' => 0,
                'success_count' => 0,
                'pending_amount' => 0,
                'pending_count' => 0,
                'rejected_amount' => 0,
                'rejected_count' => 0,
            ]);
        }
        
        // Get summary data
        $summary = DB::query()->fromSub($data, 'ib_transactions')
            ->select([
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(CAST(amount AS DECIMAL(15,2))) as total_amount'),
                DB::raw('SUM(CASE WHEN status = "success" THEN CAST(amount AS DECIMAL(15,2)) ELSE 0 END) as success_amount'),
                DB::raw('SUM(CASE WHEN status = "success" THEN 1 ELSE 0 END) as success_count'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN CAST(amount AS DECIMAL(15,2)) ELSE 0 END) as pending_amount'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN CAST(amount AS DECIMAL(15,2)) ELSE 0 END) as rejected_amount'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count'),
            ])
            ->first();
            
        return response()->json([
            'total_amount' => number_format($summary->total_amount ?? 0, 2),
            'total_count' => number_format($summary->total_count ?? 0),
            'success_amount' => number_format($summary->success_amount ?? 0, 2),
            'success_count' => number_format($summary->success_count ?? 0),
            'pending_amount' => number_format($summary->pending_amount ?? 0, 2),
            'pending_count' => number_format($summary->pending_count ?? 0),
            'rejected_amount' => number_format($summary->rejected_amount ?? 0, 2),
            'rejected_count' => number_format($summary->rejected_count ?? 0),
        ]);
    }
    
    /**
     * Export IB transactions
     */
    public function export(Request $request)
    {
        $filters = $request->only([
            'created_at', 'status', 'type', 'amount_min', 'amount_max', 
            'tnx', 'description', 'login', 'deal', 'order', 'symbol', 'user_id'
        ]);
        
        $data = IBTransactionQueryService::getAllIBTransactions($filters);
        
        if (!$data) {
            return response()->json(['error' => 'No data to export'], 404);
        }
        
        $transactions = $data->orderBy('created_at', 'desc')->get();
        
        $filename = 'ib_transactions_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Transaction ID', 'User ID', 'User Name', 'User Email', 'Amount', 'Type', 'Status',
                'Description', 'Deal', 'Symbol', 'Login', 'Order', 'Created At'
            ]);
            
            foreach ($transactions as $transaction) {
                $user = \App\Models\User::find($transaction->user_id);
                $manualData = json_decode($transaction->manual_field_data, true) ?? [];
                
                fputcsv($file, [
                    $transaction->tnx,
                    $transaction->user_id,
                    $user ? ($user->first_name . ' ' . $user->last_name) : '',
                    $user ? $user->email : '',
                    $transaction->amount,
                    $transaction->type,
                    $transaction->status,
                    $transaction->description,
                    $manualData['deal'] ?? '',
                    $manualData['symbol'] ?? '',
                    $manualData['login'] ?? '',
                    $manualData['order'] ?? '',
                    $transaction->created_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get transaction details
     */
    public function show($tnx)
    {
        // Search for transaction in all quarter tables
        $currentYear = Carbon::now()->year;
        $years = [$currentYear - 1, $currentYear];
        
        $transaction = null;
        
        foreach ($years as $year) {
            $periods = IBTransactionPeriodService::getYearPeriods($year);
            
            foreach ($periods as $period) {
                $tableName = IBTransactionPeriodService::getTableName($period);
                
                if (\Illuminate\Support\Facades\Schema::hasTable($tableName)) {
                    $found = DB::table($tableName)->where('tnx', $tnx)->first();
                    if ($found) {
                        $transaction = $found;
                        break 2;
                    }
                }
            }
        }
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $user = \App\Models\User::find($transaction->user_id);
        $manualData = json_decode($transaction->manual_field_data, true) ?? [];
        
        return response()->json([
            'transaction' => $transaction,
            'user' => $user,
            'manual_data' => $manualData,
        ]);
    }
}
