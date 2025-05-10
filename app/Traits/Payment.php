<?php

namespace App\Traits;

use Txn;
use URL;
use Session;
use Exception;
use App\Enums\TxnType;
use App\Models\Invest;
use App\Enums\TxnStatus;
use App\Enums\InvestStatus;
use App\Models\Transaction;
use Payment\Paytm\PaytmTxn;
use App\Models\DepositMethod;
use App\Models\LevelReferral;
use Payment\Mollie\MollieTxn;
use Payment\Paypal\PaypalTxn;
use Payment\Stripe\StripeTxn;
use Payment\Binance\BinanceTxn;
use Payment\BlockIo\BlockIoTxn;
use Payment\Monnify\MonnifyTxn;
use Payment\Cashmaal\CashmaalTxn;
use Payment\Coinbase\CoinbaseTxn;
use Payment\Coingate\CoingateTxn;
use Payment\Paymongo\PaymongoTxn;
use Payment\Razorpay\RazorpayTxn;
use Payment\Cryptomus\CryptomusTxn;
use Payment\Instamojo\InstamojoTxn;
use Payment\Match2pay\Match2payTxn;
use Payment\Blockchain\BlockchainTxn;
use Payment\Bridgerpay\BridgerpayTxn;
use Payment\Flutterwave\FlutterwaveTxn;
use Payment\Nowpayments\NowpaymentsTxn;
use Payment\Securionpay\SecurionpayTxn;
use Payment\Twocheckout\TwocheckoutTxn;
use App\Services\AccountActivityService;
use App\Enums\AccountActivityStatusEnums;
use Payment\Btcpayserver\BtcpayserverTxn;
use Payment\Coinpayments\CoinpaymentsTxn;
use Payment\Coinremitter\CoinremitterTxn;
use Payment\Perfectmoney\PerfectmoneyTxn;
use App\Services\ForexSchemaInvestormService;
use charlesassets\LaravelPerfectMoney\PerfectMoney;
use App\Services\AccountTypeInvestmentPaymentService;

trait Payment
{

    private $investment_payment;


    public function __construct(AccountTypeInvestmentPaymentService $investment_payment)
    {
        $this->investment_payment = $investment_payment;
    }

    //automatic deposit gateway snippet
    protected function depositAutoGateway($gateway, $txnInfo)
    {
        $txn = $txnInfo->tnx;
        Session::put('deposit_tnx', $txn);
        $gateway = DepositMethod::code($gateway)->first()->gateway->gateway_code ?? 'none';
        //        dd($gateway);

        $gatewayTxn = self::gatewayMap($gateway, $txnInfo);
        //        dd($txnInfo,$gatewayTxn);
        if ($gatewayTxn) {
            return $gatewayTxn->deposit();
        }

        return self::paymentNotify($txn, 'pending');
    }

    //automatic withdraw gateway snippet
    protected function withdrawAutoGateway($gatewayCode, $txnInfo)
    {

        $gatewayTxn = self::gatewayMap($gatewayCode, $txnInfo);
        //            dd($gatewayTxn,config('app.demo'));
        if ($gatewayTxn && config('app.demo') == 0) {
            //            dd('demo');
            $gatewayTxn->withdraw();
        }
        //        dd('real');
        $symbol = setting('currency_symbol', 'global');
        $notify = [
            'card-header' => 'Withdraw Money',
            'title' => $symbol . $txnInfo->amount . ' Withdraw Request Successful',
            'p' => 'The Withdraw Request has been successfully sent',
            'strong' => 'Transaction ID: ' . $txnInfo->tnx,
            'action' => route('user.withdraw.view'),
            'a' => 'WITHDRAW REQUEST AGAIN',
            'view_name' => 'withdraw',
        ];
        Session::put('user_notify', $notify);

        return redirect()->route('user.notify');
    }

    //automatic payment notify snippet
    protected function paymentNotify($tnx, $status)
    {

        $tnxInfo = Transaction::tnx($tnx);

        $title = '';
        $investNotifyTitle = '';
        switch ($status) {
            case 'success':
                $title = 'Successfully';
                $investNotifyTitle = 'Successfully Investment';
                break;
            case 'pending':
                $title = 'Pending';
                $investNotifyTitle = 'Successfully Investment Apply';
                break;
        }

        $status = ucfirst($status);
        

        $symbol = setting('currency_symbol', 'global');

        $notify = [
            'card-header' => "$status Your Payment Process",
            'title' => "$symbol $tnxInfo->amount Payment $title",
            'p' => "The amount has been $title added into your account",
            'strong' => 'Transaction ID: ' . $tnx,
            'action' => route('user.deposit.amount'),
            'a' => 'Deposit again',
            'view_name' => 'deposit',
        ];

        if ($status == 'Pending') {
            $shortcodes = [
                '[[full_name]]' => $tnxInfo->user->full_name,
                '[[txn]]' => $tnxInfo->tnx,
                '[[gateway_name]]' => $tnxInfo->method,
                '[[deposit_amount]]' => $tnxInfo->amount,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[message]]' => '',
                '[[status]]' => $status,
            ];
            $this->mailNotify(setting('site_email', 'global'), 'manual_deposit_request', $shortcodes);
            $this->pushNotify('manual_deposit_request', $shortcodes, route('admin.deposit.manual.pending'), $tnxInfo->user->id);
            $this->smsNotify('manual_deposit_request', $shortcodes, $tnxInfo->user->phone);
        }

        $isStepOne = 'current';
        $isStepTwo = 'current';
        Session::put('user_notify', $notify);
        return redirect()->route('user.notify');
    }

    //automatic payment success snippet
    protected function paymentSuccess($ref, $isRedirect = true)
    {
        $transaction = Transaction::tnx($ref);

        if ($transaction->status == TxnStatus::Success) {
            return false;
        }

        $new_account = $this->investment_payment->investmentActive($transaction->target_id);
        AccountActivityService::log($new_account, AccountActivityStatusEnums::ACTIVE);
        Txn::update($ref, TxnStatus::Success, $transaction->user_id);

        // $investment = new ForexSchemaInvestormService();
        // $investment->approveInvestment($txnInfo->target_id);


        if ($isRedirect) {
            notify()->success('Payment Successful');
            return redirect(URL::temporarySignedRoute(
                'user.investments.index', now()->addMinutes(2)
            ));
        }

    }

    //automatic gateway map snippet
    private function gatewayMap($gateway, $txnInfo)
    {
        $gatewayMap = [
            'paypal' => PaypalTxn::class,
            'stripe' => StripeTxn::class,
            'mollie' => MollieTxn::class,
            'perfectmoney' => PerfectmoneyTxn::class,
            'coinbase' => CoinbaseTxn::class,
            'paystack' => PaytmTxn::class,
            'voguepay' => BinanceTxn::class,
            'flutterwave' => FlutterwaveTxn::class,
            'cryptomus' => CryptomusTxn::class,
            'nowpayments' => NowpaymentsTxn::class,
            'securionpay' => SecurionpayTxn::class,
            'coingate' => CoingateTxn::class,
            'monnify' => MonnifyTxn::class,
            'coinpayments' => CoinpaymentsTxn::class,
            'paymongo' => PaymongoTxn::class,
            'coinremitter' => CoinremitterTxn::class,
            'btcpayserver' => BtcpayserverTxn::class,
            'binance' => BinanceTxn::class,
            'cashmaal' => CashmaalTxn::class,
            'blockio' => BlockIoTxn::class,
            'blockchain' => BlockchainTxn::class,
            'instamojo' => InstamojoTxn::class,
            'paytm' => PaytmTxn::class,
            'razorpay' => RazorpayTxn::class,
            'twocheckout' => TwocheckoutTxn::class,
            'bridgerpay' => BridgerpayTxn::class,
            'match2pay' => Match2payTxn::class,
        ];
        //dd($gateway,$gatewayMap,$txnInfo);
        if (array_key_exists($gateway, $gatewayMap)) {
            return app($gatewayMap[$gateway], ['txnInfo' => $txnInfo]);
        }

        return false;
    }
}
