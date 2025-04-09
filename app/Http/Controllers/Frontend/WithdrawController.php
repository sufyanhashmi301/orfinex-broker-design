<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\TxnTargetType;
use App\Enums\AccountBalanceType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Models\WithdrawAccount;
use App\Models\WithdrawalSchedule;
use App\Models\WithdrawMethod;
use App\Models\UserOtp;
use App\Rules\ForexLoginBelongsToUser;
use App\Services\ForexApiService;
use App\Services\OtpService;
use App\Services\WalletService;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Traits\Payment;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WithdrawHistoryExport;

use Session;
use Txn;
use Validator;

class WithdrawController extends Controller
{
    use ImageUpload, NotifyTrait, Payment, ForexApiTrait;
    protected $forexApiService, $otpService;

    public function __construct(ForexApiService $forexApiService, OtpService $otpService)
    {
        $this->forexApiService = $forexApiService;
        $this->otpService = $otpService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = WithdrawAccount::where('user_id', auth()->id())->get();

        return view('frontend::withdraw.account.index', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
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
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        WithdrawAccount::create($data);

        notify()->success(__('Successfully Withdraw Account Created'), 'success');

        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $withdrawMethods = WithdrawMethod::where('status', true)
            ->where(function($query) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All');
            })->get();


        return view('frontend::withdraw.account.create', compact('withdrawMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $withdrawMethods = WithdrawMethod::where(function($query) {
            $query->whereJsonContains('country', auth()->user()->country)
                ->orWhereJsonContains('country', 'All');
        })->get();
        $withdrawAccount = WithdrawAccount::where('id',get_hash($id))->where('user_id',auth()->user()->id)->first();
        if($withdrawAccount){
            return view('frontend::withdraw.account.edit', compact('withdrawMethods', 'withdrawAccount'));
        }
        return redirect()->back();


    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
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

        $withdrawAccount = WithdrawAccount::find($id);

        $oldCredentials = json_decode($withdrawAccount->credentials, true);

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (!isset($value['value'])) {
                $credentials[$key]['value'] = $oldCredentials[$key]['value'];
            }

            if (isset($value['value']) && is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], $oldCredentials[$key]['value']);
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
        ];

        $withdrawAccount->update($data);
        notify()->success(__('Successfully Withdraw Account Updated'), 'success');

        return redirect()->route('user.withdraw.account.index');

    }

    /**
     * @return string
     */
    public function withdrawMethod($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);

        if ($withdrawMethod) {
            return view('frontend::withdraw.include.__account', compact('withdrawMethod'))->render();
        }

        return '';
    }

    /**
     * @return array
     */
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
        $html = view('frontend::withdraw.include.__details', compact('credentials', 'name', 'charge', 'conversionRate'))->render();

        return [
            'html' => $html,
            'info' => $info,
        ];
    }

    /**
     * @return string
     */
    public function withdrawNow(Request $request)
    {
//        try {
//            DB::beginTransaction();

            $validationResult = $this->validateWithdrawal($request);
            if (!$validationResult) {
                return redirect()->back()->withInput();
            }

            if (setting('withdraw_otp', 'withdraw_settings')) {
                $user = Auth::user();

                $transactionType = TxnType::Withdraw->value;
                $userOtp = UserOtp::where('user_id', $user->id)->where('type', $transactionType)->first();

                if ($userOtp && $userOtp->verified && $userOtp->expires_at > Carbon::now()) {

                    $userOtp->delete();
                    return $this->processWithdrawal($validationResult);

                } else {
                    $withdrawalData = [
                        'target_id' => $request->input('target_id'),
                        'account_type' => $request->input('account_type'),
                        'withdraw_account' => $request->input('withdraw_account'),
                        'amount' => $request->input('amount'),
                    ];

                    Session::put('withdrawal_data', $withdrawalData);
                    $transactionType = TxnType::Withdraw->value;

                    $this->otpService->sendOtp($user, $transactionType);

                    notify()->info(__('OTP has been sent. Please verify it to proceed.'), 'OTP Sent');
                    return redirect()->back()->withInput();

                }
            }else{
                // Create the transaction before attempting the withdrawal (even if the withdrawal fails later)
                return $this->processWithdrawal($validationResult);
            }
//        } catch (Exception $e) {
//            DB::rollBack();
//            notify()->error(__('An error occurred while processing your withdrawal. Please try again later.'), 'Error');
//            return redirect()->back()->withInput();
//        }
    }

    public function resendOtp(Request $request)
    {
        $user = Auth::user();
        $transactionType = TxnType::Withdraw->value;

        $this->otpService->sendOtp($user, $transactionType); // Call the method to resend OTP

        return response()->json([
            'success' => true,
            'message' => 'OTP has been resent successfully.',
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $user = Auth::user();
        $otpInput = $request->input('otp');
        $transactionType = TxnType::Withdraw->value;

        // Validate the OTP
        $otpValidationResult = $this->otpService->validateOtp($user, $transactionType, $otpInput);

        // If the OTP is invalid or expired, return an error
        if (isset($otpValidationResult['status']) && $otpValidationResult['status'] === 'error') {
            return response()->json($otpValidationResult, 400);
        }

        // Mark OTP as verified
        $userOtp = UserOtp::where('user_id', $user->id)->where('type', $transactionType)->first();
        $userOtp->verified = true;
        $userOtp->save();

        return response()->json([
            'status' => 'success',
            'message' => __('OTP successfully verified. Proceed with the transaction.')
        ], 200);
    }

    public function validateWithdrawal(Request $request)
    {
        $user = Auth::user();
        $input = $request->all();

        if (!setting('user_withdraw', 'permission') || !$user->withdraw_status) {
            abort('403', __('Withdraw Disabled Now'));
        }

        if (auth()->user()->kyc < kyc_required_completed_level()) {
            notify()->error('KYC Pending: Please complete your KYC verification to proceed with your withdrawal', __('Error'));
            return false;
        }
        // Check if today is a withdraw off day
        $withdrawOffDays = WithdrawalSchedule::where('status', 0)->pluck('name')->toArray();
        $date = Carbon::now();
        $today = $date->format('l');

        if (in_array($today, $withdrawOffDays)) {
            abort('403', __('Today is the off day for withdraw'));
        }

        // Check daily send limit for successful transactions only
        $pendingLimit = setting('pending_withdraw_limit', 'withdraw_settings');
        $pendingWithdraws = Transaction::where('user_id', $user->id)
            ->whereIn('type', [TxnType::WithdrawAuto, TxnType::Withdraw])
            ->where('status', TxnStatus::Pending)
            ->count();

        if ($pendingWithdraws >= $pendingLimit) {
            notify()->error(
                __("You already have a pending withdrawal request. Please contact our support team at :email for assistance.", [
                    'email' => setting('support_email', 'global')
                ]),
                __('Error')
            );
            return false;
        }
        // Check daily send limit for successful transactions only
        $dailyLimit = setting('withdraw_day_limit', 'fee');
        $todayTransfers = Transaction::where('user_id', $user->id)
            ->whereIn('type', [TxnType::WithdrawAuto, TxnType::Withdraw])
            ->whereDate('created_at', today())
            ->count();

        if ($todayTransfers >= $dailyLimit) {
            notify()->error(__('You have reached the daily withdraw limit.'), __('Error'));
            return false;
        }

        // Decrypt the hashed target_id
        $targetId = get_hash($input['target_id']);
        $targetType = TxnTargetType::Wallet->value;  // Default to wallet

        // Determine whether the target is a Forex account or wallet
        $accountType = $input['account_type'] ?? 'wallet';
        $isForexAccount = $accountType === 'forex';

        // Add conditional validation based on the account type
        $validator = Validator::make($input, [
            'target_id' => ['required'],
            'account_type' => ['required'],
            'withdraw_account' => ['required'],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
        ], [
            'target_id.required' => __('Kindly select the account to withdraw'),
        ]);

        if ($validator->fails()) {
            // Send back validation errors with old input
            notify()->error($validator->errors()->first(), 'Error');
            return false;
        }

        $amount = (float)$input['amount'];
        $withdrawAccount = WithdrawAccount::find($input['withdraw_account']);
        $withdrawMethod = $withdrawAccount->method;

        // Check if the amount is within the allowed withdraw range
        if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = __('Please withdraw the amount within the range') . ' ' . $currencySymbol . $withdrawMethod->min_withdraw . ' ' . __('to') . ' ' . $currencySymbol . $withdrawMethod->max_withdraw;
            notify()->error($message, 'Error');
            return false;
        }

        $charge = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
        $totalAmount = BigDecimal::of($amount)->abs();
        $payAmount = ($amount * $withdrawMethod->rate) - ($charge * $withdrawMethod->rate);
        $type = $withdrawMethod->type == 'auto' ? TxnType::WithdrawAuto : TxnType::Withdraw;

        $wallet = null;

        // Validate Forex account ownership or Wallet ownership
        if ($isForexAccount) {
            // Validate Forex account ownership
            $forexAccount = ForexAccount::where('login', $targetId)
                ->where('user_id', $user->id)
                ->where('account_type', 'real') // Ensure it's a real account
                ->first();

            if (!$forexAccount) {
                notify()->error(__('The selected Forex account does not belong to you.'), 'Error');
                return false;
            }

            $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);
            if ($totalAmount->compareTo($balance) > 0) {
                notify()->error(__('Insufficient Balance in Your Forex Account'), 'Error');
                return false;
            }

            // Set the transaction target type to Forex
            $targetType = TxnTargetType::ForexWithdraw->value;
        } else {

            // Validate wallet ownership
            $wallet = get_user_account_by_wallet_id($targetId, $user->id);
            if (!$wallet) {
                notify()->error(__('The selected wallet does not belong to you.'), 'Error');
                return false;
            }

            $balance = BigDecimal::of($wallet->amount);
            if ($totalAmount->compareTo($balance) > 0) {
                notify()->error(__('Insufficient Balance in Your Wallet'), 'Error');
                return false;
            }

            if ($wallet->balance === AccountBalanceType::IB_WALLET) {
                $ibMinLimit = setting('min_ib_wallet_withdraw_limit', 'withdraw_settings');
                if ($amount < $ibMinLimit) {
                    notify()->error(__('You must withdraw at least :limit from IB Wallet.', [
                        'limit' => setting('currency_symbol', 'global') . $ibMinLimit
                    ]), 'Error');
                    return false;
                }
            }

            // Set the transaction target type to Wallet
            $targetType = TxnTargetType::Wallet->value;
        }

        return [
            'user' => $user,
            'isForexAccount' => $isForexAccount,
            'targetId' => $targetId,
            'wallet' => $wallet,
            'amount' => $amount,
            'charge' => $charge,
            'totalAmount' => $totalAmount,
            'type' => $type,
            'payAmount' => $payAmount,
            'withdrawMethod' => $withdrawMethod,
            'withdrawAccount' => $withdrawAccount,
            'targetType' => $targetType,
        ];
    }

    public function processWithdrawal(array $data)
    {
        $user = $data['user'];
        $isForexAccount = $data['isForexAccount'];
        $targetId = $data['targetId'];
        $wallet = $data['wallet'];
        $amount = $data['amount'];
        $charge = $data['charge'];
        $type = $data['type'];
        $payAmount = $data['payAmount'];
        $totalAmount = $data['totalAmount'];
        $withdrawMethod = $data['withdrawMethod'];
        $withdrawAccount = $data['withdrawAccount'];
        $targetType = $data['targetType'];

        $txnInfo = Txn::new(
            $amount, $charge, $totalAmount, $withdrawMethod->name,
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
                    Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, __('Pending Request'));
                } else {
                    // Mark the transaction as failed if deduction fails
                    Txn::update($txnInfo->tnx, TxnStatus::Failed, $txnInfo->user_id, __('Insufficient Withdrawable Balance'));
                    notify()->error(__('Insufficient Balance in Your account'), 'Error');
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
                Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, __('Pending Request'));
            }
        } else {
            // If deduction feature is disabled, mark the transaction as pending
            Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, __('Pending Request'));
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

        // Notify user and admin
        $symbol = setting('currency_symbol', 'global');
        $notify = [
            'card-header' => __('Withdraw Money'),
            'title' => $symbol . $txnInfo->amount . ' ' . __('Withdraw Request Successful'),
            'p' => __('The Withdraw Request has been successfully sent'),
            'strong' => __('Transaction ID: ') . $txnInfo->tnx,
            'action' => route('user.withdraw.view'),
            'a' => __('WITHDRAW REQUEST AGAIN'),
            'view_name' => 'withdraw',
        ];
        Session::put('user_notify', $notify);

        $shortcodes = [
            '[[full_name]]' => $txnInfo->user->full_name,
            '[[txn]]' => $txnInfo->tnx,
            '[[method_name]]' => $withdrawMethod->name,
            '[[withdraw_amount]]' => $txnInfo->amount . setting('site_currency', 'global'),
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        // Send notifications
        $this->mailNotify($user->email, 'withdraw_request_user', $shortcodes);
        $this->mailNotify(setting('site_email', 'global'), 'withdraw_request', $shortcodes);
        $this->pushNotify('withdraw_request', $shortcodes, route('admin.withdraw.pending'), $user->id);
        $this->smsNotify('withdraw_request', $shortcodes, $user->phone);

        if(session()->has('withdrawal_data')) {
            Session::forget('withdrawal_data');
        }

        return redirect()->route('user.notify');
    }

    public function WithdrawApiCall($login, $totalAmount)
    {
        $withdrawUrl = config('forextrading.withdrawUrl');
        $auth = config('forextrading.auth');

        $dataArray = [
            'Login' => $login,
            'Withdraw' => $totalAmount,
            'Comment' => "Withdraw/USD",

        ];
//        dd($userAccount,$dataArray);
        $withdrawResponse = $this->sendApiRequest($withdrawUrl, $dataArray);
//        dd($userAccount,$withdrawResponse);
        if ($withdrawResponse ? $withdrawResponse->status() == 200 && $withdrawResponse->object()->data == 0 : false) {
            return true;
        }

    }

    /**
     * @return Application|Factory|View
     */
    public function withdraw()
    {
        $accounts = WithdrawAccount::where('user_id', \Auth::id())->get();
        $accounts = $accounts->reject(function ($value, $key) {
            return !$value->method->status;
        });
        $forexAccounts = ForexAccount::with('schema')->traderType()
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend::withdraw.now', compact('accounts', 'forexAccounts'));
    }

    public function withdrawLog()
    {
        $withdraws = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->where('type', TxnType::Withdraw)
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                });
        })->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::withdraw.log', compact('withdraws'));
    }
    public function export(Request $request)
    {
      return Excel::download(new WithdrawHistoryExport($request), 'Withdraw-History.xlsx');
    }
}
