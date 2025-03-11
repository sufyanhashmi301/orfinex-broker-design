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
use App\Models\WithdrawMethod;
use App\Models\WithdrawAccount;
use App\Models\ForexAccount;
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
        $this->middleware('permission:withdraw-list|withdraw-action', ['only' => ['pending', 'history']]);
        $this->middleware('permission:withdraw-action', ['only' => ['withdrawAction', 'actionNow']]);
        $this->middleware('permission:withdraw-schedule', ['only' => ['schedule', 'scheduleUpdate']]);
        $this->middleware('permission:withdraw-export', ['only' => ['export', 'pendingExport']]);
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
        $withdrawMethods = WithdrawMethod::whereType($type)->get();

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

        return view('backend.withdraw.method_create', compact('button', 'type', 'gateways', 'rates_with_countries'));
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
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'min_withdraw' => 'required',
            'max_withdraw' => 'required',
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
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'fields' => json_encode($fields ?? $input['fields']),
        ];

        $withdrawMethod = WithdrawMethod::create($data);
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

        $withdrawMethod = WithdrawMethod::find(\request('id'));
        $supported_currencies = Gateway::find($withdrawMethod->gateway_id)->supported_currencies ?? [];

        return view('backend.withdraw.method_edit', compact('button', 'withdrawMethod', 'type', 'supported_currencies'));
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
            'charge' => 'required',
            'charge_type' => 'required',
            'rate' => 'required',
            'min_withdraw' => 'required',
            'max_withdraw' => 'required',
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
            'country' => isset($input['country']) ? $input['country'] : ['All'],
            'fields' => isset($input['fields']) ? json_encode($input['fields']) : $withdrawMethod->fields,
        ];

        if ($request->hasFile('icon')) {
            $icon = self::imageUploadTrait($input['icon'], $withdrawMethod->icon);
            $data = array_merge($data, ['icon' => $icon]);
        }

        $withdrawMethod->update($data);
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
            // Check if the logged-in user is a Super-Admin
            if ($loggedInUser->hasRole('Super-Admin')) {
                $data = Transaction::where(function ($query) {
                    $query->where('type', TxnType::Withdraw)
                        ->where('status', 'pending');
                })->latest();
            } else {
                // Get attached user IDs for non-Super-Admin users
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    // Show transactions for attached users only
                    $data = Transaction::whereIn('user_id', $attachedUserIds)
                        ->where(function ($query) {
                            $query->where('type', TxnType::Withdraw)
                                ->where('status', 'pending');
                        })->latest();
                } else {
                    // If no users are attached, return an empty collection
                    $data = collect(); // Empty collection
                }
            }

            // Apply additional filters if any
            $data->applyFilters($filters);

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('status', 'backend.transaction.include.__txn_status')
                ->editColumn('type', 'backend.transaction.include.__txn_type')
                ->editColumn('amount', 'backend.transaction.include.__txn_amount')
                ->editColumn('charge', function ($request) {
                    return $request->charge . ' ' . setting('site_currency', 'global');
                })
                ->addColumn('username', 'backend.transaction.include.__user')
                ->addColumn('action', 'backend.withdraw.include.__action')
                ->rawColumns(['action', 'status', 'type', 'amount', 'username'])
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
            // Check if the logged-in user is a Super-Admin
            if ($loggedInUser->hasRole('Super-Admin')) {
                $data = Transaction::where(function ($query) {
                    $query->where('type', TxnType::Withdraw)
                        ->orWhere('type', TxnType::WithdrawAuto);
                })->latest();
            } else {
                // Get attached user IDs for non-Super-Admin users
                $attachedUserIds = $loggedInUser->users->pluck('id');

                if ($attachedUserIds->isNotEmpty()) {
                    // Show transactions for attached users only
                    $data = Transaction::whereIn('user_id', $attachedUserIds)
                        ->where(function ($query) {
                            $query->where('type', TxnType::Withdraw)
                                ->orWhere('type', TxnType::WithdrawAuto);
                        })->latest();
                } else {
                    // If no users are attached, return an empty collection
                    $data = collect(); // Empty collection
                }
            }

            // Apply additional filters if any
            $data->applyFilters($filters);

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

        return view('backend.withdraw.include.__withdraw_action', compact('data', 'id'))->render();
    }

    /**
     * @return RedirectResponse
     */
    public function actionNow(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $approvalCause = $input['message'];

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
                '[[message]]' => $approvalCause,
                '[[status]]' => isset($input['approve']) ? 'approved' : 'Rejected',
            ];

            if (isset($input['approve'])) {
                $txn = Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
                if ($txn) {
                    $this->mailNotify($user->email, 'withdraw_request_user_approve', $shortcodes);
                    notify()->success('Approve successfully');
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
                    }
                }

                Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);
                notify()->success('Reject successfully');
            }
            $transaction->action_by = auth()->user()->id;
            $transaction->save();

            $this->pushNotify('withdraw_request_user', $shortcodes, route('user.withdraw.log'), $user->id);
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
            'Amount' => $transaction->final_amount,
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

            return Excel::download(new WithdrawsExport($request), 'withdraws.xlsx');
        }
        public function pendingExport(Request $request)
        {

            return Excel::download(new PendingWithdrawsExport($request), 'pendingwithdraws.xlsx');
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
        $users = User::where('status',1)->get();
        return view('backend.withdraw.add_withdraw', compact('users'));
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

            $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);
            if ($totalAmount->compareTo($balance) > 0) {
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
                    'Amount' => $totalAmount,
                    'type' => 2,  // Withdraw
                    'TransactionComments' => $comment
                ];

                // Simulate balance operation via Forex API
                $withdrawResponse = $this->forexApiService->balanceOperation($data);
                if ($withdrawResponse['success']  && $withdrawResponse['result']['responseCode'] == 10009) {
                    $isDeducted = true; // Deduction applied
                    Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, $approvalCause);
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
        $this->pushNotify('withdraw_request', $shortcodes, route('admin.withdraw.pending'), $user->id);
        $this->smsNotify('withdraw_request', $shortcodes, $user->phone);

        return redirect()->back();
    }


}
