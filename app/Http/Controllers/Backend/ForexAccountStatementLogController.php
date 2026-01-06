<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ForexAccountStatementLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Carbon\Carbon;

class ForexAccountStatementLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:forex-statement-logs', ['only' => ['index']]);
        $this->middleware('permission:forex-statement-logs-clear', ['only' => ['clearLogs']]);

    }

    /**
     * Display a listing of the statement logs.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ForexAccountStatementLog::with('forexAccount')
                ->select([
                    'id',
                    'forex_account_id',
                    'account_login',
                    'user_email',
                    'statement_date',
                    'status',
                    'error_message',
                    'pdf_size',
                    'sent_at'
                ]);

            return DataTables::of($query)
                ->addColumn('account_login', function ($log) {
                    return '<span class="font-medium"># '.$log->account_login.'</span>';
                })
                ->addColumn('user_email', function ($log) {
                    return '<span class="lowercase">'.$log->user_email.'</span>';
                })
                ->addColumn('status_badge', function ($log) {
                    $class = $log->status === 'sent' ? 'success' : 'danger';
                    $text = $log->status === 'sent' ? 'Sent' : 'Failed';
                    return '<span class="badge badge-' . $class . '">' . $text . '</span>';
                })
                ->addColumn('file_size', function ($log) {
                    return $log->formatted_file_size;
                })
                ->editColumn('statement_date', function ($log) {
                    // Database stores in UTC, convert to site timezone for display
                    // Use getOriginal to bypass accessor and get raw UTC timestamp
                    $statementDate = $log->getOriginal('statement_date');
                    return toSiteTimezone($statementDate, 'Y-m-d');
                })
                ->editColumn('sent_at', function ($log) {
                    if (!$log->sent_at) {
                        return 'N/A';
                    }
                    // Database stores in UTC, convert to site timezone for display
                    // Use getOriginal to bypass accessor and get raw UTC timestamp
                    $sentAt = $log->getOriginal('sent_at');
                    return toSiteTimezone($sentAt, 'Y-m-d H:i:s');
                })
                ->rawColumns(['account_login','user_email','status_badge'])
                ->make(true);
        }

        $existLog = ForexAccountStatementLog::count();

        return view('backend.investment.statement_logs', compact('existLog'));
    }

    /**
     * Clear logs based on criteria.
     */
    public function clearLogs(Request $request)
    {
        $request->validate([
            'clear_option' => 'required|in:all,30_days,90_days,failed_only,date_range',
            'from_date' => 'required_if:clear_option,date_range|date',
            'to_date' => 'required_if:clear_option,date_range|date|after_or_equal:from_date',
        ]);

        try {
            $query = ForexAccountStatementLog::query();
            $deletedCount = 0;

            switch ($request->clear_option) {
                case 'all':
                    $deletedCount = $query->count();
                    $query->delete();
                    break;

                case '30_days':
                    $cutoffDate = Carbon::now()->subDays(30);
                    $deletedCount = $query->where('statement_date', '<', $cutoffDate)->count();
                    $query->where('statement_date', '<', $cutoffDate)->delete();
                    break;

                case '90_days':
                    $cutoffDate = Carbon::now()->subDays(90);
                    $deletedCount = $query->where('statement_date', '<', $cutoffDate)->count();
                    $query->where('statement_date', '<', $cutoffDate)->delete();
                    break;

                case 'failed_only':
                    $deletedCount = $query->where('status', 'failed')->count();
                    $query->where('status', 'failed')->delete();
                    break;

                case 'date_range':
                    $deletedCount = $query->whereBetween('statement_date', [
                        $request->from_date,
                        $request->to_date
                    ])->count();
                    $query->whereBetween('statement_date', [
                        $request->from_date,
                        $request->to_date
                    ])->delete();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => __('Successfully deleted :count log entries.', ['count' => $deletedCount]),
                'deleted_count' => $deletedCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to clear statement logs', [
                'error' => $e->getMessage(),
                'clear_option' => $request->clear_option
            ]);

            return response()->json([
                'success' => false,
                'message' => __('Failed to clear logs: :error', ['error' => $e->getMessage()])
            ], 500);
        }
    }

    /**
     * Export logs to CSV.
     */
    public function exportLogs(Request $request)
    {
        $query = ForexAccountStatementLog::query();

        // Apply same filters as index
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('statement_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('statement_date', '<=', $request->date_to);
        }

        $logs = $query->orderBy('statement_date', 'desc')->get();

        $filename = 'forex_statement_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Account Login',
                'User Email',
                'Statement Date',
                'Status',
                'PDF Size',
                'Sent At',
                'Error Message'
            ]);

            // CSV data
            foreach ($logs as $log) {
                // Database stores in UTC, convert to site timezone for display
                // Use getOriginal to bypass accessor and get raw UTC timestamp
                $statementDate = $log->getOriginal('statement_date');
                $sentAt = $log->getOriginal('sent_at');
                fputcsv($file, [
                    $log->account_login,
                    $log->user_email,
                    toSiteTimezone($statementDate, 'Y-m-d'),
                    $log->status_text,
                    $log->formatted_file_size,
                    toSiteTimezone($sentAt, 'Y-m-d H:i:s'),
                    $log->error_message ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Calculate success rate percentage.
     */
    private function calculateSuccessRate(): float
    {
        $total = ForexAccountStatementLog::count();
        
        if ($total === 0) {
            return 100.0;
        }

        $successful = ForexAccountStatementLog::where('status', 'sent')->count();
        
        return round(($successful / $total) * 100, 2);
    }
}