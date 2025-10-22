<?php

namespace App\Http\Controllers\Frontend;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Payment;
use Binance\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Modules\Payment\Monnify\Monnify;
use Mollie\Laravel\Facades\Mollie;
use Payment\Securionpay\SecurionpayTxn;
use Payment\Jenapay\JenapayTxn;
use Paystack;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Txn;

class IpnController extends Controller
{
    use Payment;

    public function coinpaymentsIpn(Request $request)
    {
        $input = $request->all();
        $txn = $input['item_name'];
        $status = $input['status'];
        if ($status >= 100 || $status == 2) {
            self::paymentSuccess($txn);
        }
    }

    public function nowpaymentsIpn(Request $request)
    {
        $input = $request->all();
        $txn = $input['order_id'];
        $status = $input['payment_status'];
        if ($status == 'finished') {
            self::paymentSuccess($txn);
        }
    }

    public function bridgerpayIpn(Request $request)
    {
        // Get all the input data from the request
        $input = $request->all();

        // Extract the order ID and webhook type from the request
        $orderId = $input['data']['order_id'] ?? null;
        $webhookType = $input['webhook']['type'] ?? null;
        $txnInfo = Transaction::tnx($orderId);
        $txnInfo->update([
            'approval_cause' => __('received'),
        ]);
        // Check if order_id is present
        if (!$orderId) {
            $txnInfo = Transaction::tnx($orderId);
            $txnInfo->update([
                'approval_cause' =>  __('invalid order ID'),
            ]);
            return redirect()
                ->route('user.deposit.now')
                ->with('error', __('Invalid order ID.'));
        }

        // Handle different webhook types (approved, declined)
        switch ($webhookType) {
            case 'approved':
                // Call the payment success method
                self::paymentSuccess($orderId);

                return redirect()
                    ->route('user.deposit.now')
                    ->with('success', __('Payment approved and processed successfully.'));

            case 'declined':

                $txnInfo = Transaction::tnx($orderId);
                $txnInfo->update([
                'status' => TxnStatus::Failed,
                    'approval_cause' =>  __('invalid declined'),

                ]);
                // Log or handle the declined payment
                $declineReason = $input['data']['charge']['attributes']['decline_reason'] ?? __('Unknown reason');
                return redirect()
                    ->route('user.deposit.now')
                    ->with('error', __("Payment declined. Reason: $declineReason."));

            default:
                $txnInfo = Transaction::tnx($orderId);
                $txnInfo->update([
                    'status' => TxnStatus::Failed,
                    'approval_cause' => __('default declined'),

                ]);
                return redirect()
                    ->route('user.deposit.now')
                    ->with('error', __('Unknown error.'));
                }
    }
    public function match2payIpn(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Match2Pay IPN received', [
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'body' => $request->all(),
                'raw_content' => $request->getContent(),
            ]);

            // Get all the input data from the request
            $input = $request->all();

            // Extract the payment ID from the Match2Pay request
            $paymentId = $input['paymentId'] ?? null;
            $cryptoTransactionInfo = $input['cryptoTransactionInfo'][0] ?? null; // Get the first transaction info

            // Check if the paymentId exists in the request
            if (!$paymentId) {
                Log::error('Match2Pay IPN: Missing payment ID', ['input' => $input]);
                return response()->json(['error' => 'Invalid payment ID'], 400);
            }

            // Find the transaction in the database using the payment ID
            $txnInfo = Transaction::tnx($paymentId);
            
            if (!$txnInfo) {
                Log::error('Match2Pay IPN: Transaction not found', ['paymentId' => $paymentId]);
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Extract relevant fields from the cryptoTransactionInfo
            $status = $input['status'] ?? null;
            $confirmations = $cryptoTransactionInfo['confirmations'] ?? null;
            $txid = $cryptoTransactionInfo['txid'] ?? null;

            // Handle different transaction statuses (PENDING, DONE, FAILED)
            switch ($status) {
                case 'PENDING':
                    // Handle pending payments
                    $txnInfo->update([
                        'status' => TxnStatus::Pending,
                        'manual_field_data' => $input,
                        'approval_cause' => __('Transaction is pending'),
                    ]);
                    
                    Log::info('Match2Pay IPN: Transaction set to pending', ['paymentId' => $paymentId]);
                    return response()->json(['status' => 'success', 'message' => 'Transaction pending']);

                case 'DONE':
                    // Start database transaction to prevent race conditions
                    return DB::transaction(function() use ($txnInfo, $input, $paymentId) {
                        // Check if already processed
                        if ($txnInfo->status == TxnStatus::Success) {
                            Log::info('Match2Pay IPN: Transaction already successful', ['paymentId' => $paymentId]);
                            return response()->json(['status' => 'success', 'message' => 'Already processed']);
                        }

                        $txnInfo->update([
                            'amount' => $input['finalAmount'],
                            'final_amount' => $input['finalAmount'],
                            'pay_amount' => $input['netAmount'],
                            'pay_currency' => $input['transactionCurrency'],
                            'manual_field_data' => $input,
                            'approval_cause' => __('Transaction is Completed'),
                        ]);

                        // Call the payment success method
                        self::paymentSuccess($paymentId);
                        
                        Log::info('Match2Pay IPN: Transaction completed successfully', ['paymentId' => $paymentId]);
                        return response()->json(['status' => 'success', 'message' => 'Payment processed successfully']);
                    });

                case 'DECLINED':
                    // Handle declined payments
                    $declineReason = $cryptoTransactionInfo['decline_reason'] ?? __('Unknown reason');
                    $txnInfo->update([
                        'status' => TxnStatus::Failed,
                        'approval_cause' => __("Transaction declined: $declineReason"),
                    ]);
                    
                    Log::info('Match2Pay IPN: Transaction declined', [
                        'paymentId' => $paymentId, 
                        'reason' => $declineReason
                    ]);
                    return response()->json(['status' => 'declined', 'message' => 'Payment declined']);

                default:
                    // Handle unknown or invalid statuses
                    $txnInfo->update([
                        'status' => TxnStatus::Failed,
                        'approval_cause' => __('Unknown status received'),
                    ]);
                    
                    Log::warning('Match2Pay IPN: Unknown status received', [
                        'paymentId' => $paymentId,
                        'status' => $status
                    ]);
                    return response()->json(['status' => 'error', 'message' => 'Unknown status'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Match2Pay IPN: Exception occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function cryptomusIpn(Request $request)
    {
        $data = $request->all();
        $gatewayInfo = gateway_info('cryptomus');
        $merchantId = $gatewayInfo->merchant_id;
        $paymentKey = $gatewayInfo->payment_key;
        $payment = \Cryptomus\Api\Client::payment($paymentKey, $merchantId);
        $result = $payment->info($data);
        $txn = $result['order_id'];
        $status = $result['status'];
        if ($status == 'paid') {
            $transaction = Transaction::tnx($txn);
            if ($transaction->type == TxnType::Withdraw) {
                Txn::update($transaction->tnx, TxnStatus::Success, $transaction->user_id);
            } else {
                self::paymentSuccess($txn);
            }

        }
    }

    public function paypalIpn(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $txn = $response['purchase_units'][0]['reference_id'];

            return self::paymentSuccess($txn);

        }

        return redirect()
            ->route('user.deposit.now')
            ->with('error', $response['message'] ?? __('Something went wrong.'));

    }

    public function mollieIpn(Request $request)
    {
        $paymentId = $request->id;
        $payment = Mollie::api()->payments()->get($paymentId);
        if ($payment->isPaid()) {
            $ref = $request->reftrn;

            return self::paymentSuccess($ref);
        }
    }

    public function perfectMoneyIpn(Request $request)
    {
        $ref = Crypt::decryptString($request->PAYMENT_ID);

        return self::paymentSuccess($ref);
    }

    public function paystackIpn()
    {
        $paymentDetails = Paystack::getPaymentData();
        if ($paymentDetails['data']['status'] == 'success') {
            $transactionId = $paymentDetails['data']['reference'];

            return self::paymentSuccess($transactionId);

        }

        return redirect()->route('status.cancel');

    }

    public function flutterwaveIpn()
    {
        if (isset($_GET['status'])) {

            $credentials = gateway_info('flutterwave');

            // Check payment status
            $txnid = $_GET['tx_ref'];
            $txnInfo = Transaction::tnx($txnid);

            if ($_GET['status'] == 'cancelled') {
                $txnInfo->update([
                    'status' => TxnStatus::Failed,
                ]);

            } elseif ($_GET['status'] == 'successful') {
                $txid = $_GET['transaction_id'];

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer '.$credentials->secret_key,
                ])->get("https://api.flutterwave.com/v3/transactions/{$txid}/verify");

                if ($response->successful()) {
                    $res = $response->json();
                    $amountPaid = $res['data']['charged_amount'];
                    $amountToPay = $res['data']['meta']['price'];

                    if ($amountPaid >= $amountToPay) {
                        return self::paymentSuccess($txnid, false);
                    }
                    $txnInfo->update([
                        'status' => TxnStatus::Failed,
                    ]);

                } else {
                    $txnInfo->update([
                        'status' => TxnStatus::Failed,
                    ]);
                }
            }
        }
    }

    public function coingateIpn(Request $request)
    {
        if ($request->status == 'paid') {
            self::paymentSuccess($request->order_id);
        } else {
            Txn::update($request->order_id, __('failed'));
        }
    }

    public function monnifyIpn()
    {

        (isset($_GET) && isset($_GET['paymentReference'])) ?
            ($ref = htmlspecialchars($_GET['paymentReference'])) : $ref = null;
        $trx = Session::get('deposit_tnx');
        $txnInfo = Transaction::tnx($trx);
        if (htmlspecialchars($_GET['paymentReference'])) {

            //Query the transaction reference from your DB call the method

            $monnify = new Monnify();

            $verify = $monnify->verifyTrans($txnInfo->approval_cause);

            if ($verify['paymentStatus'] == 'PAID') {
                $txnInfo->update([
                    'approval_cause' => 'none',
                ]);

                return self::paymentSuccess($ref, false);

                //Payment has been verified!

            }
            Txn::update($ref, __('failed'));

        } else {
            Txn::update($ref, __('failed'));
        }
    }

    public function nonHostedSecurionpayIpn(Request $request)
    {
        $depositTnx = Session::get('deposit_tnx');
        $tnxInfo = Transaction::tnx($depositTnx);
        $securionPay = new SecurionpayTxn($tnxInfo);

        return $securionPay->nonHostedPayment($request);
    }

    public function coinremitterIpn(Request $request){
           $txn =  $request->description;
           if ($request->status == 'paid') {
             return  self::paymentSuccess($txn);
           }
    }

    public function btcpayIpn(Request $request)
    {
        $gatewayInfo = gateway_info('btcpayserver');
        $host = $gatewayInfo->host;
        $apiKey = $gatewayInfo->api_key;
        $storeId = $gatewayInfo->store_id;
        $webhookSecret = $gatewayInfo->webhook_secret;

        $raw_post_data = file_get_contents('php://input');
        $payload = json_decode($raw_post_data, false, 512, JSON_THROW_ON_ERROR);

        // Get the BTCPay signature header.
        $headers = getallheaders();
        foreach ($headers as $key => $value) {
            if (strtolower($key) === 'btcpay-sig') {
                $sig = $value;
            }
        }

        $webhookClient = new \BTCPayServer\Client\Webhook($host, $apiKey);

        // Validate the webhook request.
        if (!$webhookClient->isIncomingWebhookRequestValid($raw_post_data, $sig, $webhookSecret)) {
            throw new \RuntimeException(
                __('Invalid BTCPayServer payment notification message received - signature did not match.')
            );
        }
        $data = $request->all();
        self::paymentSuccess($data['metadata']['orderId']);
    }

    public function binanceIpn(Request $request){
        header("Content-Type: application/json");
        $webhookResponse = $request->all();
        $returnCode = $webhookResponse['bizStatus'];
        $data = json_decode($webhookResponse['data'], true);
        if($returnCode == "SUCCESS"){
            self::paymentSuccess($data['productName']);
        }
    }

    public function blockchainIpn(Request $request){

        $requestData = $request->all();
        $btcValue = $requestData['value']  / 100000000;
        $deposit = Transaction::tnx($requestData['txn']);
        if ($deposit->pay_amount >= $btcValue && $requestData['confirmations'] > 2 && $deposit->status == TxnStatus::Pending) {
            self::paymentSuccess($requestData['txn']);
        }

    }

    public function instamojoIpn(Request $request){
        $payload = $request->all();
        $gatewayInfo = gateway_info('instamojo');
        $instamojoSignature = $payload['mac'];
        $expectedSignature = hash_hmac('sha1', json_encode($payload), $gatewayInfo->salt);
        if ($instamojoSignature == $expectedSignature && $request->payment_status == 'Credit') {
            self::paymentSuccess(($request->txn));
        }

    }

    public function paytmIpn(Request $request)
    {
        $transaction = PaytmWallet::with('receive');
        $txn = $transaction->getOrderId();
        if($transaction->isSuccessful()){
            self::paymentSuccess(($txn));
        }
    }

    public function razorpayIpn(Request $request)
    {
        $credentials = gateway_info('razorpay');
        $computedSignature = hash_hmac('sha256', $request->input('razorpay_order_id') . "|" . $request->input('razorpay_payment_id'), $credentials->razorpay_secret);
        if ($computedSignature === $request->input('razorpay_signature')) {
            $this->paymentSuccess($request->input('txn'));
        }
    }
    public function twocheckoutIpn(Request $request){

        $gatewayInfo = gateway_info('twocheckout');
        $payload = $request->getContent();
        $expectedHash = hash_hmac('md5', $payload, $gatewayInfo->secret_word);
        $receivedHash = $request->header('X-2Checkout-Signature');
        if ($receivedHash == $expectedHash) {
          $this->paymentSuccess( $request->li_0_product_id);
        }
    }

        public function uniwireIpn(Request $request)
    {
        try {
            // Get raw content from the request
            $rawContent = $request->getContent();
            
            // Log the raw content first
            Log::info('Raw content received:', [
                'content' => $rawContent,
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method()
            ]);
            
            // Decode the raw JSON content
            $payload = json_decode($rawContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON parsing failed', [
                    'error' => json_last_error_msg(),
                    'raw_content' => $rawContent,
                    'content_length' => strlen($rawContent)
                ]);
                return response()->json([
                    'error' => 'Invalid JSON payload',
                    'details' => json_last_error_msg(),
                    'received_content_type' => $request->header('Content-Type')
                ], 400);
            }

            Log::info('Uniwire IPN payload: ' . $rawContent);

            // Extract transaction ID based on callback type
            $txn = null;
            $passthrough = null;

            if ($payload['callback_status'] === 'invoice_complete') {
                if (isset($payload['invoice']['passthrough'])) {
                    $passthrough = json_decode($payload['invoice']['passthrough'], true);
                }
            } elseif ($payload['callback_status'] === 'transaction_complete') {
                if (isset($payload['transaction']['invoice']['passthrough'])) {
                    $passthrough = json_decode($payload['transaction']['invoice']['passthrough'], true);
                }
            }

            if (!$passthrough || !isset($passthrough['transaction_id'])) {
                Log::error('Transaction ID not found in passthrough', ['payload' => $payload]);
                return response()->json(['error' => 'Transaction ID not found'], 400);
            }

            $txn = $passthrough['transaction_id'];

            // Start database transaction with row locking
            return DB::transaction(function() use ($txn, $payload) {
                // Get transaction with lock
                $txnInfo = Transaction::where('tnx', $txn)->lockForUpdate()->first();
                
                if (!$txnInfo) {
                    Log::error('Transaction not found', ['txn' => $txn]);
                    return response()->json(['error' => 'Transaction not found'], 404);
                }

                // Check if transaction is already successful
                if ($txnInfo->status == TxnStatus::Success) {
                    Log::info('Transaction already successful, skipping processing', ['txn' => $txn]);
                    return response()->json(['status' => 'success', 'message' => 'Transaction already processed']);
                }

                // Process transaction based on status
                $status = $payload['callback_status'];
                switch ($status) {
                    case 'transaction_pending':
                        $txnInfo->update([
                            'status' => TxnStatus::Pending,
                            'approval_cause' => 'Pending',
                            'manual_field_data' => json_encode([
                                'txid' => $payload['transaction']['txid'] ?? null,
                                'confirmations' => $payload['transaction']['confirmations'] ?? 0
                            ])
                        ]);
                        break;

                    case 'transaction_complete':
                    case 'invoice_complete':
                        // Get transaction data based on callback type
                        $transactionData = $status === 'transaction_complete' ? 
                            $payload['transaction'] : 
                            (isset($payload['invoice']['transactions'][0]) ? $payload['invoice']['transactions'][0] : null);

                        if (!$transactionData) {
                            Log::error('No transaction data found', ['payload' => $payload]);
                            return response()->json(['error' => 'No transaction data found'], 400);
                        }

                        $txnInfo->update([
                            'pay_amount' => $transactionData['amount'] ?? 0,
                            'pay_currency' => $transactionData['currency'] ?? 'USDT-TRX',
                            'manual_field_data' => json_encode([
                                'txid' => $transactionData['txid'] ?? null,
                                'confirmations' => $transactionData['confirmations'] ?? 0,
                                'network' => $payload['transaction']['network'] ?? 'mainnet',
                                'risk_level' => $payload['transaction']['risk_level'] ?? null
                            ]),
                            'approval_cause' => 'Payment complete'
                        ]);
                        return self::paymentSuccess($txn);

                    case 'transaction_failed':
                        $txnInfo->update([
                            'status' => TxnStatus::Failed,
                            'approval_cause' => 'Transaction failed'
                        ]);
                        break;

                    default:
                        Log::warning('Unhandled Uniwire callback status', ['status' => $status]);
                        return response()->json(['warning' => 'Unhandled status'], 200);
                }

                return response()->json(['status' => 'success']);
            }, 3); // 3 retries if deadlock occurs

        } catch (\Exception $e) {
            Log::error('Error processing Uniwire IPN', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function jenapayIpn(Request $request)
    {
        try {
            $data = $request->all();
            
            // Log the callback for debugging
            Log::info('JenaPay IPN received', $data);

            // Extract hash from request
            $receivedHash = $data['hash'] ?? '';

            // Create JenapayTxn instance to verify signature
            $jenapayTxn = new JenapayTxn((object)[]);
            
            if (!$jenapayTxn->verifyCallbackSignature($data, $receivedHash)) {
                Log::warning('JenaPay: Invalid callback signature', $data);
                return response('fail', 400);
            }

            // Extract transaction details
            $orderNumber = $data['order']['number'] ?? '';
            $paymentStatus = $data['payment_status'] ?? '';
            $paymentId = $data['payment_id'] ?? '';

            // Update transaction with payment ID if available
            if ($paymentId) {
                $transaction = Transaction::tnx($orderNumber);
                if ($transaction) {
                    $transaction->update([
                        'tnx' => $paymentId,
                        'manual_field_data' => $data
                    ]);
                }
            }

            // Handle different payment statuses
            switch ($paymentStatus) {
                case 'success':
                case 'completed':
                    self::paymentSuccess($orderNumber);
                    return response('OK', 200);
                    
                case 'failed':
                case 'declined':
                    $transaction = Transaction::tnx($orderNumber);
                    if ($transaction) {
                        $transaction->update([
                            'status' => TxnStatus::Failed,
                            'approval_cause' => 'Payment failed or declined'
                        ]);
                    }
                    return response('OK', 200);
                    
                case 'pending':
                    // Keep transaction as pending
                    return response('OK', 200);
                    
                default:
                    Log::warning('JenaPay: Unknown payment status', [
                        'status' => $paymentStatus,
                        'data' => $data
                    ]);
                    return response('OK', 200);
            }

        } catch (\Exception $e) {
            Log::error('Error processing JenaPay IPN', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            return response('fail', 500);
        }
    }

}
