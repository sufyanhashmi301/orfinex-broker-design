<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\DepositMethod;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Txn;
use Validator;

class DepositController extends GatewayController
{
    use ImageUpload, NotifyTrait,ForexApiTrait;

    public function deposit()
    {

        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }

        $isStepOne = 'current';
        $isStepTwo = '';
        $gateways = DepositMethod::where('status', 1)->get();
        $forexAccounts = ForexAccount::with('schema')
            ->where('user_id', auth()->id())
            ->where('account_type', 'real')
            ->where('status', ForexAccountStatus::Ongoing)
            ->orderBy('id', 'desc')
            ->get();

//        dd($forexAccounts);

        return view('frontend::deposit.now', compact('isStepOne', 'isStepTwo', 'gateways', 'forexAccounts'));
    }

    public function depositNow(Request $request)
    {

        if (!setting('user_deposit', 'permission') || !\Auth::user()->deposit_status) {
            abort('403', 'Deposit Disable Now');
        }

        $validator = Validator::make($request->all(), [
            'target_id' => 'required',
            'gateway_code' => 'required',
            'amount' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ],[
            'target_id.required'=>__('Kindly select Forex Account for deposit')
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), 'Error');

            return redirect()->back();
        }

        $input = $request->all();
//        dd($input);

        $gatewayInfo = DepositMethod::code($input['gateway_code'])->first();
        $amount = $input['amount'];

        if ($amount < $gatewayInfo->minimum_deposit || $amount > $gatewayInfo->maximum_deposit) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = 'Please Deposit the Amount within the range ' . $currencySymbol . $gatewayInfo->minimum_deposit . ' to ' . $currencySymbol . $gatewayInfo->maximum_deposit;
            notify()->error($message, 'Error');
            return redirect()->back();
        }

//        dd($input);
        $targetId = $input['target_id'];
        $targetType = 'forex_deposit';
        $forexAccount = ForexAccount::where('login',$targetId)->first();
//        $targetId = 124234234;
         $this->isValidForexAccount($targetId);

        if (!$forexAccount->first_min_deposit_paid) {
            if ($amount < $forexAccount->schema->first_min_deposit) {
                $currencySymbol = setting('currency_symbol', 'global');
                $message = 'Please Deposit the first Minimum Amount of ' . $currencySymbol . $forexAccount->schema->first_min_deposit;
                notify()->error($message, 'Error');
                return redirect()->back();
            }
        }
        dd('ss');

        $charge = $gatewayInfo->charge_type == 'percentage' ? (($gatewayInfo->charge / 100) * $amount) : $gatewayInfo->charge;
        $finalAmount = (float)$amount + (float)$charge;
        $payAmount = $finalAmount * $gatewayInfo->rate;
        $depositType = TxnType::Deposit;

        if (isset($input['manual_data'])) {

            $depositType = TxnType::ManualDeposit;
            $manualData = $input['manual_data'];

            foreach ($manualData as $key => $value) {

                if (is_file($value)) {
                    $manualData[$key] = self::imageUploadTrait($value);
                }
            }

        }


//        $targetId = '1063794';
//        $targetType = 'forex_deposit';
        $txnInfo = Txn::new($input['amount'], $charge, $finalAmount, $gatewayInfo->gateway_code, 'Deposit With ' . $gatewayInfo->name, $depositType, TxnStatus::Pending, $gatewayInfo->currency, $payAmount, auth()->id(), null, 'User', $manualData ?? [], 'none', $targetId, $targetType);

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
