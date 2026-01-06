<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ActivityLog::query()->with('actor');
            $this->applyFilters($data, $request);

            return datatables()->eloquent($data)
                ->addColumn('actor', function ($row) {
                    return view('backend.activity_tracking.include.__actor', compact('row'))->render();
                })
                ->addColumn('actor_type', function ($row) {
                    return class_basename($row->actor_type);
                })
                ->editColumn('action', function ($row) {
                    return $this->formatActionBadge($row->action);
                })
                ->addColumn('action_btn', function ($row) {
                    return view('backend.activity_tracking.include.__action', compact('row'))->render();
                })
                ->rawColumns(['actor', 'actor_type', 'action_btn', 'action'])
                ->make(true);
        }

        return view('backend.activity_tracking.all');
    }

    public function users(Request $request)
    {
        if ($request->ajax()) {
            $data = ActivityLog::query()->with('actor')->where('actor_type', 'App\\Models\\User');
            $this->applyFilters($data, $request);
            
            return datatables()->eloquent($data)
                ->addColumn('actor', function ($row) {
                    return view('backend.activity_tracking.include.__actor', compact('row'))->render();
                })
                ->addColumn('actor_type', function ($row) {
                    return class_basename($row->actor_type);
                })
                ->editColumn('action', function ($row) {
                    return $this->formatActionBadge($row->action);
                })
                ->addColumn('action_btn', function ($row) {
                    return view('backend.activity_tracking.include.__action', compact('row'))->render();
                })
                ->rawColumns(['actor', 'actor_type', 'action_btn', 'action'])
                ->make(true);
        }
        return view('backend.activity_tracking.users');
    }

    public function staff(Request $request)
    {
        if ($request->ajax()) {
            $data = ActivityLog::query()->with('actor')->where('actor_type', 'App\\Models\\Admin');
            $this->applyFilters($data, $request);
            
            return datatables()->eloquent($data)
                ->addColumn('actor', function ($row) {
                    return view('backend.activity_tracking.include.__actor', compact('row'))->render();
                })
                ->addColumn('actor_type', function ($row) {
                    return class_basename($row->actor_type);
                })
                ->editColumn('action', function ($row) {
                    return $this->formatActionBadge($row->action);
                })
                ->addColumn('action_btn', function ($row) {
                    return view('backend.activity_tracking.include.__action', compact('row'))->render();
                })
                ->rawColumns(['actor', 'actor_type', 'action_btn', 'action'])
                ->make(true);
        }
        return view('backend.activity_tracking.staff');
    }

    public function userActivities(Request $request, $id = null)
    {
        if ($request->ajax()) {
            $data = ActivityLog::query()->with('actor')->where('actor_type', 'App\\Models\\User')->where('actor_id', $request->id);
            $this->applyFilters($data, $request);
            
            return datatables()->eloquent($data)
                ->addColumn('actor_type', function ($row) {
                    return class_basename($row->actor_type);
                })
                ->editColumn('action', function ($row) {
                    return '<span class="font-medium">'.ucfirst(str_replace('_', ' ', $row->action)).'</span>';
                })
                ->addColumn('action_btn', function ($row) {
                    return view('backend.activity_tracking.include.__action', compact('row'))->render();
                })
                ->rawColumns(['actor', 'actor_type', 'action_btn', 'action'])
                ->make(true);
        }
        return view('backend.activity_tracking.user_activities', compact('user'));
    }

    public function show(Request $request)
    {
        $activityLog = ActivityLog::with('actor')->findOrFail($request->id);
        
        return response()->json([
            'id' => $activityLog->id,
            'action' => ucfirst(str_replace('_', ' ', $activityLog->action)),
            'description' => $activityLog->description,
            'ip' => $activityLog->ip,
            'agent' => $activityLog->agent,
            'location' => $activityLog->location,
            'meta' => $activityLog->meta,
            'created_at' => $activityLog->created_at,
            'actor' => $activityLog->actor ? [
                'name' => $activityLog->actor->full_name ?? $activityLog->actor->email,
                'email' => $activityLog->actor->email ?? '',
                'type' => class_basename($activityLog->actor_type)
            ] : null
        ]);
    }

    /**
     * Apply filters to the query
     */
    private function applyFilters($query, Request $request)
    {
        // Global search filter (first_name, last_name, email, username/name, description)
        if ($request->filled('global_search')) {
            $searchTerm = $request->global_search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('action', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('ip', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('location', 'LIKE', "%{$searchTerm}%")
                  // Search in User actors (with username)
                  ->orWhere(function ($subQ) use ($searchTerm) {
                      $subQ->where('actor_type', 'App\\Models\\User')
                           ->whereHas('actor', function ($actorQuery) use ($searchTerm) {
                               $actorQuery->where('first_name', 'LIKE', "%{$searchTerm}%")
                                          ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                                          ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                           });
                  })
                  // Search in Admin actors (with name instead of username)
                  ->orWhere(function ($subQ) use ($searchTerm) {
                      $subQ->where('actor_type', 'App\\Models\\Admin')
                           ->whereHas('actor', function ($actorQuery) use ($searchTerm) {
                               $actorQuery->where('first_name', 'LIKE', "%{$searchTerm}%")
                                          ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
                                          ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                           });
                  });
            });
        }

        // Action filter
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Status filter (from meta data)
        if ($request->filled('status')) {
            $query->whereJsonContains('meta->status', $request->status);
        }

        // Date filter with range support
        if ($request->filled('created_at')) {
            $dateValue = $request->created_at;
            
            // Handle date range (format: "2024-01-01 to 2024-01-31")
            if (strpos($dateValue, ' to ') !== false) {
                $dates = explode(' to ', $dateValue);
                if (count($dates) === 2) {
                    $query->whereBetween('created_at', [
                        Carbon::parse($dates[0])->startOfDay(),
                        Carbon::parse($dates[1])->endOfDay()
                    ]);
                }
            } else {
                // Handle single date or preset ranges
                switch ($dateValue) {
                    case 'today':
                        $query->whereDate('created_at', Carbon::today());
                        break;
                    case 'yesterday':
                        $query->whereDate('created_at', Carbon::yesterday());
                        break;
                    case 'last7':
                        $query->whereBetween('created_at', [
                            Carbon::now()->subDays(7)->startOfDay(),
                            Carbon::now()->endOfDay()
                        ]);
                        break;
                    case 'last30':
                        $query->whereBetween('created_at', [
                            Carbon::now()->subDays(30)->startOfDay(),
                            Carbon::now()->endOfDay()
                        ]);
                        break;
                    case 'thisMonth':
                        $query->whereBetween('created_at', [
                            Carbon::now()->startOfMonth(),
                            Carbon::now()->endOfMonth()
                        ]);
                        break;
                    case 'lastMonth':
                        $query->whereBetween('created_at', [
                            Carbon::now()->subMonth()->startOfMonth(),
                            Carbon::now()->subMonth()->endOfMonth()
                        ]);
                        break;
                    case 'ytd':
                        $query->whereBetween('created_at', [
                            Carbon::now()->startOfYear(),
                            Carbon::now()->endOfDay()
                        ]);
                        break;
                    default:
                        // Single date
                        $query->whereDate('created_at', Carbon::parse($dateValue));
                        break;
                }
            }
        }
    }

    /**
     * Export activities
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('actor');
        
        // Apply actor type filter if coming from specific page
        if ($request->filled('actor_type')) {
            if ($request->actor_type === 'user') {
                $query->where('actor_type', 'App\\Models\\User');
            } elseif ($request->actor_type === 'admin') {
                $query->where('actor_type', 'LIKE', '%Admin%');
            }
        }
        
        $this->applyFilters($query, $request);
        
        $activities = $query->orderBy('created_at', 'desc')->get();
        
        return $this->exportCsv($activities);
    }

    /**
     * Export to CSV
     */
    private function exportCsv($activities)
    {
        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Date/Time',
                'Action',
                'Actor Type',
                'Actor Name',
                'Actor Email',
                'Description',
                'IP Address',
                'Location',
                'User Agent',
                'Metadata'
            ]);

            // CSV Data
            foreach ($activities as $activity) {
                // Database stores in UTC, convert to site timezone for display
                // Use getOriginal to bypass accessor and get raw UTC timestamp
                $createdAt = $activity->getOriginal('created_at');
                fputcsv($file, [
                    toSiteTimezone($createdAt, 'Y-m-d H:i:s'),
                    $activity->action,
                    class_basename($activity->actor_type),
                    $activity->actor->full_name ?? '',
                    $activity->actor->email ?? '',
                    $activity->description ?? '',
                    $activity->ip,
                    $activity->location ?? '',
                    $activity->agent ?? '',
                    $activity->meta ? json_encode($activity->meta) : ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Format action badge
     */
    private function formatActionBadge($action)
    {
        $badges = [
            // Authentication Actions
            'user_login' => '<span class="badge badge-success bg-opacity-30 text-success">User Login</span>',
            'user_logout' => '<span class="badge badge-warning bg-opacity-30 text-warning">User Logout</span>',
            'admin_login' => '<span class="badge badge-primary bg-opacity-30 text-primary">Admin Login</span>',
            'admin_logout' => '<span class="badge badge-secondary bg-opacity-30 text-secondary">Admin Logout</span>',
            'login_failed' => '<span class="badge badge-danger bg-opacity-30 text-danger">Login Failed</span>',
            'password_reset' => '<span class="badge badge-warning bg-opacity-30 text-warning">Password Reset</span>',
            'email_verification' => '<span class="badge badge-danger bg-opacity-30 text-danger">Email Verification</span>',
            
            // Financial Actions
            'deposit' => '<span class="badge badge-success bg-opacity-30 text-success">Deposit</span>',
            'deposit_approved' => '<span class="badge badge-success bg-opacity-30 text-success">Deposit Approved</span>',
            'deposit_rejected' => '<span class="badge badge-danger bg-opacity-30 text-danger">Deposit Rejected</span>',
            'withdraw' => '<span class="badge badge-warning bg-opacity-30 text-warning">Withdraw</span>',
            'withdraw_approved' => '<span class="badge badge-success bg-opacity-30 text-success">Withdraw Approved</span>',
            'withdraw_rejected' => '<span class="badge badge-danger bg-opacity-30 text-danger">Withdraw Rejected</span>',
            'internal_transfer' => '<span class="badge badge-danger bg-opacity-30 text-danger">Internal Transfer</span>',
            'external_transfer' => '<span class="badge badge-danger bg-opacity-30 text-danger">External Transfer</span>',
            'transfer_approved' => '<span class="badge badge-success bg-opacity-30 text-success">Transfer Approved</span>',
            'transfer_rejected' => '<span class="badge badge-danger bg-opacity-30 text-danger">Transfer Rejected</span>',
            'deposit_demo' => '<span class="badge badge-success bg-opacity-30 text-success">Deposit Demo</span>',
            'voucher_redeem' => '<span class="badge badge-success bg-opacity-30 text-success">Voucher Redeem</span>',
            
            // User Management Actions
            'user_register' => '<span class="badge badge-success bg-opacity-30 text-success">User Register</span>',
            'user_create' => '<span class="badge badge-primary bg-opacity-30 text-primary">User Create</span>',
            'user_update' => '<span class="badge badge-info bg-opacity-30 text-info">User Update</span>',
            'user_delete' => '<span class="badge badge-danger bg-opacity-30 text-danger">User Delete</span>',
            'user_suspend' => '<span class="badge badge-warning bg-opacity-30 text-warning">User Suspend</span>',
            'user_activate' => '<span class="badge badge-success bg-opacity-30 text-success">User Activate</span>',
            'profile_update' => '<span class="badge badge-info bg-opacity-30 text-info">Profile Update</span>',
            'password_update' => '<span class="badge badge-warning bg-opacity-30 text-warning">Password Update</span>',
            'email_update' => '<span class="badge badge-info bg-opacity-30 text-info">Email Update</span>',
            'phone_update' => '<span class="badge badge-info bg-opacity-30 text-info">Phone Update</span>',
            
            // KYC Actions
            'kyc_submit' => '<span class="badge badge-info bg-opacity-30 text-info">KYC Submit</span>',
            'kyc_approve' => '<span class="badge badge-success bg-opacity-30 text-success">KYC Approve</span>',
            'kyc_reject' => '<span class="badge badge-danger bg-opacity-30 text-danger">KYC Reject</span>',
            'kyc_resubmit' => '<span class="badge badge-warning bg-opacity-30 text-warning">KYC Resubmit</span>',
            'document_upload' => '<span class="badge badge-info bg-opacity-30 text-info">Document Upload</span>',
            'document_delete' => '<span class="badge badge-warning bg-opacity-30 text-warning">Document Delete</span>',
            
            // Admin Actions
            'settings_update' => '<span class="badge badge-warning bg-opacity-30 text-warning">Settings Update</span>',
            'admin_create' => '<span class="badge badge-primary bg-opacity-30 text-primary">Admin Create</span>',
            'admin_update' => '<span class="badge badge-info bg-opacity-30 text-info">Admin Update</span>',
            'admin_delete' => '<span class="badge badge-danger bg-opacity-30 text-danger">Admin Delete</span>',
            'role_assign' => '<span class="badge badge-primary bg-opacity-30 text-primary">Role Assign</span>',
            'role_revoke' => '<span class="badge badge-warning bg-opacity-30 text-warning">Role Revoke</span>',
            'user_permissions_update' => '<span class="badge badge-info bg-opacity-30 text-info">User Permissions Update</span>',
            
            // Trading & Forex Actions
            'forex_account_create' => '<span class="badge badge-success bg-opacity-30 text-success">Forex Account Create</span>',
            'forex_account_update' => '<span class="badge badge-warning bg-opacity-30 text-warning">Forex Account Update</span>',
            'forex_account_delete' => '<span class="badge badge-danger bg-opacity-30 text-danger">Forex Account Delete</span>',
            'leverage_change' => '<span class="badge badge-warning bg-opacity-30 text-warning">Leverage Change</span>',
            'position_open' => '<span class="badge badge-info bg-opacity-30 text-info">Position Open</span>',
            'position_close' => '<span class="badge badge-secondary bg-opacity-30 text-secondary">Position Close</span>',
            
            // Support Actions
            'ticket_create' => '<span class="badge badge-info bg-opacity-30 text-info">Ticket Create</span>',
            'ticket_reply' => '<span class="badge badge-primary bg-opacity-30 text-primary">Ticket Reply</span>',
            'ticket_close' => '<span class="badge badge-secondary bg-opacity-30 text-secondary">Ticket Close</span>',
            'ticket_reopen' => '<span class="badge badge-warning bg-opacity-30 text-warning">Ticket Reopen</span>',
            
            // Security Actions
            'suspicious_activity' => '<span class="badge badge-danger bg-opacity-30 text-danger">Suspicious Activity</span>',
            'account_locked' => '<span class="badge badge-danger bg-opacity-30 text-danger">Account Locked</span>',
            'ip_blocked' => '<span class="badge badge-danger bg-opacity-30 text-danger">IP Blocked</span>',
            'multiple_login_attempts' => '<span class="badge badge-warning bg-opacity-30 text-warning">Multiple Login Attempts</span>',
            'session_expired' => '<span class="badge badge-secondary bg-opacity-30 text-secondary">Session Expired</span>',
            
            // IB & Referral Actions
            'ib_registration' => '<span class="badge badge-success bg-opacity-30 text-success">IB Registration</span>',
            'ib_commission_paid' => '<span class="badge badge-success bg-opacity-30 text-success">IB Commission Paid</span>',
            'referral_bonus' => '<span class="badge badge-success bg-opacity-30 text-success">Referral Bonus</span>',
            'ib_level_change' => '<span class="badge badge-info bg-opacity-30 text-info">IB Level Change</span>',

            'data_export' => '<span class="badge badge-primary bg-opacity-30 text-primary">Data Export</span>',
            'withdraw_account_created' => '<span class="badge badge-success bg-opacity-30 text-success">Withdraw Account Created</span>',
        ];

        return $badges[$action] ?? '<span class="badge badge-primary bg-opacity-30 text-primary">' . ucfirst(str_replace('_', ' ', $action)) . '</span>';
    }
}
