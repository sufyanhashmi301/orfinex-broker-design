<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ForexAccountStatus;
use App\Models\Account;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Txn;
use Purifier;
use DataTables;
use Carbon\Carbon;
use App\Models\Rate;
use App\Enums\TxnType;
use App\Models\Invest;
use App\Models\Country;
use App\Models\Gateway;
use App\Models\SetTune;
use App\Enums\TxnStatus;
use App\Enums\GatewayType;
use App\Enums\InvestStatus;
use App\Enums\TxnTargetType;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Models\ForexAccount;
use Illuminate\Http\Request;
use App\Models\DepositMethod;
use App\Models\LevelReferral;
use App\Traits\ForexApiTrait;
use App\Exports\DepositsExport;
use App\Exports\pendingDepositsExport;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\DepositVoucher;
use App\Services\NotificationService;

class DepositController extends Controller
{
    use NotifyTrait, ImageUpload, ForexApiTrait;
    protected $notificationService;

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('permission:deposit-list|deposit-add', ['only' => ['pending', 'history']]);
        $this->middleware('permission:deposit-export', ['only' => ['export']]);
        $this->middleware('permission:deposit-add', ['only' => ['addDeposit']]);
        $this->middleware('permission:automatic-gateway-manage|manual-gateway-manage', ['only' => ['methodList']]);
        $this->middleware('permission:deposit-notification', ['only' => ['notificationTune']]);
        $this->notificationService = $notificationService;
    }

    //-------------------------------------------  Deposit method start ---------------------------------------------------------------

    public function methodList($type)
    {
        $button = [
            'name' => __('ADD NEW'),
            'icon' => 'plus',
            'route' => route('admin.deposit.method.create', $type),
        ];

        $loggedInUser = auth('admin')->user();
        $staffBranchIds = $loggedInUser->branches()->pluck('branches.id')->toArray();
        
        $depositMethodsQuery = DepositMethod::where('type', $type)->with('branches');
        
        // Apply branch filtering for non-Super-Admin staff
        if (!$loggedInUser->hasRole('Super-Admin') && !empty($staffBranchIds)) {
            $depositMethodsQuery->where(function($query) use ($staffBranchIds) {
                $query->whereHas('branches', function($branchQuery) use ($staffBranchIds) {
                    $branchQuery->whereIn('branch_id', $staffBranchIds);
                })->orWhereDoesntHave('branches');
            });
        }
        
        $depositMethods = $depositMethodsQuery->get();

        return view('backend.deposit.method_list', compact('depositMethods', 'button', 'type'));
    }

    public function createMethod($type)
    {
        $gateways = Gateway::where('status', true)->get();
        $rates_with_countries = Rate::with('country')->get();
        $autoExchangeRatesEnabled = setting('auto_exchange_rates_update', 'permission', 1);
        $branches = \App\Models\Branch::where('status', 1)->get();

        return view('backend.deposit.create_method', compact('type', 'gateways', 'rates_with_countries', 'autoExchangeRatesEnabled', 'branches'));
    }

    public function methodStore(Request $request)
    {
        $input = $request->all();
        $input['payment_details'] = str_replace(['{', '}'], ['<', '>'], $request->payment_details);

        $validator = Validator::make($input, [
            'logo' => 'required_if:type,==,manual',
            'name' => 'required',
            'gateway_id' => 'required_if:type,==,auto',
            'method_code' => 'unique:deposit_methods,gateway_code|required_if:type,==,manual',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'charge' => 'required|numeric|gte:0',
            'charge_type' => 'required',
            'rate' => 'required|numeric|gte:0',
            'minimum_deposit' => 'required|numeric|gte:0',
            'maximum_deposit' => 'required|numeric|gte:0',
            'processing_time' => 'required',
            'status' => 'required',
            'field_options' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        if (isset($input['gateway_id'])) {
            $gateway = Gateway::find($input['gateway_id']);
            $methodCode = $gateway->gateway_code . '-' . strtolower($input['currency']);
        }

        // Prevent creating duplicate auto methods with the same gateway + currency
        if (($input['type'] ?? null) === 'auto' && isset($methodCode)) {
            if (DepositMethod::where('gateway_code', $methodCode)->exists()) {
                notify()->error(__('This automatic method for the selected gateway and currency already exists.'), 'Error');
                return redirect()->back()->withInput();
            }
        }

        $data = [
            'logo' => isset($input['logo']) ? self::imageUploadTrait($input['logo']) : null,
            'name' => $input['name'],
            'type' => $input['type'],
            'gateway_id' => $input['gateway_id'] ?? null,
            'gateway_code' => $input['method_code'] ?? $methodCode,
            'currency' => $input['currency'],
            'currency_symbol' => $input['currency_symbol'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'minimum_deposit' => $input['minimum_deposit'],
            'maximum_deposit' => $input['maximum_deposit'],
            'processing_time' => $input['processing_time'],
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'status' => $input['status'],
            'is_global' => isset($input['is_global']) ? (bool) $input['is_global'] : false,
            'is_custom_bank_details' => isset($input['is_custom_bank_details']) ? (bool) $input['is_custom_bank_details'] : false,
            'field_options' => isset($input['field_options']) ? json_encode($input['field_options']) : null,
            'payment_details' => isset($input['payment_details']) ? Purifier::clean(htmlspecialchars_decode($input['payment_details'])) : null,
        ];

        $depositMethod = DepositMethod::create($data);
        
        // Sync branches if provided
        if (!empty($input['branches'])) {
            $depositMethod->branches()->sync($input['branches']);
        }
        
        notify()->success($depositMethod->name . ' ' . __(' Method Created'));

        return redirect()->route('admin.deposit.method.list', $depositMethod->type);
    }

    public function methodEdit($type)
    {
        $gateways = Gateway::where('status', true)->get();
        $method = DepositMethod::with('branches')->find(\request('id'));
        $supported_currencies = Gateway::find($method->gateway_id)->supported_currencies ?? [];
        $autoExchangeRatesEnabled = setting('auto_exchange_rates_update', 'permission', 1);
        $branches = \App\Models\Branch::where('status', 1)->get();
        $attachedBranches = $method->branches->pluck('id')->toArray();

        return view('backend.deposit.edit_method', compact('method', 'type', 'gateways', 'supported_currencies', 'autoExchangeRatesEnabled', 'branches', 'attachedBranches'));
    }

    public function methodUpdate($id, Request $request)
    {
        $input = $request->all();
        $input['payment_details'] = str_replace(['{', '}'], ['<', '>'], $request->payment_details);
//        dd($input);
        $validator = Validator::make($input, [
            'name' => 'required',
            'gateway_id' => 'required_if:type,==,auto',
            'currency' => 'required',
            'currency_symbol' => 'required',
            'charge' => 'required|numeric|gte:0',
            'charge_type' => 'required',
            'rate' => 'required|numeric|gte:0',
            'minimum_deposit' => 'required|numeric|gte:0',
            'maximum_deposit' => 'required|numeric|gte:0',
            'processing_time' => 'required',
            'status' => 'required',
            'field_options' => 'required_if:type,==,manual',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);  // Laravel will return status 422 automatically
        }

        $depositMethod = DepositMethod::find($id);

        $user = \Auth::user();
        if ($depositMethod->type == GatewayType::Automatic) {
            if (!$user->can('automatic-gateway-manage')) {
//                return redirect()->route('admin.deposit.method.list', $depositMethod->type);
                return response()->json(['redirect'=> route('admin.deposit.method.list', $depositMethod->type)]);

            }

        } else {
            if (!$user->can('manual-gateway-manage')) {
                return response()->json(['redirect'=> route('admin.deposit.method.list', $depositMethod->type)]);

//                return redirect()->route('admin.deposit.method.list', $depositMethod->type);
            }
        }

        $data = [
            'name' => $input['name'],
            'type' => $input['type'],
            'gateway_id' => $input['gateway_id'] ?? null,
            'currency' => $input['currency'],
            'currency_symbol' => $input['currency_symbol'],
            'charge' => $input['charge'],
            'charge_type' => $input['charge_type'],
            'rate' => $input['rate'],
            'minimum_deposit' => $input['minimum_deposit'],
            'maximum_deposit' => $input['maximum_deposit'],
            'processing_time' => $input['processing_time'],
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'status' => $input['status'],
            'is_global' => isset($input['is_global']) ? (bool) $input['is_global'] : false,
            'is_custom_bank_details' => isset($input['is_custom_bank_details']) ? (bool) $input['is_custom_bank_details'] : false,
            'field_options' => isset($input['field_options']) ? json_encode($input['field_options']) : null,
            'payment_details' => isset($input['payment_details']) ? $input['payment_details'] : null,
        ];
//dd($input['payment_details'],$data);
        if ($request->hasFile('logo')) {
            $logo = self::imageUploadTrait($input['logo'], $depositMethod->logo);
            $data = array_merge($data, ['logo' => $logo]);
        }

        $depositMethod->update($data);
        
        // Sync branches if provided
        if (isset($input['branches'])) {
            $depositMethod->branches()->sync($input['branches']);
        } else {
            $depositMethod->branches()->detach();
        }
        
        notify()->success($depositMethod->name . ' ' . __(' Method Updated'));
        return response()->json(['redirect'=> route('admin.deposit.method.list', $depositMethod->type)]);

//        return redirect()->route('admin.deposit.method.list', $depositMethod->type);
    }

    //-------------------------------------------  Deposit method end ---------------------------------------------------------------

    public function pending(Request $request)
    {
        $loggedInUser = auth()->user();

        if ($request->ajax()) {
            $filters = $request->only(['email', 'status', 'created_at']);
            // ✅ Use helper to get allowed user IDs
            $accessibleUserIds = getAccessibleUserIds()->pluck('id');

            // ✅ Build the base query
            $data = Transaction::query()
                ->whereIn('status', [TxnStatus::Pending,TxnStatus::Review])
                ->whereIn('type', [TxnType::ManualDeposit])
                ->whereIn('user_id', $accessibleUserIds);

            // Apply additional filters
            $data->applyFilters($filters);

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
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.deposit.include.__action')
                // Server-side ordering mappings
                ->orderColumn('created_at', 'transactions.created_at $1')
                ->orderColumn('username', 'username_sort $1')
                ->orderColumn('tnx', 'transactions.tnx $1')
                ->orderColumn('target_id', 'transactions.target_id $1')
                ->orderColumn('amount', 'signed_amount $1')
                ->orderColumn('final_amount', 'signed_amount $1')
                ->orderColumn('charge', 'transactions.charge $1')
                ->orderColumn('method', 'transactions.method $1')
                ->orderColumn('action_by', 'action_by_sort $1')
                ->orderColumn('status', 'transactions.status $1')
                ->rawColumns(['created_at', 'action', 'status', 'type', 'amount', 'username'])
                ->make(true);
        }

        return view('backend.deposit.manual');
    }



    public function history(Request $request)
    {
        $loggedInUser = auth()->user();
        $filters = $request->only(['email', 'status', 'created_at']);

        if ($request->ajax()) {
            // ✅ Get accessible user IDs using the helper
            $accessibleUserIds = getAccessibleUserIds()->pluck('id');

            // ✅ Base query: only deposits and manual deposits, exclude none status
            $data = Transaction::query()
                ->where(function ($query) {
                    $query->where('type', TxnType::ManualDeposit)
                        ->orWhere('type', TxnType::Deposit);
                })
                ->where('status', '!=', \App\Enums\TxnStatus::None) // Exclude none status transactions
                ->whereIn('user_id', $accessibleUserIds);

            // ✅ Optional filter by selected user (hashed id from Add Deposit)
            if ($request->filled('user_id')) {
                $selectedUserId = get_hash($request->input('user_id'));
                if (!empty($selectedUserId)) {
                    $data->where('user_id', $selectedUserId);
                }
            }

            // ✅ Apply filters (if any)
            $data->applyFilters($filters);

            // Select sortable projections for username, action_by and signed final amount
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
                            THEN -1 * CAST(COALESCE(transactions.final_amount, 0) AS DECIMAL(18,8))
                        ELSE CAST(COALESCE(transactions.final_amount, 0) AS DECIMAL(18,8))
                    END
                ) as signed_final_amount");

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return '<span class="text-nowrap">' . $row->created_at . '</span>';
                })
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('final_amount', 'backend.transaction.include.__txn_amount')
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
                ->orderColumn('tnx', 'transactions.tnx $1')
                ->orderColumn('target_id', 'transactions.target_id $1')
                ->orderColumn('final_amount', 'signed_final_amount $1')
                ->orderColumn('amount', 'signed_final_amount $1')
                ->orderColumn('method', 'transactions.method $1')
                ->orderColumn('charge', 'transactions.charge $1')
                ->orderColumn('action_by', 'action_by_sort $1')
                ->orderColumn('status', 'transactions.status $1')
                ->rawColumns(['created_at', 'status', 'type', 'action_by', 'final_amount', 'username', 'action'])
                ->make(true);
        }

        return view('backend.deposit.history');
    }



    public function depositAction($id)
    {

        $data = Transaction::find($id);
        $gateway = $this->gateway($data->method);
        return view('backend.deposit.include.__deposit_action', compact('data', 'id', 'gateway'))->render();
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


    public function actionNow(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $approvalCause = $input['message'];
        $transaction = Transaction::find($id);

        if (!$transaction) {
            notify()->error('Transaction not found');
            return redirect()->back();
        }

        // Prevent double processing
        DB::beginTransaction();
        try {
            $existingTransaction = Transaction::where('id', $id)->lockForUpdate()->first();
            // dd($existingTransaction->status);

            if ($existingTransaction->status != TxnStatus::Pending && $existingTransaction->status != TxnStatus::Review) {
                notify()->warning('This transaction has already been processed.');
                DB::rollBack();
                return redirect()->back();
            }
            $shortcodes = [
                '[[full_name]]' => $transaction->user->full_name,
                '[[txn]]' => $transaction->tnx,
                '[[gateway_name]]' => $transaction->method,
                '[[deposit_amount]]' => $transaction->amount,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[message]]' => $approvalCause,
                '[[status]]' => isset($input['approve']) ? 'approved' : 'rejected',
            ];

            if (isset($input['approve'])) {
                // Update transaction
                $transaction->amount = $input['final_amount'];
                $transaction->final_amount = $input['final_amount'];
                $transaction->pay_amount = $input['pay_amount'] ?? $input['final_amount'];
                $transaction->save();

                // Call transaction update method
                $updateResult = Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
                if (!$updateResult) {
                    DB::rollBack();
                    notify()->error('Failed to update transaction. Please try again.');
                    return redirect()->back();
                }

                // Notify user
                try {
                    $notificationService = app(NotificationService::class);
                    $notificationService->transactionStatus($transaction, 'success');
                    $notificationService->adminTransactionAlert($transaction);
                } catch (\Throwable $e) {
                    Log::error('Manual deposit approval notification failed', [
                        'transaction_tnx' => $transaction->tnx,
                        'error' => $e->getMessage(),
                    ]);
                }
                
                notify()->success('Deposit approved successfully.');

            } elseif (isset($input['reject'])) {
                // Reject transaction
                $updateResult = Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);
                if (!$updateResult) {
                    DB::rollBack();
                    notify()->error('Failed to reject transaction. Please try again.');
                    return redirect()->back();
                }

                try {
                    $notificationService = app(NotificationService::class);
                    $notificationService->transactionStatus($transaction, 'rejected');
                    $notificationService->adminTransactionAlert($transaction);
                } catch (\Throwable $e) {
                    Log::error('Manual deposit rejection notification failed', [
                        'transaction_tnx' => $transaction->tnx,
                        'error' => $e->getMessage(),
                    ]);
                }

                notify()->success('Deposit rejected successfully.');
            }

            $transaction->approval_cause = $approvalCause;
            $transaction->action_by = auth()->user()->id;
            $transaction->save();
            DB::commit();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Deposit action failed: ' . $e->getMessage());
            notify()->error('An error occurred while processing the deposit. Please try again.');
            return redirect()->back();
        }
    }
    public function export(Request $request, $type = null)
{
    switch ($type) {
        case 'pending':
            return Excel::download(new pendingDepositsExport($request), 'pending-deposits.xlsx');
        default:
        return Excel::download(new DepositsExport($request), 'deposits-history.xlsx');
    }
}
    public function view($id)
    {
        $transaction = Transaction::find($id);
        return response()->json(['transaction'=>$transaction]);
    }

    public function destroy($id)
    {
//        dd($id);
        try {
            // Find the method by its ID and delete it
            $method = DepositMethod::with('branches')->findOrFail($id);
            
            // Check if method is associated with existing transactions
            if(Transaction::where('method',$method->gateway_code)->exists()){
                notify()->error(__('This method is associated with existing transactions, and therefore cannot be deleted: :method', ['method' => $method->name]), 'Error');
                return redirect()->back();
            }
            
            // Get branch count for logging
            $branchCount = $method->branches->count();
            $methodName = $method->name;
            
            // Delete the method (cascade will automatically delete branch relationships)
            $method->delete();
            
            $message = $branchCount > 0 
                ? "Successfully deleted method '{$methodName}' and {$branchCount} branch assignment(s)"
                : "Successfully deleted method '{$methodName}'";
                
            notify()->success($message);

            return redirect()->back();
        } catch (\Exception $e) {
            notify()->error('Something went wrong, Please check error log', 'Error Log');
            return redirect()->back();
        }
    }

   public function addDeposit()
{
    $gateways = DepositMethod::where('status', 1)->get();
        $loggedInUser = auth()->user();

    // ✅ Get accessible user IDs using the helper
    $accessibleUserIds = getAccessibleUserIds()->pluck('id');

    // ✅ Fetch only users with 'status = 1' and within accessible IDs
    $users = User::where('status', 1)
        ->whereIn('id', $accessibleUserIds)
        ->get();

    $forexAccounts = ForexAccount::with('schema')
        ->traderType()
        ->where('account_type', 'real')
        ->where('status', ForexAccountStatus::Ongoing)
        ->orderBy('id', 'desc')
        ->get();

    $currency = setting('site_currency', 'global');
    return view('backend.deposit.add_deposit', compact('users', 'gateways', 'forexAccounts', 'currency'));
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
        return response()->json([
            'forexAccounts' => $forexAccounts->map(function ($account) {
                // Ensure wallet_name is included
                return [
                    'login' => $account->login,
                    'equity' => get_mt5_account_equity($account->login),
                    'account_name' => $account->account_name, // This is the accessor you defined
                    'currency' => $account->schema->is_cent_account ? $account->currency . ' (Cents)' : $account->currency,
                ];
            }),
            'wallets' => $wallets->map(function ($wallet) {
                // Ensure wallet_name is included
                return [
                    'id' => $wallet->id,
                    'wallet_id' => $wallet->wallet_id,
                    'wallet_name' => $wallet->wallet_name, // This is the accessor you defined
                    'amount' => $wallet->amount
                ];
            })
        ]);

    }
    public function depositNow(Request $request)
    {
        // Validate input
        $rules = [
            'user_id'       => ['required'],
            'target_id'     => ['required'],
            'gateway_code'  => 'required',
            'amount'        => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
        ];

        if ($request->input('gateway_code') === 'voucher') {
            $rules['manual_data.voucher code'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, [
            'target_id.required' => __('Kindly select an account for deposit'),
            'manual_data.voucher code.required' => __('Voucher code is required'),
        ]);

        try {
            DB::beginTransaction();

            $userID = get_hash($request->user_id);
            $user = User::findOrFail($userID);
            $input = $request->all();

            $gateway = DepositMethod::code($input['gateway_code'])->firstOrFail();
            $amount = (float)$input['amount'];

            // Voucher logic
            if ($input['gateway_code'] === 'voucher') {
                $voucherCode = $input['manual_data']['voucher code'] ?? null;
                $voucher = DepositVoucher::where('code', $voucherCode)->first();

                if (!$voucher || $voucher->status !== 'active' || $voucher->expiry_date < now()) {
                    $msg = !$voucher ? __('Invalid voucher code.') : ($voucher->status !== 'active' ? __('Voucher is not active.') : __('Voucher has expired.'));
                    notify()->error($msg, __('Error'));
                    return redirect()->back();
                }

                $amount = $voucher->amount;
                $request->merge(['is_auto_approve' => true]);
            }

            // Validate deposit limits
            if ($amount < $gateway->minimum_deposit || $amount > $gateway->maximum_deposit) {
                $currencySymbol = setting('currency_symbol', 'global');
                notify()->error(
                    __('Please deposit the amount within the range') . " {$currencySymbol}{$gateway->minimum_deposit} " . __('to') . " {$currencySymbol}{$gateway->maximum_deposit}",
                    __('Error')
                );
                return redirect()->back();
            }

            // Determine target account type
            $targetId = $input['target_id'];
            $targetType = TxnTargetType::ForexDeposit->value;

        $forexAccount = ForexAccount::where('login', $targetId)->first();
        if (!$forexAccount) {
            $walletAccount = get_user_account_by_wallet_id($targetId, $user->id);
            if (!$walletAccount) {
                notify()->error(__('The selected account does not exist'), __('Error'));
                return redirect()->back();
            }
            $targetType = TxnTargetType::Wallet->value;
        }

        // Calculate charges
        $charge = $gateway->charge_type === 'percentage'
            ? ($gateway->charge / 100) * $amount
            : $gateway->charge;

        $finalAmount = $amount - $charge;
        $payAmount = $finalAmount * $gateway->rate;

        if ($input['gateway_code'] === 'voucher') {
            $depositType = TxnType::VoucherDeposit;
        }else {
            $depositType = TxnType::ManualDeposit;
        }

        // Handle manual data (e.g., file uploads)
        $manualData = [];
        if (!empty($input['manual_data'])) {
            foreach ($input['manual_data'] as $key => $value) {
                $manualData[$key] = is_file($value) ? self::depositImageUploadTrait($value) : $value;
            }
        }

        // Create transaction
        $approvalCause = $request->input('approval_cause', 'none');
        $txn = Txn::new(
            $finalAmount, $charge, $amount, $gateway->gateway_code,
            __('Deposit With ') . $gateway->name . __(' by Admin'), $depositType, TxnStatus::Pending,
            $gateway->currency, $payAmount, $userID, null, 'User',
            $manualData, $approvalCause, $targetId, $targetType
        );

        // Prepare email/notification placeholders
        $shortcodes = [
            '[[full_name]]'      => $txn->user->full_name,
            '[[txn]]'            => $txn->tnx,
            '[[gateway_name]]'   => $txn->method,
            '[[deposit_amount]]' => $txn->amount,
            '[[site_title]]'     => setting('site_title', 'global'),
            '[[site_url]]'       => route('home'),
            '[[message]]'        => $txn->approval_cause,
            '[[status]]'         => $request->is_auto_approve ? 'approved' : 'Pending',
        ];

        // Auto approval logic
        if ($request->boolean('is_auto_approve')) {
            if (!Txn::update($txn->tnx, TxnStatus::Success, $txn->user_id, $approvalCause)) {
                notify()->error(__('Failed to update transaction. Please try again.'));
                return redirect()->back();
            }

            if ($input['gateway_code'] === 'voucher' && isset($voucher) && $voucher->status !== 'used') {
                $voucher->update([
                    'status'     => 'used',
                    'used_by'    => $txn->user_id,
                    'used_date'  => now(),
                    'modal'      => $txn->from_model,
                ]);
            }

            try {
                $notificationService = app(NotificationService::class);
                $notificationService->transactionStatus($txn, 'success');
                $notificationService->adminTransactionAlert($txn);
            } catch (\Throwable $e) {
                Log::error('Manual deposit approval notification failed', [
                    'transaction_tnx' => $txn->tnx,
                    'error' => $e->getMessage(),
                ]);
            }
            notify()->success(__('Approve successfully'));
        } else {
            try {
                $notificationService = app(NotificationService::class);
                $notificationService->transactionStatus($txn, 'pending');
                $notificationService->adminTransactionAlert($txn);
            } catch (\Throwable $e) {
                Log::error('Manual deposit creation notification failed', [
                    'transaction_tnx' => $txn->tnx,
                    'error' => $e->getMessage(),
                ]);
            }
            notify()->success(__('Successfully added pending deposit request'));
        }

        DB::commit();

        return redirect()->back();
    } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Deposit Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            notify()->error(__('An unexpected error occurred. Please try again.'));
            return redirect()->back()->withInput();
        }
    }

    public function getVoucher(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $voucher = DepositVoucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Invalid voucher code.']);
        }

        if ($voucher->status === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'This voucher has already been used.'
            ]);
        }

        if ($voucher->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This voucher is not active.'
            ]);
        }

        if ($voucher->expiry_date < now()) {
            return response()->json(['success' => false, 'message' => 'Voucher has expired.']);
        }

        return response()->json([
            'success' => true,
            'amount' => $voucher->amount,
        ]);
    }

    public function notificationTune()
    {
        $tunes = SetTune::all();
        return view('backend.deposit.notification_tune', compact('tunes'));
    }

}

