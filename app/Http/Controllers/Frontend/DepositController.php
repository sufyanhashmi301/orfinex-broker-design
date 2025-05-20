<?php

namespace App\Http\Controllers\Frontend;

use Txn;
use URL;
use Validator;
use Carbon\Carbon;
use App\Models\Offer;
use App\Enums\TxnType;
use App\Models\Invoice;
use App\Enums\TxnStatus;
use App\Models\Discount;
use App\Models\UserOffer;
use App\Models\Transaction;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Models\ForexAccount;
use Illuminate\Http\Request;
use App\Models\DepositMethod;
use App\Traits\ForexApiTrait;
use App\Enums\InvestmentStatus;
use Illuminate\Validation\Rule;
use App\Enums\ForexAccountStatus;
use App\Services\ForexApiService;
use App\Models\ForexSchemaPhaseRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\AccountTypeInvestment;
use App\Enums\PricingInvestmentStatus;
use App\Rules\ForexLoginBelongsToUser;
use App\Rules\ForexLoginBelongsToUserForDemo;

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
        if (!setting('user_deposit', 'permission') || !Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }

        $isStepOne = 'current';
        $isStepTwo = '';
        $gateways = DepositMethod::where('status', 1)
            ->where(function ($query) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All');
            })->get();

        $investment = AccountTypeInvestment::where('id', $request->investment)->firstorFail();

        return view('frontend::deposit.now', compact('isStepOne', 'isStepTwo', 'gateways', 'investment'));
    }

    public function depositNow(Request $request)
    {
        if (!setting('user_deposit', 'permission') || !Auth::user()->deposit_status) {
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
                    $manualData[$key] = self::imageUploadTrait($value, null, 'user/payments/' . Auth::id());
                }
            }
        }


        //        $targetId = '1063794';
        //        $targetType = 'forex_deposit';
        $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code, 'Payment With ' . $gatewayInfo->name, $depositType, TxnStatus::Pending, $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User', $manualData ?? [], 'none', $targetId, $targetType);

        // Update the status of the account to pending when paid
        AccountTypeInvestment::where('id', $targetId)->update([
            'status' => InvestmentStatus::PENDING
        ]);

        // Update Invoice
        $invoice = Invoice::where('account_type_investment_id', $targetId)->first();
        $invoice->transaction_id = $txnInfo->id;
        $invoice->transaction_id_string = $txnInfo->tnx;
        $invoice->save();

        // Update Discount
        if(isset($invoice->coupon_code_discount['id'])) {
            $discount = Discount::where('id', $invoice->coupon_code_discount['id'])->first();
            $discount->used_count = $discount->used_count + 1;
            $discount->save();

            // Update User Offers
            if($discount->purpose == 'offers') {
                $offer = Offer::where('discount_id', $discount->id)->first();
                $userOffer = UserOffer::where('user_id', Auth::id())->where('offer_id', $offer->id)->where('status', 'available')->first();
                $userOffer->status = 'used';
                $userOffer->save();
            }
        }
        

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
