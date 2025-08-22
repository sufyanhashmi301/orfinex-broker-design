<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PaymentDepositQuestion;
use App\Models\PaymentDepositRequest;
use App\Models\User;
use App\Traits\NotifyTrait;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PaymentDepositController extends Controller
{
    use NotifyTrait;

    public function __construct()
    {
        $this->middleware('permission:payment-deposit-list|payment-deposit-action|payment-deposit-form-manage', 
            ['only' => ['index', 'update', 'pendingList', 'approvedList', 'rejectedList']]);
        $this->middleware('permission:payment-deposit-form-manage', ['only' => ['saveForm']]);
    }

    /**
     * Display IB Forms management
     */
    public function index(Request $request)
    {
        $questions = PaymentDepositQuestion::paginate(10);
        return view('backend.payment-deposit.index', compact('questions'));
    }

    /**
     * Show the form for creating a new payment deposit form
     */
    public function create()
    {
        return view('backend.payment-deposit.create');
    }

    /**
     * Show the form for editing the specified form
     */
    public function edit($id)
    {
        $question = PaymentDepositQuestion::find($id);
        
        // Ensure fields is always an array
        $fields = $question->fields;
        if (!is_array($fields)) {
            $fields = is_string($fields) ? json_decode($fields, true) : [];
        }
        $fields = $fields ?: [];

        return view('backend.payment-deposit.edit', compact('question', 'fields'));
    }

    /**
     * Update the specified form in storage
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:payment_deposit_questions,name,' . $id,
                'status' => 'required',
                'fields' => 'required',
            ]);

            if ($validator->fails()) {
                DB::rollback();
                notify()->error($validator->errors()->first(), 'Error');
                return redirect()->back();
            }

            $data = [
                'name' => $request->name,
                'status' => $request->status,
                'fields' => json_encode($request->fields),
            ];

            $question = PaymentDepositQuestion::find($id);
            $question->update($data);
            
            DB::commit();
            
            notify()->success($question->name . ' ' . __(' Payment Deposit Form Updated'));
            return redirect()->route('admin.payment-deposit-form.index');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment deposit form update failed: ' . $e->getMessage());
            notify()->error(__('Failed to update form. Please try again.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified form from storage
     */
    public function destroy($id)
    {
        try {
            PaymentDepositQuestion::find($id)->delete();
            notify()->success(__('Payment Deposit Form Deleted Successfully'));
        } catch (\Exception $e) {
            Log::error('Payment deposit form delete failed: ' . $e->getMessage());
            notify()->error(__('Failed to delete form. Please try again.'), 'Error');
        }

        return redirect()->route('admin.payment-deposit-form.index');
    }

    /**
     * Save new form
     */
    public function saveForm(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:payment_deposit_questions,name',
                'status' => 'required',
                'fields' => 'required',
            ]);

            if ($validator->fails()) {
                DB::rollback();
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }

            $data = [
                'name' => $request->name,
                'status' => $request->status,
                'fields' => json_encode($request->fields),
            ];

            $question = PaymentDepositQuestion::create($data);
            
            DB::commit();
            
            notify()->success($question->name . ' ' . __(' Payment Deposit Form Created'));
            return redirect()->route('admin.payment-deposit-form.index');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment deposit form creation failed: ' . $e->getMessage());
            notify()->error(__('Failed to create form. Please try again.'), 'Error');
            return redirect()->back();
        }
    }

    /**
     * Display pending payment deposit requests
     */
    public function pendingList(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'date_filter', 'created_at', 'bank_details']);
            
            // Get accessible user IDs
            $accessibleUserIds = getAccessibleUserIds($filters)->pluck('id');

            if ($accessibleUserIds->isEmpty()) {
                return Datatables::of(collect([]))->make(true);
            }

            // Initialize query
            $data = PaymentDepositRequest::with(['user', 'approvedBy'])
                ->where('status', PaymentDepositRequest::STATUS_PENDING)
                ->whereIn('user_id', $accessibleUserIds);

            // Apply bank details filter if provided
            if ($request->filled('bank_details')) {
                $bankDetailsSearch = $request->bank_details;
                $data->where(function($query) use ($bankDetailsSearch) {
                    $query->whereRaw('JSON_SEARCH(bank_details, "all", ?) IS NOT NULL', ["%{$bankDetailsSearch}%"]);
                });
            }

            $data->latest();

            // Process date range filters
            $this->applyDateFilters($data, $request);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    return view('backend.user.include.__user', ['row' => $row->user])->render();
                })
                ->addColumn('request_details', function ($row) {
                    $fieldsCount = is_array($row->fields) ? count($row->fields) : 0;
                    return '
                        <div>
                            <div class="text-slate-600 dark:text-slate-300 text-sm font-medium">Payment Request</div>
                            <div class="text-xs text-slate-500 dark:text-slate-400">' . $fieldsCount . ' fields submitted</div>
                        </div>
                    ';
                })
                ->editColumn('submitted_at', function ($row) {
                    return $row->submitted_at ? $row->submitted_at->format('M d, Y') . '<br><span class="text-xs text-slate-400">' . $row->submitted_at->format('h:i A') . '</span>' : 'N/A';
                })
                ->editColumn('status', function ($row) {
                    return '<span class="badge bg-warning-500 text-white">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('backend.payment-deposit.include.__action', ['request' => $row]);
                })
                ->rawColumns(['user_info', 'request_details', 'submitted_at', 'status', 'action'])
                ->make(true);
        }

        // Calculate stats for the pending page
        $accessibleUserIds = getAccessibleUserIds([])->pluck('id');
        
        $stats = [
            'total_pending' => PaymentDepositRequest::where('status', PaymentDepositRequest::STATUS_PENDING)
                ->whereIn('user_id', $accessibleUserIds)
                ->count(),
            'today' => PaymentDepositRequest::where('status', PaymentDepositRequest::STATUS_PENDING)
                ->whereIn('user_id', $accessibleUserIds)
                ->whereDate('created_at', now()->toDateString())
                ->count(),
            'this_month' => PaymentDepositRequest::where('status', PaymentDepositRequest::STATUS_PENDING)
                ->whereIn('user_id', $accessibleUserIds)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count()
        ];

        return view('backend.payment-deposit.pending', compact('stats'));
    }

    /**
     * Display approved payment deposit requests
     */
    public function approvedList(Request $request)
    {
        if ($request->ajax()) {
            $filters = $request->only(['global_search', 'date_filter', 'created_at', 'bank_details']);
            
            $accessibleUserIds = getAccessibleUserIds($filters)->pluck('id');

            if ($accessibleUserIds->isEmpty()) {
                return Datatables::of(collect([]))->make(true);
            }

            $data = PaymentDepositRequest::with(['user', 'approvedBy'])
                ->where('status', PaymentDepositRequest::STATUS_APPROVED)
                ->whereIn('user_id', $accessibleUserIds);

            // Apply bank details filter if provided
            if ($request->filled('bank_details')) {
                $bankDetailsSearch = $request->bank_details;
                $data->where(function($query) use ($bankDetailsSearch) {
                    $query->whereRaw('JSON_SEARCH(bank_details, "all", ?) IS NOT NULL', ["%{$bankDetailsSearch}%"]);
                });
            }

            $data->latest();

            $this->applyDateFilters($data, $request);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_info', function ($row) {
                    return view('backend.user.include.__user', ['row' => $row->user])->render();
                })
                ->addColumn('bank_details', function ($row) {
                    if ($row->bank_details && is_array($row->bank_details)) {
                        $bankName = $row->bank_details['bank_name'] ?? 'N/A';
                        $accountNumber = $row->bank_details['account_number'] ?? 'N/A';
                        return '
                            <div>
                                <div class="text-slate-600 dark:text-slate-300 text-sm font-medium">' . htmlspecialchars($bankName) . '</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Account: ' . htmlspecialchars($accountNumber) . '</div>
                            </div>
                        ';
                    }
                    return '<span class="text-slate-400 text-sm">No bank details</span>';
                })
                ->editColumn('approved_at', function ($row) {
                    return $row->approved_at ? $row->approved_at->format('M d, Y') . '<br><span class="text-xs text-slate-400">' . $row->approved_at->format('h:i A') . '</span>' : 'N/A';
                })
                ->addColumn('approved_by_name', function ($row) {
                    $approver = $row->approvedBy;
                    if ($approver) {
                        return '
                            <div>
                                <div class="text-slate-600 dark:text-slate-300 text-sm font-medium">' . htmlspecialchars($approver->name) . '</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">Admin</div>
                            </div>
                        ';
                    }
                    return '<span class="text-slate-400 text-sm">System</span>';
                })
                ->editColumn('status', function ($row) {
                    return '<span class="badge bg-success-500 text-white">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('backend.payment-deposit.include.__action', ['request' => $row]);
                })
                ->rawColumns(['user_info', 'bank_details', 'approved_at', 'approved_by_name', 'status', 'action'])
                ->make(true);
        }

        // Calculate stats for the approved page
        $accessibleUserIds = getAccessibleUserIds([])->pluck('id');
        
        $stats = [
            'total_approved' => PaymentDepositRequest::where('status', PaymentDepositRequest::STATUS_APPROVED)
                ->whereIn('user_id', $accessibleUserIds)
                ->count(),
            'this_month' => PaymentDepositRequest::where('status', PaymentDepositRequest::STATUS_APPROVED)
                ->whereIn('user_id', $accessibleUserIds)
                ->whereMonth('approved_at', now()->month)
                ->whereYear('approved_at', now()->year)
                ->count(),
            'this_year' => PaymentDepositRequest::where('status', PaymentDepositRequest::STATUS_APPROVED)
                ->whereIn('user_id', $accessibleUserIds)
                ->whereYear('approved_at', now()->year)
                ->count(),
            'total_users' => \App\Models\User::whereIn('id', $accessibleUserIds)->count()
        ];

        return view('backend.payment-deposit.approved', compact('stats'));
    }

    /**
     * View request details
     */
    public function requestView(PaymentDepositRequest $request)
    {
        return view('backend.payment-deposit.include.__request_detail', [
            'depositRequest' => $request
        ]);
    }

    /**
     * Approve payment deposit request with bank details
     */
    public function approveRequest(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validator = Validator::make($request->all(), [
                'request_id' => 'required|exists:payment_deposit_requests,id',
                'bank_name' => 'required|string|max:255',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50',
                'routing_number' => 'nullable|string|max:50',
                'swift_code' => 'nullable|string|max:20',
                'bank_address' => 'nullable|string|max:500',
                'additional_instructions' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                DB::rollback();
                return response()->json([
                    'error' => $validator->errors()->first()
                ], 422);
            }

            $depositRequest = PaymentDepositRequest::findOrFail($request->request_id);

            if ($depositRequest->status !== PaymentDepositRequest::STATUS_PENDING) {
                DB::rollback();
                return response()->json([
                    'error' => 'Request has already been processed'
                ], 400);
            }

            // Prepare bank details
            $bankDetails = [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'routing_number' => $request->routing_number,
                'swift_code' => $request->swift_code,
                'bank_address' => $request->bank_address,
                'additional_instructions' => $request->additional_instructions,
                'approved_at' => now()->toISOString()
            ];

            // Update request
            $depositRequest->update([
                'status' => PaymentDepositRequest::STATUS_APPROVED,
                'bank_details' => $bankDetails,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            // Send notification to user
            $this->sendApprovalNotifications($depositRequest);

            DB::commit();

            Log::info('Payment deposit request approved', [
                'request_id' => $depositRequest->id,
                'user_id' => $depositRequest->user_id,
                'approved_by' => auth()->id()
            ]);

            return response()->json([
                'success' => 'Payment deposit request approved successfully',
                'reload' => true
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment deposit request approval failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to approve request. Please try again.'
            ], 500);
        }
    }

    /**
     * Reject payment deposit request
     */
    public function rejectRequest(Request $request)
    {
        DB::beginTransaction();
        
        try {
            $validator = Validator::make($request->all(), [
                'request_id' => 'required|exists:payment_deposit_requests,id',
                'rejection_reason' => 'required|string|max:1000'
            ]);

            if ($validator->fails()) {
                DB::rollback();
                return response()->json([
                    'error' => $validator->errors()->first()
                ], 422);
            }

            $depositRequest = PaymentDepositRequest::findOrFail($request->request_id);

            if ($depositRequest->status !== PaymentDepositRequest::STATUS_PENDING) {
                DB::rollback();
                return response()->json([
                    'error' => 'Request has already been processed'
                ], 400);
            }

            $depositRequest->update([
                'status' => PaymentDepositRequest::STATUS_REJECTED,
                'rejection_reason' => $request->rejection_reason,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            // Send notification to user
            $this->sendRejectionNotifications($depositRequest);

            DB::commit();

            return response()->json([
                'success' => 'Payment deposit request rejected successfully',
                'reload' => true
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment deposit request rejection failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to reject request. Please try again.'
            ], 500);
        }
    }

    /**
     * Apply date filters to query
     */
    private function applyDateFilters($query, Request $request)
    {
        $dateRanges = [];

        // Custom date range
        if (!empty($request->created_at)) {
            $dates = explode(' to ', $request->created_at);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $dateRanges[] = [
                    'start' => $start,
                    'end' => $end,
                    'days' => $start->diffInDays($end)
                ];
            }
        }

        // Predefined range
        if ($request->date_filter) {
            $filter = $request->date_filter;
            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $dateRanges[] = [
                    'start' => $dateRange[0],
                    'end' => $dateRange[1],
                    'days' => $dateRange[0]->diffInDays($dateRange[1])
                ];
            }
        }

        // Apply the shortest date range if multiple exist
        if (count($dateRanges) > 0) {
            $shortestRange = collect($dateRanges)->sortBy('days')->first();
            $query->whereBetween('created_at', [
                $shortestRange['start'],
                $shortestRange['end']
            ]);
        }
    }

    /**
     * Send approval notifications
     */
    private function sendApprovalNotifications(PaymentDepositRequest $depositRequest)
    {
        try {
            $user = $depositRequest->user;
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => 'Approved',
                '[[bank_name]]' => $depositRequest->bank_details['bank_name'] ?? '',
            ];

            $this->mailNotify($user->email, 'payment_deposit_approved', $shortcodes);
            $this->pushNotify('payment_deposit_approved', $shortcodes, route('user.payment-deposit.status'), $user->id);
            
        } catch (\Exception $e) {
            Log::warning('Payment deposit approval notifications failed: ' . $e->getMessage());
        }
    }

    /**
     * Send rejection notifications
     */
    private function sendRejectionNotifications(PaymentDepositRequest $depositRequest)
    {
        try {
            $user = $depositRequest->user;
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => 'Rejected',
                '[[reason]]' => $depositRequest->rejection_reason,
            ];

            $this->mailNotify($user->email, 'payment_deposit_rejected', $shortcodes);
            $this->pushNotify('payment_deposit_rejected', $shortcodes, route('user.payment-deposit.status'), $user->id);
            
        } catch (\Exception $e) {
            Log::warning('Payment deposit rejection notifications failed: ' . $e->getMessage());
        }
    }
}
