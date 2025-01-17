<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Enums\PricingInvestmentStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\AccountTypeInvestment;
use App\Models\DepositMethod;
use App\Models\ForexAccount;
use App\Models\ForexSchemaPhaseRule;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Rules\ForexLoginBelongsToUser;
use App\Rules\ForexLoginBelongsToUserForDemo;
use App\Services\ForexApiService;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

    public function deposit(Request $request, $id = null)
    {

    //    dd($request->all());
        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }

        $isStepOne = 'current';
        $isStepTwo = '';
        $gateways = DepositMethod::where('status', 1)
            ->where(function ($query) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All');
            })->get();

//        dd($gateways);

//        $clientIp = request()->ip();
//        if (!in_array($clientIp, ['127.0.0.1', '::1'])) {
//            $this->syncForexAccounts(auth()->id());
//        }
        $forexAccounts = ForexAccount::with('schema')
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();

        $investment = AccountTypeInvestment::where('id', $request->investment)->firstorFail();

//        dd($rule);

        return view('frontend::deposit.now', compact('isStepOne', 'isStepTwo', 'gateways', 'forexAccounts', 'investment'));
    }

    public function depositNow(Request $request)
    {

//        dd($request->all());
        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }

        $validator = Validator::make($request->all(), [
            'target_id' => ['required'],
            'gateway_code' => 'required',
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
        ], [
            'target_id.required' => __('Kindly select Account for deposit'),
            'target_id.exists' => 'The selected account does not exist or is not of type real.',

        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
//        dd($input);

        $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
        $amount = $input['amount'];


//        dd($input);
        $targetId = get_hash($input['target_id']);
        $targetType = 'forex_deposit';
//        $forexAccount = ForexAccount::where('login', $targetId)->first();
//        $targetId = 124234234;


        $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
        $finalAmount = (float)$amount + (float)$charge;
        $payAmount = $finalAmount * $gatewayInfo->rate;
        $depositType = TxnType::Deposit;

        if (isset($input['manual_data'])) {

            $depositType = TxnType::ManualDeposit;
            $manualData = $input['manual_data'];

            foreach ($manualData as $key => $value) {

                if (is_file($value)) {
                    $manualData[$key] = self::depositImageUploadTrait($value);
                }
            }

        }


//        $targetId = '1063794';
//        $targetType = 'forex_deposit';
        $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code, 'Payment With ' . $gatewayInfo->name, $depositType, TxnStatus::Pending, $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User', $manualData ?? [], 'none', $targetId, $targetType);

        // Update Invoice
        $invoice = Invoice::where('account_type_investment_id', $targetId)->first();
        $invoice->transaction_id = $txnInfo->id;
        $invoice->transaction_id_string = $txnInfo->tnx;
        $invoice->save();

        // send email if the payment is pending
        if ($gatewayInfo->type == 'manual') {
            $shortcodes = [
                '[[full_name]]' => $txnInfo->user->full_name,
                '[[transaction_id]]' => $txnInfo->tnx,
                '[[amount]]' => $txnInfo->pay_amount . $txnInfo->pay_currency,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];
           $this->mailNotify($txnInfo->user->email, 'payment_pending', $shortcodes);

        }

        return self::depositAutoGateway($gatewayInfo->gateway_code, $txnInfo);

    }

        public function depositDemoNow(Request $request)
    {
        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }
        $request->validate([
            'target_id' => ['required', 'integer', new ForexLoginBelongsToUserForDemo,
                Rule::exists('forex_accounts', 'login')->where(function ($query) {
                    $query->where('account_type', 'demo');
                })],
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/', 'numeric', 'min:1', 'max:100000'],
        ], [
            'target_id.required' => __('Kindly select Account for deposit'),
            'target_id.exists' => 'The selected account does not exist or is not of type demo.',
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
        if (!$response['success']) {
            return response()->json(['error' => __('Your Account is Deactivated, please contact: ' . setting('support_email', 'global')), 'reload' => false]);

        }

//        }
        $charge = 0;
        $finalAmount = (float)$amount + (float)$charge;
        $payAmount = $finalAmount;
        $depositType = TxnType::DemoDeposit;

        $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, 'Demo-Deposit', 'Demo Deposit of ' . $targetId, $depositType, TxnStatus::Pending, 'USD', $payAmount, auth()->id(), null, 'User', $manualData ?? [], 'none', $targetId, $targetType);
        $comment = 'demo/deposit/' . substr($txnInfo->tnx, -7);
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
                ->when(request('date'), function ($query) {
                    $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                })
                ->whereIn('type', [TxnType::Deposit, TxnType::ManualDeposit]);
        })->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('frontend::deposit.log', compact('deposits'));
    }
}
