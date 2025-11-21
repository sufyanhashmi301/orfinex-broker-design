<?php

namespace App\Traits;

use App\Enums\InvestStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\DepositMethod;
use App\Models\Invest;
use App\Models\LevelReferral;
use App\Models\Transaction;
use App\Services\NotificationService;
use charlesassets\LaravelPerfectMoney\PerfectMoney;
use Exception;
use Illuminate\Support\Facades\Log;
use Payment\Binance\BinanceTxn;
use Payment\Blockchain\BlockchainTxn;
use Payment\BlockIo\BlockIoTxn;
use Payment\Bridgerpay\BridgerpayTxn;
use Payment\Btcpayserver\BtcpayserverTxn;
use Payment\Cashmaal\CashmaalTxn;
use Payment\Coinbase\CoinbaseTxn;
use Payment\Coingate\CoingateTxn;
use Payment\Coinpayments\CoinpaymentsTxn;
use Payment\Coinremitter\CoinremitterTxn;
use Payment\Cryptomus\CryptomusTxn;
use Payment\Flutterwave\FlutterwaveTxn;
use Payment\Instamojo\InstamojoTxn;
use Payment\Jenapay\JenapayTxn;
use Payment\Match2pay\Match2payTxn;
use Payment\Mollie\MollieTxn;
use Payment\Monnify\MonnifyTxn;
use Payment\Nowpayments\NowpaymentsTxn;
use Payment\Paymongo\PaymongoTxn;
use Payment\Paypal\PaypalTxn;
use Payment\Paytm\PaytmTxn;
use Payment\Perfectmoney\PerfectmoneyTxn;
use Payment\Razorpay\RazorpayTxn;
use Payment\Securionpay\SecurionpayTxn;
use Payment\Stripe\StripeTxn;
use Payment\Twocheckout\TwocheckoutTxn;
use Payment\Uniwire\UniwireTxn;
use Session;
use Txn;
use URL;

trait Payment
{
    //automatic deposit gateway snippet
    protected function depositAutoGateway($gateway, $txnInfo)
    {
        $txn = $txnInfo->tnx;
        Session::put('deposit_tnx', $txn);
        $gateway = DepositMethod::code($gateway)->first()->gateway->gateway_code ?? 'none';
    //    dd($gateway);

        $gatewayTxn = self::gatewayMap($gateway, $txnInfo);
    //    dd($txnInfo,$gatewayTxn);
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
            'title' => $symbol.$txnInfo->amount.' Withdraw Request Successful',
            'p' => 'The Withdraw Request has been successfully sent',
            'strong' => 'Transaction ID: '.$txnInfo->tnx,
            'action' => route('user.withdraw.view'),
            'a' => 'WITHDRAW REQUEST AGAIN',
            'view_name' => 'withdraw',
        ];
        Session::put('user_notify', $notify);

        // Send notifications for auto withdrawal initiation
        try {
            $notificationService = app(NotificationService::class);
            
            // Refresh transaction to get latest status
            $txnInfo->refresh();
            
            // Send user notification for initiation
            $notificationService->transactionStatus($txnInfo, 'pending');
            
            // Send admin and staff notifications (merged - includes all admin and staff emails)
            $notificationService->adminTransactionAlert($txnInfo);
        } catch (\Throwable $e) {
            // Log error but don't break withdrawal flow
            Log::error('Auto withdrawal initiation notification failed', [
                'transaction_tnx' => $txnInfo->tnx,
                'error' => $e->getMessage(),
            ]);
        }

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
            'card-header' => "$status Your Deposit Process",
            'title' => "$symbol $tnxInfo->amount Deposit $title",
            'p' => "The amount has been $title added into your account",
            'strong' => 'Transaction ID: '.$tnx,
            'action' => route('user.deposit.methods'),
            'a' => 'Deposit again',
            'view_name' => 'deposit',
        ];

        if ($status == 'Pending') {
            // Send centralized notifications for auto deposit pending
            try {
                $notificationService = app(NotificationService::class);
                
                // Send user notification
                $notificationService->transactionStatus($tnxInfo, 'pending');
                
                // Send admin and staff notifications (merged - includes all admin and staff emails)
                $notificationService->adminTransactionAlert($tnxInfo);
            } catch (\Throwable $e) {
                // Log error but don't break payment flow
                Log::error('Auto deposit pending notification failed', [
                    'transaction_tnx' => $tnx,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $isStepOne = 'current';
        $isStepTwo = 'current';
        Session::put('user_notify', $notify);
        return redirect()->route('user.notify');

    }

    //automatic payment success snippet
    protected function paymentSuccess($ref, $isRedirect = true)
    {
        $txnInfo = Transaction::tnx($ref);

        if ($txnInfo->status == TxnStatus::Success) {
            return false;
        }

//            $txnInfo->update([
//                'status' => TxnStatus::Success,
//            ]);
            Txn::update($ref, TxnStatus::Success, $txnInfo->user_id);

//            if (setting('site_referral', 'global') == 'level' && setting('deposit_level')) {
//                $level = LevelReferral::where('type', 'deposit')->max('the_order') + 1;
//                creditReferralBonus($txnInfo->user, 'deposit', $txnInfo->amount, $level);
//            }

            // Send notifications AFTER transaction update completes successfully
            try {
                $notificationService = app(NotificationService::class);
                
                // Refresh transaction to get updated status
                $txnInfo->refresh();
                
                // Send user notification
                $notificationService->transactionStatus($txnInfo, 'success');
                
                // Send admin and staff notifications (merged - includes all admin and staff emails)
                $notificationService->adminTransactionAlert($txnInfo);
            } catch (\Throwable $e) {
                // Log error but don't break transaction flow
                Log::error('Auto deposit success notification failed', [
                    'transaction_tnx' => $ref,
                    'error' => $e->getMessage(),
                ]);
            }

            if ($isRedirect) {
                return redirect(URL::temporarySignedRoute(
                    'status.success', now()->addMinutes(2)
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
            'uniwire' => UniwireTxn::class,
            'jenapay' => JenapayTxn::class,

        ];
// dd($gateway,$gatewayMap,$txnInfo);
        if (array_key_exists($gateway, $gatewayMap)) {
            return app($gatewayMap[$gateway], ['txnInfo' => $txnInfo]);
        }

        return false;

    }

    protected function notifyOnVoucherRedeem(Transaction $txn)
    {
        $status = ucfirst($txn->status->value);
        $symbol = setting('currency_symbol', 'global');

        $titleText = match (strtolower($status)) {
            'success' => 'Successfully',
            'pending' => 'Pending',
            default   => ucfirst($status)
        };

        $notify = [
            'card-header' => "$status - Voucher Deposit",
            'title'       => "$symbol {$txn->amount} Deposit $titleText",
            'p'           => "The amount has been $titleText added to your account using a voucher.",
            'strong'      => 'Transaction ID: ' . $txn->tnx,
            'action'      => route('user.history.transactions'),
            'a'           => 'View Transactions',
            'view_name'   => 'deposit',
        ];

        $isStepOne = 'current';
        $isStepTwo = 'current';
        Session::put('user_notify', $notify);
        return redirect()->route('user.notify');
    }

}
