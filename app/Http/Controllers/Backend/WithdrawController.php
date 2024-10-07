<?php

namespace App\Http\Controllers\Backend;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\TxnTargetType;
use App\Exports\PendingWithdrawsExport;
use App\Exports\WithdrawsExport;
use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalSchedule;
use App\Models\WithdrawMethod;
use App\Services\ForexApiService;
use App\Services\WalletService;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Brick\Math\BigDecimal;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Str;
use Txn;

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
        $this->middleware('permission:withdraw-method-manage', ['only' => ['methods', 'methodCreate', 'methodStore', 'methodEdit', 'methodUpdate']]);
        $this->middleware('permission:withdraw-list|withdraw-action', ['only' => ['pending', 'history']]);
        $this->middleware('permission:withdraw-action', ['only' => ['withdrawAction', 'actionNow']]);
        $this->middleware('permission:withdraw-schedule', ['only' => ['schedule', 'scheduleUpdate']]);
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

        return view('backend.withdraw.method_create', compact('button', 'type', 'gateways'));
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
        $filters = $request->only(['email',  'created_at']);
        if ($request->ajax()) {
            $data = Transaction::where(function ($query) {
                $query->where('type', TxnType::Withdraw)
                    ->where('status', 'pending');
            })->latest();
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
        $filters = $request->only(['email', 'status',  'created_at']);
        $data = Transaction::where(function ($query) {
            $query->where('type', TxnType::Withdraw);

        })->get();

        if ($request->ajax()) {
            $data = Transaction::where(function ($query) {
                $query->where('type', TxnType::Withdraw)
                    ->orWhere('type', TxnType::WithdrawAuto);

            })->latest();
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
                ->addColumn('action', 'backend.transaction.include.__action')
                ->rawColumns(['status', 'type', 'amount', 'username','action'])
                ->make(true);
        }

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
        $transaction = Transaction::find($id);
        $user = User::find($transaction->user_id);
//dd($input);
        $shortcodes = [
            '[[full_name]]' => $user->full_name,
            '[[txn]]' => $transaction->tnx,
            '[[method_name]]' => $transaction->method,
            '[[withdraw_amount]]' => $transaction->amount . setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
            '[[message]]' => $transaction->approval_cause,
            '[[status]]' => isset($input['approve']) ? 'approved' : 'Rejected',
        ];

        if (isset($input['approve'])) {
//            if (setting('withdraw_deduction', 'features')) {//on approval from admin
//                Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
//                notify()->success('Approve successfully');
//            }else{ //on request
//                $balance = $this->forexApiService->getValidatedBalance([
//                    'login' => $transaction->target_id
//                ]);
//                $totalAmount = BigDecimal::of($transaction->amount);
//                if ($totalAmount->compareTo($balance) > 0) {
//                    notify()->error(__('Insufficient Balance in Your Account'), 'Error');
//                    return redirect()->back();
//                }
//                $comment = $transaction->method . '/' . substr($transaction->tnx, -7);
//                $data = [
//                    'login' => $transaction->target_id,
//                    'Amount' => $totalAmount,
//                    'type' => 2,//withdraw
//                    'TransactionComments' => $comment
//                ];
//                $withdrawResponse = $this->forexApiService->balanceOperation($data);
//                if ($withdrawResponse['success']) {
                    $txn = Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
                  if($txn) {
                      $this->mailNotify($user->email, 'withdraw_request_user_approve', $shortcodes);
                        notify()->success('Approve successfully');
                  }
//                } else {
//                    notify()->error(__('Something went wrong! Please try again!'), 'Error');
//                    return redirect()->back();
//                }
//            }
        } elseif (isset($input['reject'])) {

            // Fetch deduction status from transaction
            $manualFieldData = json_decode($transaction->manual_field_data, true);
            $deductionStatus = isset($manualFieldData['Deduction Status']['value']) ? $manualFieldData['Deduction Status']['value'] : 'Not Deducted';

            // If deduction was applied, create a refund transaction
            if ($deductionStatus === 'Deducted') {
                $newTransaction = $transaction->replicate();
                $newTransaction->type = TxnType::Refund;
                $newTransaction->status = TxnStatus::None;
                $newTransaction['method'] = 'system';
                $newTransaction->tnx = 'TRX' . strtoupper(Str::random(10));
                $newTransaction->save();
                $newTransaction->refresh();
                // If deduction was applied, reverse the payment
                if ($deductionStatus === 'Deducted') {
                    if (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::ForexWithdraw->value) {
                        // Reverse deduction for Forex account
                        $this->reverseForexWithdrawal($newTransaction);
                    } elseif (isset($transaction->target_id) && $transaction->target_type == TxnTargetType::Wallet->value) {
                        // Reverse deduction for Wallet account
//                    dd($transaction);
                        $this->reverseWalletWithdrawal($newTransaction);
                    }
    }
                $txn = Txn::update($newTransaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);
                if($txn) {
                    $this->mailNotify($user->email, 'withdraw_request_user_reject', $shortcodes);
                }
            }

            Txn::update($transaction->tnx, TxnStatus::Failed, $transaction->user_id, $approvalCause);

            notify()->success('Reject successfully');
        }


            $this->pushNotify('withdraw_request_user', $shortcodes, route('user.withdraw.log'), $user->id);
            $this->smsNotify('withdraw_request_user', $shortcodes, $user->phone);

            return redirect()->back();
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
    }
