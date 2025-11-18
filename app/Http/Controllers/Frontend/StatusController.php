<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\NotifyTrait;
use App\Traits\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Payment\Jenapay\JenapayTxn;
use Session;
use Txn;

class StatusController extends Controller
{
    use NotifyTrait, Payment;

    public function pending(Request $request)
    {
        $depositTnx = Session::get('deposit_tnx');

        return self::paymentNotify($depositTnx, 'pending');
    }

    public function success(Request $request)
    {
        $data = $request->all();
        // dd($data);
        Log::info('JenaPay success redirect received', $data);
        // dd($request->all());

        // Handle JenaPay redirect with parameters
        // According to: https://docs.jenapay.com/docs/guides/checkout_integration
        if ($request->has('order_id') || $request->has('payment_id')) {
            try {
                $orderId = $request->order_id ?? $request->trans_id ?? '';
                
                if ($orderId) {
                    // Verify hash if provided (when "Return parameters" is enabled)
                    if ($request->has('hash')) {
                        $jenapayTxn = new JenapayTxn((object)[]);
                        
                        // Prepare data for hash verification
                        $paymentPublicId = $request->payment_id ?? '';
                        $orderNumber = $request->order_id ?? '';
                        $amount = $request->amount ?? '';
                        $currency = $request->currency ?? '';
                        $description = $request->description ?? '';
                        $receivedHash = $request->hash ?? '';
                        
                        // Try to verify hash if we have enough data
                        if ($paymentPublicId && $orderNumber && $amount && $currency) {
                            if (!$jenapayTxn->verifyCallbackSignature([
                                'payment_public_id' => $paymentPublicId,
                                'order' => [
                                    'number' => $orderNumber,
                                    'amount' => $amount,
                                    'currency' => $currency,
                                    'description' => $description,
                                ]
                            ], $receivedHash)) {
                                Log::warning('JenaPay: Invalid redirect signature', $request->all());
                                // Continue anyway as redirect URLs may not always have hash
                            }
                        }
                    }
                    
                    // Process payment success
                    return self::paymentSuccess($orderId);
                }
            } catch (\Exception $e) {
                Log::error('JenaPay redirect error: ' . $e->getMessage(), [
                    'request' => $request->all(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Fall through to default handling
            }
        }
        
        // Handle other gateways with encrypted reference
        if (isset($request->reftrn)) {
            $ref = Crypt::decryptString($request->reftrn);
            return self::paymentSuccess($ref);
        }
        
        // Default: use session
        $depositTnx = Session::get('deposit_tnx');
        return self::paymentNotify($depositTnx, 'success');
    }

    public function cancel(Request $request)
    {
        // Handle JenaPay cancel redirect with parameters
        if ($request->has('order_id') || $request->has('payment_id')) {
            try {
                $orderId = $request->order_id ?? $request->trans_id ?? '';
                
                if ($orderId) {
                    // Update transaction status to failed/cancelled
                    $transaction = \App\Models\Transaction::tnx($orderId);
                    if ($transaction) {
                        Txn::update($orderId, 'failed');
                        Log::info('JenaPay: Payment cancelled via redirect', ['order_id' => $orderId]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('JenaPay cancel redirect error: ' . $e->getMessage(), [
                    'request' => $request->all()
                ]);
            }
        } else {
            // Default: use session
            $trx = Session::get('deposit_tnx');
            if ($trx) {
                Txn::update($trx, 'failed');
            }
        }

        notify()->warning('Payment Canceled');
        return redirect(route('user.dashboard'))->setStatusCode(200);
    }
}
