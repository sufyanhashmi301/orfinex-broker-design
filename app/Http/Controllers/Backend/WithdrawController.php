<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Str;
use Txn;
use Exception;
use DataTables;
use App\Models\Rate;
use App\Models\User;
use App\Enums\TxnType;
use App\Models\Gateway;
use App\Enums\TxnStatus;
use App\Enums\ForexAccountStatus;
use Brick\Math\BigDecimal;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Enums\TxnTargetType;
use Illuminate\Http\Request;
use App\Traits\ForexApiTrait;
use App\Models\Comment;
use App\Models\WithdrawMethod;
use App\Models\WithdrawAccount;
use App\Models\ForexAccount;
use App\Models\SetTune;
use App\Services\WalletService;
use App\Exports\WithdrawsExport;
use App\Services\ForexApiService;
use Illuminate\Http\JsonResponse;
use App\Models\WithdrawalSchedule;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use App\Exports\PendingWithdrawsExport;
use App\Exports\PendingWithdrawAccountsExport;
use App\Exports\ApprovedWithdrawAccountsExport;
use App\Exports\RejectedWithdrawAccountsExport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Foundation\Application;

class WithdrawController extends Controller
{
    use ImageUpload, NotifyTrait, ForexApiTrait;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->middleware('permission:automatic-withdraw-method|manual-withdraw-method', ['only' => ['methods', 'methodCreate', 'methodStore', 'methodEdit', 'methodUpdate']]);
        $this->middleware('permission:withdraw-method-create', ['only' => ['methodCreate', 'methodStore']]);
        $this->middleware('permission:withdraw-list' , ['only' => ['pending', 'history','withdrawAction','actionNow']]);
        $this->middleware('permission:withdraw-add' , ['only' => ['addWithdraw']]);
        $this->middleware('permission:withdraw-schedule', ['only' => ['schedule', 'scheduleUpdate']]);
        $this->middleware('permission:withdraw-export', ['only' => ['export', 'pendingExport']]);
        $this->middleware('permission:withdraw-notification' , ['only' => ['notificationTune']]);
        $this->middleware('permission:withdraw-account-export', ['only' => ['rejectedAccountsExport', 'approvedAccountsExport', 'pendingAccountsExport']]);
        $this->middleware('permission:withdraw-account-action', ['only' => ['accountActionModal', 'accountAction']]);
        $this->middleware('permission:withdraw-account-pending' , ['only' => ['pendingAccounts']]);
        $this->middleware('permission:withdraw-account-approve' , ['only' => ['approvedAccounts']]);
        $this->middleware('permission:withdraw-account-rejected' , ['only' => ['rejectedAccounts']]);

        $this->forexApiService = $forexApiService;

    }

    /**
     * @return Application|Factory|View
     */
    public function methods($type)
    {
        $button = [
            'name' => __('ADD NEW'),
            'icon' => 'plus',
            'route' => route('admin.withdraw.method.create', $type),
        ];
        
        $loggedInUser = auth('admin')->user();
        $staffBranchIds = $loggedInUser->branches()->pluck('branches.id')->toArray();
        
        $withdrawMethodsQuery = WithdrawMethod::whereType($type)->with('branches');
        
        // Apply branch filtering for non-Super-Admin staff
        if (!$loggedInUser->hasRole('Super-Admin') && !empty($staffBranchIds)) {
            $withdrawMethodsQuery->where(function($query) use ($staffBranchIds) {
                $query->whereHas('branches', function($branchQuery) use ($staffBranchIds) {
                    $branchQuery->whereIn('branch_id', $staffBranchIds);
                })->orWhereDoesntHave('branches');
            });
        }
        
        $withdrawMethods = $withdrawMethodsQuery->get();

        return view('backend.withdraw.method', compact('withdrawMethods', 'button', 'type'));
    }

    /**
     * @return Application|Factory|View
     */
    public function methodCreate($type)
    {
        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => route('admin.withdraw.method.list', $type),
        ];
        $gateways = Gateway::where('status', true)->whereNot('is_withdraw', '=', '0')->get();
        $rates_with_countries = Rate::with('country')->get();
        $branches = \App\Models\Branch::where('status', 1)->get();

        return view('backend.withdraw.method_create', compact('button', 'type', 'gateways', 'rates_with_countries', 'branches'));
    }

    /**
     * @return RedirectResponse
     */
    public function methodStore(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'icon' => 'required_if:type,==,manual',
            'gateway_id' => 'required_if:type,==,auto',
            'name' => 'required',
            'currency' => 'required',
            'required_time' => 'required_if:type,==,manual',
            'required_time_format' => 'required_if:type,==,manual',
            'charge' => 'required|numeric|gte:0',
            'charge_type' => 'required',
            'rate' => 'required|numeric|gte:0',
            'min_withdraw' => 'required|numeric|gte:0',
            'max_withdraw' => 'required|numeric|gte:0',
            'status' => 'required',
            'fields' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $fields = null;
        if ($input['type'] == 'auto') {

            $withdrawGateways = Gateway::find($input['gateway_id']);
            $withdrawFields = explode(',', $withdrawGateways->is_withdraw);

            $fields = array_map(function ($field) {
                return [
                    'name' => $field,
                    'type' => 'text',
                    'validation' => 'required',
                ];
            }, $withdrawFields);

        }


        $data = [
            'icon' => isset($input['icon']) ? self::imageUploadTrait($input['icon']) : '',
            'gateway_id' => $input['gateway_id'] ?? null,
            'type' => $input['type'],
            'name' => $input['name'],
            'required_time' => $input['required_time'] ?? 0,
            'required_time_format' => $input['required_time_format'] ?? 'hour',
            'currency' => $input['currency'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'min_withdraw' => $input['min_withdraw'],
            'max_withdraw' => $input['max_withdraw'],
            'status' => $input['status'],
            'is_global' => isset($input['is_global']) ? (bool) $input['is_global'] : false,
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'fields' => json_encode($fields ?? $input['fields']),
        ];

        $withdrawMethod = WithdrawMethod::create($data);
        
        // Sync branches if provided
        if (!empty($input['branches'])) {
            $withdrawMethod->branches()->sync($input['branches']);
        }
        
        notify()->success($withdrawMethod->name . ' ' . __('Withdraw Method Created'));

        return redirect()->route('admin.withdraw.method.list', $input['type']);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function methodEdit($type)
    {

        $button = [
            'name' => __('Back'),
            'icon' => 'corner-down-left',
            'route' => route('admin.withdraw.method.list', $type),
        ];

        $withdrawMethod = WithdrawMethod::with('branches')->find(\request('id'));
        $supported_currencies = Gateway::find($withdrawMethod->gateway_id)->supported_currencies ?? [];
        $branches = \App\Models\Branch::where('status', 1)->get();
        $attachedBranches = $withdrawMethod->branches->pluck('id')->toArray();

        return view('backend.withdraw.method_edit', compact('button', 'withdrawMethod', 'type', 'supported_currencies', 'branches', 'attachedBranches'));
    }

    /**
     * @return RedirectResponse
     */
    public function methodUpdate(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'currency' => 'required',
            'required_time' => 'required_if:type,==,manual',
            'required_time_format' => 'required_if:type,==,manual',
            'charge' => 'required|numeric|gte:0',
            'charge_type' => 'required',
            'rate' => 'required|numeric|gte:0',
            'min_withdraw' => 'required|numeric|gte:0',
            'max_withdraw' => 'required|numeric|gte:0',
            'status' => 'required',
            'fields' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $withdrawMethod = WithdrawMethod::find($id);

        $data = [
            'name' => $input['name'],
            'required_time' => $input['required_time'] ?? $withdrawMethod->required_time,
            'required_time_format' => $input['required_time_format'] ?? $withdrawMethod->required_time_format,
            'currency' => $input['currency'] ?? $withdrawMethod->currency,
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'min_withdraw' => $input['min_withdraw'],
            'max_withdraw' => $input['max_withdraw'],
            'status' => $input['status'],
            'is_global' => isset($input['is_global']) ? (bool) $input['is_global'] : false,
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'fields' => isset($input['fields']) ? json_encode($input['fields']) : $withdrawMethod->fields,
        ];

        if ($request->hasFile('icon')) {
            $icon = self::imageUploadTrait($input['icon'], $withdrawMethod->icon);
            $data = array_merge($data, ['icon' => $icon]);
        }

        $withdrawMethod->update($data);
        
        // Sync branches if provided
        if (isset($input['branches'])) {
            $withdrawMethod->branches()->sync($input['branches']);
        } else {
            $withdrawMethod->branches()->detach();
        }
        
        notify()->success($withdrawMethod->name . ' ' . __('Withdraw Method Updated'));

        return redirect()->route('admin.withdraw.method.list', $withdrawMethod->type);
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function pending(Request $request)
    {
        $loggedInUser = auth()->user();
        $filters = $request->only(['email', 'created_at']);

        if ($request->ajax()) {
            $accessibleUserIds = getAccessibleUserIds()->pluck('id');

            // ✅ Base query for pending withdrawals
            $data = Transaction::where('type', TxnType::Withdraw)
                ->where('status', 'pending')
                ->whereIn('user_id', $accessibleUserIds);

            // Apply additional filters if any
            $data = $data->applyFilters($filters);

            // Select sortable projections for username and signed amount
            $data = $data->select('transactions.*')
                ->selectSub(
                    DB::table('users')
                        ->whereColumn('users.id', 'transactions.user_id')
                        ->selectRaw("MIN(CONCAT(users.first_name, ' ', users.last_name))"),
                    'username_sort'
                )
                ->selectRaw("(
                    CASE
                        WHEN transactions.type IN ('subtract','investment','withdraw','send_money','send_money_internal','bonus_refund','bonus_subtract')
                            THEN -1 * CAST(COALESCE(transactions.amount, 0) AS DECIMAL(18,8))
                        ELSE CAST(COALESCE(transactions.amount, 0) AS DECIMAL(18,8))
                    END
                ) as signed_amount");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at . '</span>';
                })
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge . ' ' . setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.withdraw.include.__action')
                // Server-side ordering mappings
                ->orderColumn('created_at', 'transactions.created_at $1')
                ->orderColumn('username', 'username_sort $1')
                ->orderColumn('description', 'transactions.description $1')
                ->orderColumn('tnx', 'transactions.tnx $1')
                ->orderColumn('target_id', 'transactions.target_id $1')
                ->orderColumn('amount', 'signed_amount $1')
                ->orderColumn('charge', 'transactions.charge $1')
                ->orderColumn('method', 'transactions.method $1')
                ->orderColumn('status', 'transactions.status $1')
                ->rawColumns(['created_at', 'action', 'status', 'type', 'amount', 'username'])
                ->make(true);
        }

        return view('backend.withdraw.pending');
    }

    /**
     * @return Application|Factory|View|JsonResponse
     *
     * @throws Exception
     */
    public function history(Request $request)
    {
        $loggedInUser = auth()->user();
        $filters = $request->only(['email', 'status', 'created_at']);

        if ($request->ajax()) {
            $userIds = getAccessibleUserIds()->pluck('id');

        // Build base query
        $data = Transaction::query()
            ->where(function ($query) {
                $query->where('type', TxnType::Withdraw)
                      ->orWhere('type', TxnType::WithdrawAuto);
            })
            ->when($userIds !== 'all', function ($query) use ($userIds) {
                $query->whereIn('user_id', $userIds);
            });

            // Filter by selected user (hashed id from Add Withdraw)
            if ($request->filled('user_id')) {
                $selectedUserId = get_hash($request->input('user_id'));
                if (!empty($selectedUserId)) {
                    $data->where('user_id', $selectedUserId);
                }
            }


            // Apply additional filters if any
            $data = $data->applyFilters($filters);

            // Select sortable projections for username, action_by and signed amount
            $data = $data->select('transactions.*')
                ->selectSub(
                    DB::table('users')
                        ->whereColumn('users.id', 'transactions.user_id')
                        ->selectRaw("MIN(CONCAT(users.first_name, ' ', users.last_name))"),
                    'username_sort'
                )
                ->selectSub(
                    DB::table('admins')
                        ->whereColumn('admins.id', 'transactions.action_by')
                        ->selectRaw('MIN(admins.name)'),
                    'action_by_sort'
                )
                ->selectRaw("(
                    CASE
                        WHEN transactions.type IN ('subtract','investment','withdraw','send_money','send_money_internal','bonus_refund','bonus_subtract')
                            THEN -1 * CAST(COALESCE(transactions.amount, 0) AS DECIMAL(18,8))
                        ELSE CAST(COALESCE(transactions.amount, 0) AS DECIMAL(18,8))
                    END
                ) as signed_amount");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at . '</span>';
                })
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge . ' ' . setting('site_currency', 'global');
                })
                ->addColumn('action_by', function ($row) {
                    return '<span class="text-nowrap">' . optional($row->staff)->name ?? '-' . '</span>';
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.transaction.include.__action')
                // Server-side ordering mappings
                ->orderColumn('created_at', 'transactions.created_at $1')
                ->orderColumn('username', 'username_sort $1')
                ->orderColumn('description', 'transactions.description $1')
                ->orderColumn('tnx', 'transactions.tnx $1')
                ->orderColumn('target_id', 'transactions.target_id $1')
                ->orderColumn('amount', 'signed_amount $1')
                ->orderColumn('charge', 'transactions.charge $1')
                ->orderColumn('method', 'transactions.method $1')
                ->orderColumn('action_by', 'action_by_sort $1')
                ->orderColumn('status', 'transactions.status $1')
                ->rawColumns(['created_at', 'action_by','status', 'type', 'amount', 'username', 'action'])
                ->make(true);
        }

        // For non-AJAX requests, show the full view
        return view('backend.withdraw.history');
    }

    /**
     * @return string
     */
    public function withdrawAction($id)
    {
        $data = Transaction::find($id);
        // For transaction withdraw approval modal → show only active 'withdraw_amount' comments
        $comments = Comment::where('type', 'withdraw_amount')
            ->where('status', true)
            ->orderBy('title')
            ->get(['id','title','description']);

        return view('backend.withdraw.include.__withdraw_action', compact('data', 'id', 'comments'))->render();
    }

    /**
     * @return RedirectResponse
     */
    public function actionNow(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $approvalCause = str_replace(['{', '}'], ['<', '>'], $input['message'] ?? 'none');

        // dd($input['message'], $approvalCause);

        DB::beginTransaction();
        try {
            $transaction = Transaction::where('id', $id)->lockForUpdate()->first();
            if (!$transaction) {
                notify()->error('Transaction not found');
                DB::rollBack();
                return redirect()->back();
            }

            if ($transaction->status != TxnStatus::Pending) {
                notify()->warning('This transaction has already been processed.');
                DB::rollBack();
                return redirect()->back();
            }

            $user = User::find($transaction->user_id);
            $shortcodes = [
                '[[full_name]]' => $transaction->user->full_name,
                '[[txn]]' => $transaction->tnx,
                '[[method_name]]' => $transaction->method,
                '[[withdraw_amount]]' => $transaction->amount . setting('site_currency', 'global'),
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[message]]' =>    ($approvalCause),
                '[[status]]' => isset($input['approve']) ? 'approved' : 'Rejected',
            ];

            if (isset($input['approve'])) {
                $txn = Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
                if ($txn) {
                    $this->mailNotify($user->email, 'withdraw_request_user_approve', $shortcodes);
                    notify()->success('Approve successfully');
                }else{
                    notify()->error('Failed to update transaction. Please try again.');
                    return redirect()->back();

                }
            } elseif (isset($input['reject'])) {
                $manualFieldData = json_decode($transaction->manual_field_data, true);
                $deductionStatus = isset($manualFieldData['Deduction Status']['value']) ? $manualFieldData['Deduction Status']['value'] : 'Not Deducted';

                if ($deductionStatus === 'Deducted') {
                    $newTransaction = $transaction->replicate();
                    $newTransaction->type = TxnType::Refund;
                    $newTransaction->status = TxnStatus::None;
                    $newTransaction['method'] = 'system';
                    $newTransaction->tnx = 'TRX' . strtoupper(Str::random(10));
                    $newTransaction->action_by = auth()->user()->id;
                    $newTransaction->save();
                    $newTransaction->refresh();

                    if (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::ForexWithdraw->value) {
                        $this->reverseForexWithdrawal($newTransaction);
                    } elseif (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::Wallet->value) {
                        $this->reverseWalletWithdrawal($newTransaction);
                    }

                    $txn = Txn::update($newTransaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
                    if ($txn) {
                        $this->mailNotify($user->email, 'withdraw_request_user_reject', $shortcodes);
                    }else{
                        notify()->error('Failed to update transaction. Please try again.');
                        return redirect()->back();

                    }
                }

                $updateResult =  Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);
                if (!$updateResult) {
                    notify()->error('Failed to update transaction. Please try again.');
                    return redirect()->back();
                }
                notify()->success('Reject successfully');
            }
            $transaction->action_by = auth()->user()->id;
            $transaction->save();

            $this->pushNotify('withdraw_request_user', $shortcodes, route('user.history.transactions'), $user->id, 'withdraw');
            $this->smsNotify('withdraw_request_user', $shortcodes, $user->phone);

            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdraw action failed: ' . $e->getMessage());
            notify()->error('An error occurred while processing the withdrawal. Please try again.');
            return redirect()->back();
        }
    }

    private function reverseForexWithdrawal($transaction)
    {
        // Reversal logic for Forex account
        $comment = "wd/reject/" . substr($transaction->tnx, -7);
        $data = [
            'login' => $transaction->target_id,
            'Amount' => apply_cent_account_adjustment($transaction->target_id, $transaction->final_amount),
            'type' => 1, // Deposit back to account
            'TransactionComments' => $comment
        ];

        $withdrawResponse = $this->forexApiService->balanceOperation($data);

        if (!$withdrawResponse['success']) {
            notify()->error(__('Failed to reverse Forex withdrawal. Please check the Forex API.'), 'Error');
        }
    }
    private function reverseWalletWithdrawal($transaction)
    {
        // Reverse deduction for Wallet
        $userAccount = get_user_account_by_wallet_id($transaction->target_id);
//        dd($userAccount);
        $walletService = new WalletService();
        $ledgerBalance = $walletService->getLedgerBalance($userAccount->id);

        try {
            // Add back the amount to the ledger (credit)
            $walletService->createCreditLedgerEntry($transaction, $ledgerBalance);

            // Add the amount back to the user's wallet
            $userAccount->amount = BigDecimal::of($userAccount->amount)->plus(BigDecimal::of($transaction->final_amount));
            $userAccount->save();
        } catch (\Exception $e) {
            notify()->error(__('Failed to reverse Wallet withdrawal: ') . $e->getMessage(), 'Error');
        }
    }


    public
        function schedule()
        {
            $schedules = WithdrawalSchedule::all();

            return view('backend.withdraw.schedule', compact('schedules'));
        }

        public
        function scheduleUpdate(Request $request)
        {

            $updateSchedules = $request->except('_token');
            foreach ($updateSchedules as $name => $status) {
                WithdrawalSchedule::where('name', $name)->update([
                    'status' => $status,
                ]);
            }

            notify()->success('Withdrawal Schedule Update successfully');

            return redirect()->back();
        }
        public function export(Request $request)
        {

            return Excel::download(new WithdrawsExport($request), 'withdraws-history.xlsx');
        }
            public function pendingExport(Request $request)
    {

        return Excel::download(new PendingWithdrawsExport($request), 'pending-withdraws.xlsx');
    }

    /**
     * Display pending withdraw accounts
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function pendingAccounts(Request $request)
    {
        $filters = $request->only(['username', 'email', 'created_at']);

        if ($request->ajax()) {
            // Get accessible user IDs using the helper (includes branch filtering)
            $accessibleUserIds = getAccessibleUserIds()->pluck('id')->toArray();

            $data = WithdrawAccount::where('status', 'pending')
                ->with(['user', 'method']);

            // Apply user filtering based on accessible users
            if (!empty($accessibleUserIds)) {
                $data->whereIn('user_id', $accessibleUserIds);
            } elseif (!auth()->user()->hasRole('Super-Admin')) {
                // If no accessible users and not Super-Admin, show no results
                $data->where('user_id', -1);
            }

            // Apply filters
            if (!empty($filters['username'])) {
                $data = $data->whereHas('user', function($query) use ($filters) {
                    $query->where('username', 'like', '%' . $filters['username'] . '%')
                          ->orWhere('first_name', 'like', '%' . $filters['username'] . '%')
                          ->orWhere('last_name', 'like', '%' . $filters['username'] . '%')
                          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$filters['username']}%"]);
                });
            }

            if (!empty($filters['email'])) {
                $data = $data->whereHas('user', function($query) use ($filters) {
                    $query->where('email', 'like', '%' . $filters['email'] . '%');
                });
            }

            if (!empty($filters['created_at'])) {
                // Handle date range format "start_date to end_date"
                if (strpos($filters['created_at'], ' to ') !== false) {
                    $dates = explode(' to ', $filters['created_at']);
                    if (count($dates) == 2) {
                        $startDate = \Carbon\Carbon::parse($dates[0])->startOfDay();
                        $endDate = \Carbon\Carbon::parse($dates[1])->endOfDay();
                        $data = $data->whereBetween('created_at', [$startDate, $endDate]);
                    }
                } else {
                    // Single date
                    $data = $data->whereDate('created_at', $filters['created_at']);
                }
            }

            // Select sortable projections
            $data = $data->select('withdraw_accounts.*')
                ->selectSub(
                    DB::table('users')
                        ->whereColumn('users.id', 'withdraw_accounts.user_id')
                        ->selectRaw("MIN(CONCAT(users.first_name, ' ', users.last_name))"),
                    'username_sort'
                );

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at->format('Y-m-d H:i:s') . '</span>';
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('method_name', function ($row) {
                    return '<div class="flex items-center">
                        <div class="flex-none">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex flex-col items-center justify-center text-sm font-medium text-slate-600 dark:text-slate-400">
                                <iconify-icon icon="lucide:backpack"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-1 text-start ltr:ml-2 rtl:mr-2">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-white">
                                ' . $row->method_name . '
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                ' . ($row->method->currency ?? 'N/A') . '
                            </p>
                        </div>
                    </div>';
                })
                ->editColumn('status', 'backend.withdraw.include.__account_status')
                // Server-side ordering mappings
                ->orderColumn('created_at', 'withdraw_accounts.created_at $1')
                ->orderColumn('username', 'username_sort $1')
                ->orderColumn('method_name', 'withdraw_accounts.method_name $1')
                ->orderColumn('status', 'withdraw_accounts.status $1')
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="action-btn text-primary account-action-btn" data-id="' . the_hash($row->id) . '" data-account-name="' . $row->method_name . '">
                        <iconify-icon icon="lucide:edit"></iconify-icon>
                    </button>';
                })
                ->rawColumns(['created_at', 'username', 'method_name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.withdraw.pending_accounts');
    }

    /**
     * Export pending withdraw accounts
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function pendingAccountsExport(Request $request)
    {
        return Excel::download(new PendingWithdrawAccountsExport($request), 'pending-withdraw-accounts.xlsx');
    }

    /**
     * Display approved withdraw accounts
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function approvedAccounts(Request $request)
    {
        $filters = $request->only(['username', 'email', 'created_at']);

        if ($request->ajax()) {
            // Get accessible user IDs using the helper (includes branch filtering)
            $accessibleUserIds = getAccessibleUserIds()->pluck('id')->toArray();

            $data = WithdrawAccount::where('status', 'approved')
                ->with(['user', 'method']);

            // Apply user filtering based on accessible users
            if (!empty($accessibleUserIds)) {
                $data->whereIn('user_id', $accessibleUserIds);
            } elseif (!auth()->user()->hasRole('Super-Admin')) {
                // If no accessible users and not Super-Admin, show no results
                $data->where('user_id', -1);
            }

            // Apply filters
            if (!empty($filters['username'])) {
                $data = $data->whereHas('user', function($query) use ($filters) {
                    $query->where('username', 'like', '%' . $filters['username'] . '%')
                          ->orWhere('first_name', 'like', '%' . $filters['username'] . '%')
                          ->orWhere('last_name', 'like', '%' . $filters['username'] . '%')
                          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$filters['username']}%"]);
                });
            }

            if (!empty($filters['email'])) {
                $data = $data->whereHas('user', function($query) use ($filters) {
                    $query->where('email', 'like', '%' . $filters['email'] . '%');
                });
            }

            if (!empty($filters['created_at'])) {
                // Handle date range format "start_date to end_date"
                if (strpos($filters['created_at'], ' to ') !== false) {
                    $dates = explode(' to ', $filters['created_at']);
                    if (count($dates) == 2) {
                        $startDate = \Carbon\Carbon::parse($dates[0])->startOfDay();
                        $endDate = \Carbon\Carbon::parse($dates[1])->endOfDay();
                        $data = $data->whereBetween('created_at', [$startDate, $endDate]);
                    }
                } else {
                    // Single date
                    $data = $data->whereDate('created_at', $filters['created_at']);
                }
            }

            // Select sortable projections
            $data = $data->select('withdraw_accounts.*')
                ->selectSub(
                    DB::table('users')
                        ->whereColumn('users.id', 'withdraw_accounts.user_id')
                        ->selectRaw("MIN(CONCAT(users.first_name, ' ', users.last_name))"),
                    'username_sort'
                );

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at->format('Y-m-d H:i:s') . '</span>';
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('method_name', function ($row) {
                    return '<div class="flex items-center">
                        <div class="flex-none">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex flex-col items-center justify-center text-sm font-medium text-slate-600 dark:text-slate-400">
                                <iconify-icon icon="lucide:backpack"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-1 text-start ltr:ml-2 rtl:mr-2">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-white">
                                ' . $row->method_name . '
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                ' . ($row->method->currency ?? 'N/A') . '
                            </p>
                        </div>
                    </div>';
                })
                ->editColumn('status', 'backend.withdraw.include.__account_status')
                // Server-side ordering mappings
                ->orderColumn('created_at', 'withdraw_accounts.created_at $1')
                ->orderColumn('username', 'username_sort $1')
                ->orderColumn('method_name', 'withdraw_accounts.method_name $1')
                ->orderColumn('status', 'withdraw_accounts.status $1')
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="action-btn text-primary account-action-btn" data-id="' . the_hash($row->id) . '" data-account-name="' . $row->method_name . '">
                        <iconify-icon icon="lucide:edit"></iconify-icon>
                    </button>';
                })
                ->rawColumns(['created_at', 'username', 'method_name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.withdraw.approved_accounts');
    }

    /**
     * Export approved withdraw accounts
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function approvedAccountsExport(Request $request)
    {
        return Excel::download(new ApprovedWithdrawAccountsExport($request), 'approved-withdraw-accounts.xlsx');
    }

    /**
     * Display rejected withdraw accounts
     *
     * @param Request $request
     * @return Application|Factory|View|JsonResponse
     */
    public function rejectedAccounts(Request $request)
    {
        $filters = $request->only(['username', 'email', 'created_at']);

        if ($request->ajax()) {
            // Get accessible user IDs using the helper (includes branch filtering)
            $accessibleUserIds = getAccessibleUserIds()->pluck('id')->toArray();

            $data = WithdrawAccount::where('status', 'rejected')
                ->with(['user', 'method']);

            // Apply user filtering based on accessible users
            if (!empty($accessibleUserIds)) {
                $data->whereIn('user_id', $accessibleUserIds);
            } elseif (!auth()->user()->hasRole('Super-Admin')) {
                // If no accessible users and not Super-Admin, show no results
                $data->where('user_id', -1);
            }

            // Apply filters
            if (!empty($filters['username'])) {
                $data = $data->whereHas('user', function($query) use ($filters) {
                    $query->where('username', 'like', '%' . $filters['username'] . '%')
                          ->orWhere('first_name', 'like', '%' . $filters['username'] . '%')
                          ->orWhere('last_name', 'like', '%' . $filters['username'] . '%')
                          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$filters['username']}%"]);
                });
            }

            if (!empty($filters['email'])) {
                $data = $data->whereHas('user', function($query) use ($filters) {
                    $query->where('email', 'like', '%' . $filters['email'] . '%');
                });
            }

            if (!empty($filters['created_at'])) {
                // Handle date range format "start_date to end_date"
                if (strpos($filters['created_at'], ' to ') !== false) {
                    $dates = explode(' to ', $filters['created_at']);
                    if (count($dates) == 2) {
                        $startDate = \Carbon\Carbon::parse($dates[0])->startOfDay();
                        $endDate = \Carbon\Carbon::parse($dates[1])->endOfDay();
                        $data = $data->whereBetween('created_at', [$startDate, $endDate]);
                    }
                } else {
                    // Single date
                    $data = $data->whereDate('created_at', $filters['created_at']);
                }
            }

            // Select sortable projections
            $data = $data->select('withdraw_accounts.*')
                ->selectSub(
                    DB::table('users')
                        ->whereColumn('users.id', 'withdraw_accounts.user_id')
                        ->selectRaw("MIN(CONCAT(users.first_name, ' ', users.last_name))"),
                    'username_sort'
                );

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at->format('Y-m-d H:i:s') . '</span>';
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('method_name', function ($row) {
                    return '<div class="flex items-center">
                        <div class="flex-none">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 flex flex-col items-center justify-center text-sm font-medium text-slate-600 dark:text-slate-400">
                                <iconify-icon icon="lucide:backpack"></iconify-icon>
                            </div>
                        </div>
                        <div class="flex-1 text-start ltr:ml-2 rtl:mr-2">
                            <h4 class="text-sm font-medium text-slate-600 dark:text-white">
                                ' . $row->method_name . '
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                ' . ($row->method->currency ?? 'N/A') . '
                            </p>
                        </div>
                    </div>';
                })
                ->editColumn('status', 'backend.withdraw.include.__account_status')
                // Server-side ordering mappings
                ->orderColumn('created_at', 'withdraw_accounts.created_at $1')
                ->orderColumn('username', 'username_sort $1')
                ->orderColumn('method_name', 'withdraw_accounts.method_name $1')
                ->orderColumn('status', 'withdraw_accounts.status $1')
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="action-btn text-primary account-action-btn" data-id="' . the_hash($row->id) . '" data-account-name="' . $row->method_name . '">
                        <iconify-icon icon="lucide:edit"></iconify-icon>
                    </button>';
                })
                ->rawColumns(['created_at', 'username', 'method_name', 'status', 'action'])
                ->make(true);
        }

        return view('backend.withdraw.rejected_accounts');
    }

    /**
     * Export rejected withdraw accounts
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function rejectedAccountsExport(Request $request)
    {
        return Excel::download(new RejectedWithdrawAccountsExport($request), 'rejected-withdraw-accounts.xlsx');
    }

    /**
     * Show account action modal
     *
     * @param $id
     * @return string
     */
    public function accountActionModal($id)
    {
        $data = WithdrawAccount::where('id', get_hash($id))
            ->with(['user', 'method'])
            ->first();

        if (!$data) {
            return '<div class="p-6 text-center text-red-500">' . __('Account not found.') . '</div>';
        }

        // Debug: Check if user relationship is loaded
        if (!$data->user) {
            \Log::error('User relationship not loaded for account ID: ' . $id);
            return '<div class="p-6 text-center text-red-500">' . __('User data not found.') . '</div>';
        }

        $comments = Comment::where('type', 'withdraw_account')->where('status', true)->orderBy('title')->get(['id','title','description']);

        return view('backend.withdraw.include.__account_action', compact('data', 'id', 'comments'))->render();
    }

    /**
     * Handle account approval/rejection
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function accountAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required',
            'action' => 'required|in:approve,reject',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $accountId = get_hash($request->account_id);
            $account = WithdrawAccount::where('id', $accountId)
                ->with(['user', 'method']) // Eager load method for currency
                ->first();

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => __('Withdraw account not found.')
                ]);
            }

            $action = $request->action;
            $description = $request->description ?? '';
            
         
            if ($action === 'approve') {
                $previousStatus = $account->status;
                $account->status = WithdrawAccount::STATUS_APPROVED;
                $account->description = $description;
                $account->save();
                
              
                // Send approval notification
                $shortcodes = [
                    '[[full_name]]' => $account->user->full_name,
                    '[[method_name]]' => $account->method_name,
                    '[[currency]]' => $account->method->currency ?? 'N/A',
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ];

                // Debug: Check if email template exists
                $template = \App\Models\EmailTemplate::where('status', true)->where('code', 'withdraw_account_approval')->first();
                \Log::info('Email template check', [
                    'template_exists' => $template ? 'Yes' : 'No',
                    'template_code' => 'withdraw_account_approval',
                    'user_email' => $account->user->email,
                    'shortcodes' => $shortcodes
                ]);

                if ($template) {
                    try {
                        $this->mailNotify($account->user->email, 'withdraw_account_approval', $shortcodes);
                        \Log::info('Withdraw account approval email sent successfully');
                    } catch (\Exception $e) {
                        \Log::error('Failed to send withdraw account approval email: ' . $e->getMessage());
                    }
                } else {
                    \Log::error('Email template not found or not active: withdraw_account_approval');
                }

                try {
                    $this->pushNotify('withdraw_account_approval', $shortcodes, route('user.withdraw.account.index'), $account->user->id, 'withdraw');
                    \Log::info('Withdraw account approval push notification sent successfully');
                } catch (\Exception $e) {
                    \Log::error('Failed to send withdraw account approval push notification: ' . $e->getMessage());
                }

                $message = $previousStatus === WithdrawAccount::STATUS_PENDING 
                    ? __('Withdraw account approved successfully.')
                    : __('Withdraw account status changed to approved successfully.');
                
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                $previousStatus = $account->status;
                $account->status = WithdrawAccount::STATUS_REJECTED;
                $account->description = $description;
                $account->save();
                
              

                // Send rejection notification
                $shortcodes = [
                    '[[full_name]]' => $account->user->full_name,
                    '[[method_name]]' => $account->method_name,
                    '[[currency]]' => $account->method->currency ?? 'N/A',
                    '[[rejection_reason]]' => $description ?: __('Account rejected by admin'),
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ];

                // Debug: Check if email template exists
                $template = \App\Models\EmailTemplate::where('status', true)->where('code', 'withdraw_account_rejection')->first();
                \Log::info('Email template check', [
                    'template_exists' => $template ? 'Yes' : 'No',
                    'template_code' => 'withdraw_account_rejection',
                    'user_email' => $account->user->email,
                    'shortcodes' => $shortcodes
                ]);

                if ($template) {
                    try {
                        $this->mailNotify($account->user->email, 'withdraw_account_rejection', $shortcodes);
                        \Log::info('Withdraw account rejection email sent successfully');
                    } catch (\Exception $e) {
                        \Log::error('Failed to send withdraw account rejection email: ' . $e->getMessage());
                    }
                } else {
                    \Log::error('Email template not found or not active: withdraw_account_rejection');
                }

                try {
                    $this->pushNotify('withdraw_account_rejection', $shortcodes, route('user.withdraw.account.index'), $account->user->id, 'withdraw');
                    \Log::info('Withdraw account rejection push notification sent successfully');
                } catch (\Exception $e) {
                    \Log::error('Failed to send withdraw account rejection push notification: ' . $e->getMessage());
                }

                $message = $previousStatus === WithdrawAccount::STATUS_PENDING 
                    ? __('Withdraw account rejected successfully.')
                    : __('Withdraw account status changed to rejected successfully.');
                
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Withdraw account action failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while processing the request.')
            ]);
        }
    }

    public function destroy($id)
    {
//        dd($id);
        try {
            // Find the method by its ID and delete it
            $method = WithdrawMethod::findOrFail($id);
            if(Transaction::where('method',$method->name)->exists() || WithdrawAccount::where('withdraw_method_id',$id)->exists()){
                notify()->error(__('This method is associated with existing transactions or User withdraw accounts, and therefore cannot be deleted: :method', ['method' => $method->name]), 'Error');
                return redirect()->back();
            }
            $method->delete();
            notify()->success('Successfully deleted method');

            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Something went wrong, Please check error log', 'Error Log');
            return redirect()->back();
        }
    }

    public function addWithdraw()
{
    $loggedInUser = auth()->user();

    // ✅ Use the helper to get accessible users
    $userIds = getAccessibleUserIds()->pluck('id');

    // ✅ Fetch only active users from accessible IDs
    $users = User::whereIn('id', $userIds)
        ->where('status', 1)
        ->get();

    // ✅ Filter withdraw methods by country or "All"
    $withdrawMethods = WithdrawMethod::where('status', true)
        ->where(function ($query) use ($loggedInUser) {
            $query->whereJsonContains('country', $loggedInUser->country)
                ->orWhereJsonContains('country', 'All');
        })->get();

    return view('backend.withdraw.add_withdraw', compact('users', 'withdrawMethods'));
}


    public function withdrawAccount($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);

        if ($withdrawMethod) {
            return view('backend.withdraw.include.__account', compact('withdrawMethod'))->render();
        }

        return '';
    }

    public function accountStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));
            return redirect()->back();
        }

        $input = $request->all();

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {
            if (is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value']);
            }
        }

        $data = [
            'user_id' => get_hash($input['user_id']),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
            'status' => WithdrawAccount::STATUS_APPROVED, // Admin created accounts are automatically approved
        ];

        WithdrawAccount::create($data);

        notify()->success(__('Successfully Withdraw Account Created'), 'success');
        return redirect()->back();
    }

    public function getUserAccounts($userId)
    {
        $userId = get_hash($userId);

        $forexAccounts = ForexAccount::with('schema')
            ->where('user_id', $userId)
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();

        $wallets = get_all_wallets($userId);

        $withdrawAccounts = WithdrawAccount::where('user_id', $userId)
            ->where('status', WithdrawAccount::STATUS_APPROVED)
            ->get()
            ->reject(function ($account) {
                return !$account->method->status;
            });

        // Prepare the response
        return response()->json([
            'forexAccounts' => $forexAccounts->map(function ($account) {
                return [
                    'login' => the_hash($account->login),
                    'login_title' => $account->login,
                    'equity' => get_mt5_account_equity($account->login),
                    'account_name' => $account->account_name,
                    'currency' => $account->schema->is_cent_account ? $account->currency . ' (Cents)' : $account->currency,
                ];
            }),
            'wallets' => $wallets->map(function ($wallet) {
                return [
                    'id' => $wallet->id,
                    'wallet_id' => the_hash($wallet->wallet_id),
                    'wallet_name' => $wallet->wallet_name,
                    'amount' => $wallet->amount,
                ];
            }),
            'withdrawAccounts' => $withdrawAccounts->map(function ($account) {
                return [
                    'id' => $account->id,
                    'method_name' => $account->method_name,
                ];
            }),
        ]);
    }

    public function details($accountId, int $amount = 0)
    {

        $withdrawAccount = WithdrawAccount::find($accountId);

        $credentials = json_decode($withdrawAccount->credentials, true);

        $currency = setting('site_currency', 'global');
        $method = $withdrawAccount->method;
        $charge = $method->charge;
        $name = $withdrawAccount->method_name;
        $processingTime = (int)$method->required_time > 0 ? 'Processing Time: ' . $withdrawAccount->method->required_time .' '. $withdrawAccount->method->required_time_format : 'This Is Automatic Method';

        $info = [
            'name' => $name,
            'charge' => $charge,
            'charge_type' => $withdrawAccount->method->charge_type,
            'range' => __('Minimum') . ' ' . $method->min_withdraw . ' ' . $currency . ' ' . __('and') . ' ' . __('Maximum') . ' ' . $method->max_withdraw . ' ' . $currency,
            'processing_time' => $processingTime,
            'rate' => $method->rate,
            'pay_currency' => $method->currency
        ];

        if ($withdrawAccount->method->charge_type != 'fixed') {
            $charge = ($charge / 100) * $amount;
        }
        $conversionRate = $method->currency != $currency ? $method->rate . ' ' . $method->currency : null;
        $html = view('backend.withdraw.include.__details', compact('credentials', 'name', 'charge', 'conversionRate'))->render();

        return [
            'html' => $html,
            'info' => $info,
        ];
    }

    public function withdrawNow(Request $request)
    {
        $input = $request->all();
//dd($input);
        $targetId = get_hash($input['target_id']);
        $targetType = TxnTargetType::Wallet->value;

        $accountType = $input['account_type'] ?? 'wallet';
        $isForexAccount = $accountType === 'forex';

        // Add conditional validation based on the account type
        $validator = Validator::make($input, [
            'user_id' => ['required'],
            'target_id' => ['required'],
            'account_type' => ['required'],
            'withdraw_account' => ['required'],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
        ], [
            'target_id.required' => __('Kindly select the account to withdraw'),
        ]);

        $approvalCause = str_replace(['{', '}'], ['<', '>'], $request->approval_cause ?? 'none');

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userID = get_hash($request->user_id);
        $user = User::findOrFail($userID);

        $amount = (float)$input['amount'];
        $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
        $withdrawMethod = $withdrawAccount->method;

        // Check if the amount is within the allowed withdraw range
        if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = __('Please withdraw the amount within the range') . ' ' . $currencySymbol . $withdrawMethod->min_withdraw . ' ' . __('to') . ' ' . $currencySymbol . $withdrawMethod->max_withdraw;
            notify()->error($message, 'Error');
            return redirect()->back()->withInput();  // Passes the old input back to the form
        }

        $charge = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
        $totalAmount = BigDecimal::of($amount)->abs();
        $payAmount = ($amount * $withdrawMethod->rate) - ($charge * $withdrawMethod->rate);
        $type = $withdrawMethod->type == 'auto' ? TxnType::WithdrawAuto : TxnType::Withdraw;

        // Validate Forex account ownership or Wallet ownership
        if ($isForexAccount) {
            // Validate Forex account ownership
            $forexAccount = ForexAccount::where('login', $targetId)
                ->where('user_id', $user->id)
                ->where('account_type', 'real') // Ensure it's a real account
                ->first();

            if (!$forexAccount) {
                notify()->error(__('The selected Forex account does not belong to this user.'), 'Error');
                return redirect()->back()->withInput();
            }

            $scaledAmount = apply_cent_account_adjustment($targetId, $amount);
            $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);

            if (BigDecimal::of($scaledAmount)->compareTo(BigDecimal::of($balance)) > 0) {
                notify()->error(__('Insufficient Balance in this Forex Account'), 'Error');
                return redirect()->back()->withInput();
            }


            // Set the transaction target type to Forex
            $targetType = TxnTargetType::ForexWithdraw->value;
        } else {
            // Validate wallet ownership
            $wallet = get_user_account_by_wallet_id($targetId, $user->id);

            if (!$wallet) {
                notify()->error(__('The selected wallet does not belong to this user.'), 'Error');
                return redirect()->back()->withInput();
            }

            $balance = BigDecimal::of($wallet->amount);

            if ($totalAmount->compareTo($balance) > 0) {
                notify()->error(__('Insufficient Balance in this Wallet'), 'Error');
                return redirect()->back()->withInput();
            }

            // Set the transaction target type to Wallet
            $targetType = TxnTargetType::Wallet->value;
        }

        // Create the transaction before attempting the withdrawal (even if the withdrawal fails later)
        $txnInfo = Txn::new(
            $input['amount'], $charge, $totalAmount, $withdrawMethod->name,
            'Withdraw With ' . $withdrawAccount->method_name, $type,
            TxnStatus::None, $withdrawMethod->currency, $payAmount, $user->id, null,
            'User', json_decode($withdrawAccount->credentials, true), 'none',
            $targetId, $targetType
        );

        $isDeducted = false;

        // Apply deduction logic for both Forex and wallet accounts
        if (setting('withdraw_deduction', 'features')) {
            if ($isForexAccount) {
                // Deduction logic for Forex
                $comment = $withdrawMethod->name . '/' . substr($txnInfo->tnx, -7);
                $data = [
                    'login' => $targetId,
                    'Amount' => apply_cent_account_adjustment($targetId, $txnInfo->final_amount),
                    'type' => 2,  // Withdraw
                    'TransactionComments' => $comment
                ];

                // Simulate balance operation via Forex API
                $withdrawResponse = $this->forexApiService->balanceOperation($data);
                if ($withdrawResponse['success'] && 
                    ($withdrawResponse['result']['responseCode'] == 10009 || $withdrawResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')
                ) {
                    $isDeducted = true; // Deduction applied
                    $updateResult =  Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, $approvalCause);
                    if (!$updateResult) {
                        $data['type'] = 1;
                        $reverseWithdrawResponse = $this->forexApiService->balanceOperation($data);
                        notify()->error('Failed to update transaction. Please try again.');
                        return redirect()->back();
                    }
                } else {
                    // Mark the transaction as failed if deduction fails
                    Txn::update($txnInfo->tnx, TxnStatus::Failed, $txnInfo->user_id, $approvalCause);
                    notify()->error(__('Insufficient Balance in this account'), 'Error');
                    return redirect()->back()->withInput();
                }
            } else {
                // Wallet deduction logic
                $walletService = new WalletService();
                $ledgerBalance = $walletService->getLedgerBalance($wallet->id);

                // Create ledger entry for the wallet deduction (Debit)
                $ledger = $walletService->createDebitLedgerEntry($txnInfo, $ledgerBalance);

                // Deduct the amount from the wallet
                $wallet->amount = BigDecimal::of($wallet->amount)->minus(BigDecimal::of($txnInfo->amount));
                $wallet->save();

                $isDeducted = true;  // Mark deduction as applied for wallet

                // Update transaction status
                Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, $approvalCause);
            }
        } else {
            // If deduction feature is disabled, mark the transaction as pending
            Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, $approvalCause);
        }

        // Ensure $txnInfo->manual_field_data is decoded as an array
        $manualFieldData = json_decode($txnInfo->manual_field_data, true);

        // If manual_field_data is null or not an array, initialize it as an empty array
        if (is_null($manualFieldData) || !is_array($manualFieldData)) {
            $manualFieldData = [];
        }

        // Add the 'Deduction Status' field to the array, formatted like the other fields
        $manualFieldData['Deduction Status'] = [
            'type' => 'text',
            'validation' => 'optional',
            'value' => $isDeducted ? __('Deducted') : __('Not Deducted')
        ];

        // Re-encode and save the updated manual_field_data
        $txnInfo->manual_field_data = json_encode($manualFieldData);
        $txnInfo->save();
        DB::commit();

        // Handle automatic withdrawals
        if ($withdrawMethod->type == 'auto') {
            $gatewayCode = $withdrawMethod->gateway->gateway_code;
            return self::withdrawAutoGateway($gatewayCode, $txnInfo);
        }

        $shortcodes = [
            '[[full_name]]' => $txnInfo->user->full_name,
            '[[txn]]' => $txnInfo->tnx,
            '[[method_name]]' => $withdrawMethod->name,
            '[[withdraw_amount]]' => $txnInfo->amount . setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        if ($request->is_auto_approve == true) {
            Txn::update($txnInfo->tnx, TxnStatus::Success, $txnInfo->user_id, $approvalCause);
            $this->mailNotify($txnInfo->user->email, 'user_auto_approve_withdrawal', $shortcodes);
            notify()->success('Withdrawal approved automatically');
            return redirect()->back();
        }

        // Send notifications
        $this->mailNotify($user->email, 'withdraw_request_user', $shortcodes);
        $this->mailNotify(setting('site_email', 'global'), 'withdraw_request', $shortcodes);
        $this->pushNotify('withdraw_request', $shortcodes, route('admin.withdraw.pending'), $user->id, 'withdraw');
        $this->smsNotify('withdraw_request', $shortcodes, $user->phone);

        return redirect()->back();
    }

    public function notificationTune()
    {
        $tunes = SetTune::all();
        return view('backend.withdraw.notification_tune', compact('tunes'));
    }


}
