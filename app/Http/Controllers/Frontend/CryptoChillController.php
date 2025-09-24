<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Enums\TxnStatus;
use Illuminate\Support\Facades\Log;

class CryptoChillController extends Controller
{
    /**
     * Show CryptoChill payment page
     */
    public function showPayment($txn)
    {
        try {
            $transaction = Transaction::where('txn', $txn)->firstOrFail();
            
            // Initialize payment gateway
            $uniwire = new \Payment\Uniwire\UniwireTxn($transaction);
            return $uniwire->deposit();

        } catch (\Exception $e) {
            Log::error('CryptoChill Payment Error', [
                'message' => $e->getMessage(),
                'transaction_id' => $txn
            ]);

            return redirect()->route('user.deposit.amount')->with([
                'error' => true,
                'message' => 'Payment initialization failed: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Handle payment status
     */
    public function handleStatus(Request $request)
    {
        try {
            $status = $request->get('status');
            $txn = $request->get('txn');
            $error = $request->get('error');

            if (!$txn) {
                throw new \Exception('Transaction ID not found');
            }

            $transaction = Transaction::where('txn', $txn)->first();
            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            switch ($status) {
                case 'success':
                    // Transaction will be updated via IPN, just show success message
                    return redirect()->route('user.notify.success')->with([
                        'success' => true,
                        'message' => 'Payment completed successfully. Please wait while we process your payment.',
                        'transaction' => $transaction
                    ]);

                case 'cancel':
                    $transaction->update([
                        'status' => TxnStatus::Cancelled,
                        'approval_cause' => 'Payment cancelled by user'
                    ]);
                    
                    return redirect()->route('user.notify.canceled')->with([
                        'warning' => true,
                        'message' => 'Payment was cancelled',
                        'transaction' => $transaction
                    ]);

                case 'error':
                    $transaction->update([
                        'status' => TxnStatus::Failed,
                        'approval_cause' => $error ?? 'Payment failed'
                    ]);

                    return redirect()->route('user.notify.failed')->with([
                        'error' => true,
                        'message' => 'Payment failed: ' . ($error ?? 'Unknown error'),
                        'transaction' => $transaction
                    ]);

                default:
                    return redirect()->route('user.deposit.amount')->with([
                        'warning' => true,
                        'message' => 'Invalid payment status'
                    ]);
            }

        } catch (\Exception $e) {
            Log::error('CryptoChill Status Handler Error', [
                'message' => $e->getMessage(),
                'data' => $request->all()
            ]);

            return redirect()->route('user.notify.failed')->with([
                'error' => true,
                'message' => 'Error processing payment status: ' . $e->getMessage()
            ]);
        }
    }
} 