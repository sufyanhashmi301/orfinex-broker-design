<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\TxnTargetType;
use App\Models\Account;
use App\Models\DepositMethod;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Models\DepositVoucher;
use App\Rules\ForexLoginBelongsToUser;
use App\Rules\ForexLoginBelongsToUserForDemo;
use App\Services\ForexApiService;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Exports\DepositsHistoryExport;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

use Txn;
use Validator;

class DepositController extends GatewayController
{
    use ImageUpload, NotifyTrait, ForexApiTrait;
    protected $forexApiService;

    public function __construct(ForexApiService $forexApiService)
    {
        $this->forexApiService = $forexApiService;
    }
    public function depositMethods()
    {
        $gateways = DepositMethod::where('status', 1)
            ->where(function($query) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All');
            })->get();

        // Check if request deposit accounts is enabled
        $isRequestDepositMode = setting('deposit_account_mode', 'features') === 'request_deposit_accounts';
        $approvedRequest = null;
        
        if ($isRequestDepositMode) {
            $approvedRequest = \App\Models\PaymentDepositRequest::forUser(auth()->id())
                ->approved()
                ->first();
        }

        // Add validation flags to each gateway
        $gateways->each(function ($gateway) use ($isRequestDepositMode, $approvedRequest) {
            $gateway->requires_payment_deposit = false;
            $gateway->is_accessible = true;
            
            // Check if this is a manual method with custom bank details enabled
            if ($gateway->type == \App\Enums\GatewayType::Manual->value && 
                $isRequestDepositMode && 
                $gateway->is_custom_bank_details) {
                
                $gateway->requires_payment_deposit = true;
                
                // If user doesn't have approved request, mark as not accessible
                if (!$approvedRequest) {
                    $gateway->is_accessible = false;
                }
            }
        });

        return view('frontend::deposit.deposit-methods', compact('gateways', 'isRequestDepositMode', 'approvedRequest'));
    }
    public function deposit()
    {
        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', __('Deposit Disable Now'));
        }

        $gatewayCode = request()->get('gateway_code', '');
        $gatewayCode = get_hash($gatewayCode);

        if (!$gatewayCode){
            notify()->error('Please select a valid deposit method');
            return redirect()->back();
        }

        // Check if the selected gateway is manual type and if request deposit accounts is enabled
        $gateway = DepositMethod::code($gatewayCode)->first();
        if ($gateway && $gateway->type == \App\Enums\GatewayType::Manual->value) {
            if (setting('deposit_account_mode', 'features') === 'request_deposit_accounts') {
                // Only check for payment deposit request if this method has custom bank details enabled
                if ($gateway->is_custom_bank_details) {
                    $user = auth()->user();
                    $approvedRequest = \App\Models\PaymentDepositRequest::forUser($user->id)
                        ->approved()
                        ->first();
                    
                    if (!$approvedRequest) {
                        notify()->info('Please submit a payment deposit request first to get your custom bank details for this method.');
                        return redirect()->route('user.payment-deposit');
                    }
                }
            }
        }

        $isStepOne = 'current';
        $isStepTwo = '';
        $forexAccounts = ForexAccount::with('schema')->traderType()
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();

        // Get approved payment deposit request for bank details (if applicable)
        $approvedRequest = null;
        if (setting('deposit_account_mode', 'features') === 'request_deposit_accounts') {
            $approvedRequest = \App\Models\PaymentDepositRequest::forUser(auth()->id())
                ->approved()
                ->first();
        }

        return view('frontend::deposit.now', compact('isStepOne', 'isStepTwo', 'forexAccounts', 'gatewayCode', 'approvedRequest'));
    }

    public function depositNow(Request $request)
    {
        try {
            DB::beginTransaction();
            if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
                abort('403', __('Deposit Disabled Now'));
            }
            if (!setting('deposit_amount', 'kyc_permissions') && auth()->user()->kyc < kyc_required_completed_level())  {
                    notify()->error('KYC Pending: Please complete your KYC verification to proceed with your deposit', __('Error'));
                return redirect()->route('user.kyc');
                }
            // Validate request input
            $validator = Validator::make($request->all(), [
                'target_id' => ['required'], // Removed integer validation because wallet id and forex login are not the same type
                'gateway_code' => 'required',
                'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
            ], [
                'target_id.required' => __('Kindly select an account for deposit'),
            ]);

            if ($validator->fails()) {
                notify()->error($validator->errors()->first(),  __('Error'));
                return redirect()->back();
            }
            $user = \Auth::user();

            // Check pending deposit request limits
            if (Transaction::where('user_id',$user->id)->where('type',TxnType::ManualDeposit)->where('status',TxnStatus::Pending)->count() > setting('pending_deposit_limit', 'deposit_settings')) {
                $currencySymbol = setting('currency_symbol', 'global');
                $message = __('You already have a pending deposit request. Please contact our support team at :support to resolve this issue and proceed with further deposits.', ['support' => setting('support_email', 'common_settings')]);
                notify()->error($message, 'Error');
                return redirect()->back();
            }
            // Check deposit amount against the gateway's limits
            if (!isset($user->country)) {
                $message = __('Kindly choose the country from your profile for proceed to payment!');
                notify()->error($message, __('Error'));
                return redirect()->back();
            }
            $input = $request->all();
            $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
            $amount = $input['amount'];
    //        dd($amount);

            // Check deposit amount against the gateway's limits
            if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
                $currencySymbol = setting('currency_symbol', 'global');
                $message = __('Please deposit the amount within the range ') . $currencySymbol . $gatewayInfo->minimum_deposit . __(' to ')  . $currencySymbol . $gatewayInfo->maximum_deposit;
                notify()->error($message,  __('Error'));
                return redirect()->back();
            }

            // Determine whether it's a forex account or a wallet
            $targetId = get_hash($input['target_id']);
            $targetType = TxnTargetType::ForexDeposit->value; // Default to forex_deposit

            // Check if the selected target is a forex account
            $forexAccount = ForexAccount::where('login', $targetId)->first();
        //    dd('s');

            if ($forexAccount) {
                // It's a Forex account, handle the Forex-specific validation
                if (isset($forexAccount->schema->first_min_deposit) && $forexAccount->schema->first_min_deposit > 0) {
                    if (!$forexAccount->first_min_deposit_paid && $amount < $forexAccount->schema->first_min_deposit) {
                        $currencySymbol = setting('currency_symbol', 'global');
                        $message =  __('Please deposit the first minimum amount of ') . $currencySymbol . $forexAccount->schema->first_min_deposit;
                        notify()->error($message, __('Error'));
                        return redirect()->back();
                    }
                }
            } else {
                // If it's not a Forex account, it must be a wallet, so change target type to wallet
                $account = get_user_account_by_wallet_id($targetId,$user->id);
                if ($account) {
                    $targetType = TxnTargetType::Wallet->value;
                } else {
                    notify()->error(__('The selected account does not exist'), __('Error'));
                    return redirect()->back();
                }
            }
            // Proceed with transaction
            $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
            $finalAmount = (float)$amount + (float)$charge;
            $payAmount = $finalAmount * $gatewayInfo->rate;
            $depositType = TxnType::Deposit;

            if (isset($input['manual_data'])) {
                $depositType = TxnType::ManualDeposit;
                $manualData = $input['manual_data'];

                foreach ($manualData as $key => $value) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid() && is_file($value)) {
                        $manualData[$key] = self::depositImageUploadTrait($value);
                    }
                }
            }

            // Create transaction with the appropriate target_id and target_type
            $txnInfo = Txn::new(
                $input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code,
                __('Deposit With ') . $gatewayInfo->name, $depositType, TxnStatus::Pending,
                $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User',
                $manualData ?? [], 'none', $targetId, $targetType
            );

            DB::commit();

            if ($gatewayInfo->type == 'manual') {
                $shortcodes = [
                    '[[full_name]]' => $txnInfo->user->full_name,
                    '[[txn]]' => $txnInfo->tnx,
                    '[[gateway_name]]' => $txnInfo->method,
                    '[[deposit_amount]]' => $txnInfo->amount,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                    '[[message]]' => $txnInfo->approval_cause,
                    '[[status]]' =>  'Pending',
                ];
                $this->mailNotify($txnInfo->user->email, 'user_manual_deposit_request', $shortcodes);
                $this->mailNotify(setting('site_email', 'global'), 'manual_deposit_request', $shortcodes);
                $this->pushNotify('manual_deposit_request', $shortcodes, route('user.deposit.log'), $user->id);

            }
            return self::depositAutoGateway($gatewayInfo->gateway_code, $txnInfo);
        } catch (Exception $e) {
            DB::rollBack();
            notify()->error(__('An error occurred while processing your deposit. Please try again later.'), 'Error');
            return redirect()->back()->withInput();
        }
    }


    public function depositDemoNow(Request $request)
    {
        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', __('Deposit Disable Now'));
        }
        $request->validate([
            'target_id' => ['required','integer', new ForexLoginBelongsToUserForDemo,
                Rule::exists('forex_accounts', 'login')->where(function ($query) {
                    $query->where('account_type', 'demo');
                })],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/','numeric','min:1','max:100000'],
        ], [
            'target_id.required' => __('Kindly select Account for deposit'),
            'target_id.exists' => __('The selected account does not exist or is not of type demo.'),
        ]);

        $input = $request->all();
        $amount = $input['amount'];
//
//        if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
//            $currencySymbol = setting('currency_symbol', 'global');
//            $message = 'Please Deposit the Amount within the range ' . $currencySymbol . $gatewayInfo->minimum_deposit . ' to ' . $currencySymbol . $gatewayInfo->maximum_deposit;
//            notify()->error($message, 'Error');
//            return redirect()->back();
//        }
//        dd($input);
        $targetId = $input['target_id'];
        $targetType = 'forex_deposit_demo';

        $clientIp = request()->ip();
//        if(!in_array($clientIp,['127.0.0.1' , '::1'])) {
//           dd($isValid);
        $response = $this->forexApiService->getUserByLogin([
            'login' => $targetId
        ]);
        if(!$response['success']){
            return response()->json(['error' => __('Your account has been disabled. You are currently not permitted to access the platform. please contact: ').setting('support_email', 'global'), 'reload' => false]);

        }

//        }
        $charge = 0;
        $finalAmount = (float)$amount + (float)$charge;
        $payAmount = $finalAmount;
        $depositType = TxnType::DemoDeposit;

        $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, 'Demo-Deposit', 'Demo Deposit of '.$targetId  , $depositType, TxnStatus::Pending, base_currency(), $payAmount, auth()->id(), null, 'User', $manualData ?? [], 'none', $targetId, $targetType);
        $comment = 'demo/deposit/'.substr($txnInfo->tnx, -7);
        $data = [
            'login' => $targetId,
            'Amount' => $finalAmount,
            'type' => 1,//deposit
            'TransactionComments' => $comment
        ];
        $depositResponse = $this->forexApiService->balanceOperationDemo($data);
        if ($depositResponse['success']) {
            Txn::update($txnInfo->tnx, TxnStatus::Success, $txnInfo->user_id, 'System');
            return response()->json(['success' => __('Successfully Deposited.'), 'reload' => true]);
        } else {
            Txn::update($txnInfo->tnx, TxnStatus::Failed, $txnInfo->user_id, 'System');
            return response()->json(['error' => __('Opps! We unable to process your request. Please reload the page and try again.'), 'reload' => true]);
        }

    }

    public function depositLog()
    {
        $deposits = Transaction::search(request('query'), function ($query) {
            $query->where('user_id', auth()->user()->id)
                ->where('status', '!=', \App\Enums\TxnStatus::None) // Exclude none status
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                })
                ->whereIn('type', [TxnType::Deposit, TxnType::ManualDeposit]);
        })->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::deposit.log', compact('deposits'));
    }

    public function export(Request $request)
    {
        return Excel::download(new DepositsHistoryExport($request), 'deposit-History.xlsx');
    }

    public function redeemVoucher()
    {

        $gatewayCode = request()->get('gateway_code', '');
        $gatewayCode = get_hash($gatewayCode);

        if (!$gatewayCode){
            notify()->error('Please select a valid deposit method');
            return redirect()->back();
        }

        $isStepOne = 'current';
        $isStepTwo = '';
        $forexAccounts = ForexAccount::with('schema')->traderType()
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();

        return view('frontend::deposit.voucher.now', compact('isStepOne', 'isStepTwo', 'forexAccounts', 'gatewayCode'));

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
            'code' => $voucher->code,
            'title' => $voucher->title,
            'expires_at' => $voucher->expiry_date->format('Y-m-d H:i'),
            'description' => $voucher->description
        ]);
    }

    public function redeemNow(Request $request)
    {
        try {
            DB::beginTransaction();
        
            $validator = Validator::make($request->all(), [
                'target_id' => ['required'],
                'code' => 'required',
            ], [
                'target_id.required' => __('Kindly select an account for deposit'),
            ]);

            if ($validator->fails()) {
                notify()->error($validator->errors()->first(),  __('Error'));
                return redirect()->back();
            }

            $voucher = DepositVoucher::where('code', $request->code)->first();

            if (!$voucher) {
                notify()->error(__('Invalid voucher code.'), __('Error'));
                return redirect()->back();
            }

            if ($voucher->status !== 'active') {
                notify()->error(__('Voucher is not active.'), __('Error'));
                return redirect()->back();
            }

            if ($voucher->expiry_date < now()) {
                notify()->error(__('Voucher has expired.'), __('Error'));
                return redirect()->back();
            }

            $user = \Auth::user();

            $input = $request->all();
            $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
            $amount = $voucher->amount;

            // Determine whether it's a forex account or a wallet
            $targetId = get_hash($input['target_id']);
            $targetType = TxnTargetType::ForexDeposit->value; // Default to forex_deposit

            // Check if the selected target is a forex account
            $forexAccount = ForexAccount::where('login', $targetId)->first();

            if ($forexAccount) {
                // It's a Forex account, handle the Forex-specific validation
                if (isset($forexAccount->schema->first_min_deposit) && $forexAccount->schema->first_min_deposit > 0) {
                    if (!$forexAccount->first_min_deposit_paid && $amount < $forexAccount->schema->first_min_deposit) {
                        $currencySymbol = setting('currency_symbol', 'global');
                        $message =  __('Please deposit the first minimum amount of ') . $currencySymbol . $forexAccount->schema->first_min_deposit;
                        notify()->error($message, __('Error'));
                        return redirect()->back();
                    }
                }
            } else {
                // If it's not a Forex account, it must be a wallet, so change target type to wallet
                $account = get_user_account_by_wallet_id($targetId,$user->id);
                if ($account) {
                    $targetType = TxnTargetType::Wallet->value;
                } else {
                    notify()->error(__('The selected account does not exist'), __('Error'));
                    return redirect()->back();
                }
            }
            // Proceed with transaction
            $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
            $finalAmount = (float)$amount - (float)$charge;
            $payAmount = $finalAmount * $gatewayInfo->rate;
            $depositType = TxnType::VoucherDeposit;

            if (isset($input['manual_data'])) {
                $depositType = TxnType::VoucherDeposit;
                $manualData = $input['manual_data'];
        
                foreach ($manualData as $key => $value) {
                    if ($value instanceof \Illuminate\Http\UploadedFile && $value->isValid() && is_file($value)) {
                        $manualData[$key] = self::depositImageUploadTrait($value);
                    }
                }
            }

            // Create transaction with the appropriate target_id and target_type
            $txnInfo = Txn::new(
                $finalAmount, $charge, $voucher->amount, $gatewayInfo->gateway_code,
                __('Deposit With ') . $gatewayInfo->name, $depositType, TxnStatus::Pending,
                $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User',
                $manualData ?? [], 'none', $targetId, $targetType
            );

            // Auto-approve immediately
            $approved = $this->approveVoucherTransaction($txnInfo, 'Auto-approved on redeem');

            if (!$approved) {
                DB::rollBack();
                notify()->error(__('Transaction update failed after redeem.'));
                return redirect()->back();
            }

            DB::commit();
            return self::notifyOnVoucherRedeem($txnInfo);


        } catch (Exception $e) {
            DB::rollBack();
            notify()->error(__('An error occurred while processing your deposit. Please try again later.'), 'Error');
            return redirect()->back()->withInput();
        }
    }

    private function approveVoucherTransaction(Transaction $transaction, string $approvalCause = 'Auto-approved via voucher redemption')
    {
        try {
            if ($transaction->status === TxnStatus::Success) {
                return true; // Already processed
            }

            // Update approval cause
            $transaction->approval_cause = $approvalCause;
            $transaction->save();

            // Update transaction status to Success
            $updated = Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id, $approvalCause);

            if (!$updated) {
                return false;
            }

            $manualData = is_array($transaction->manual_field_data)
                ? $transaction->manual_field_data
                : json_decode($transaction->manual_field_data, true);

            $voucherCode = $manualData['voucher code'] ?? null;

            // Update the voucher as used
            $voucher = DepositVoucher::where('code', $voucherCode)->first();
            
            if ($voucher && $voucher->status !== 'used') {
                $voucher->update([
                    'status' => 'used',
                    'used_by' => $transaction->user_id,
                    'used_date' => now(),
                    'modal' => $transaction->from_model
                ]);
            }

            // Optional: Send notification
            $shortcodes = [
                '[[full_name]]' => $transaction->user->full_name,
                '[[txn]]' => $transaction->tnx,
                '[[gateway_name]]' => 'Voucher',
                '[[deposit_amount]]' => $transaction->amount,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[message]]' => $approvalCause,
                '[[status]]' => 'approved',
            ];

            $this->mailNotify($transaction->user->email, 'user_manual_deposit_approve', $shortcodes);
            $this->pushNotify('user_manual_deposit_request', $shortcodes, route('user.history.transactions'), $transaction->user_id, 'deposit');

            return true;

        } catch (\Exception $e) {
            Log::error('Voucher approval failed: ' . $e->getMessage());
            return false;
        }
    }
}
