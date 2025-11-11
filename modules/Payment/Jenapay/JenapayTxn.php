<?php
namespace Payment\Jenapay;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Payment\Transaction\BaseTxn;
use App\Models\Transaction;
use Txn;

class JenapayTxn extends BaseTxn
{
    protected $apiUrl;
    protected $merchantKey;
    protected $merchantPass;
    protected $successUrl;
    protected $cancelUrl;
    protected $callbackUrl;
    protected $client;

    public function __construct($txnInfo)
    {
        parent::__construct($txnInfo);
        $credentials = gateway_info('jenapay');

        // Set the base URL for JenaPay API
        $this->apiUrl = $credentials->api_url ?? 'https://checkout.jenapay.com';
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
        ]);

        // Define required credentials
        $this->amount = $txnInfo->final_amount;
        $this->merchantKey = $credentials->merchant_key;
        $this->merchantPass = $credentials->merchant_pass;
        $this->successUrl = url('/status/success');
        $this->cancelUrl = url('/status/cancel');
        $this->callbackUrl = url('/ipn/jenapay');
    }

    /**
     * Handle the deposit request for JenaPay
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deposit()
    {
        try {
            // Generate hash for authentication
            $hash = $this->generateAuthSignature(
                $this->txn,
                number_format((float)$this->amount, 2, '.', ''),
                base_currency(),
                'Deposit for ' . $this->userName
            );

            // Prepare the payload according to JenaPay API
            $payload = [
                'merchant_key' => $this->merchantKey,
                'operation' => 'purchase',
                'order' => [
                    'number' => $this->txn,
                    'amount' => number_format((float)$this->amount, 2, '.', ''),
                    'currency' => base_currency(),
                    'description' => 'Deposit for ' . $this->userName,
                ],
                'customer' => [
                    'name' => $this->userName,
                    'email' => $this->userEmail,
                ],
                'success_url' => $this->successUrl,
                'cancel_url' => $this->cancelUrl,
                'callback_url' => $this->callbackUrl,
                'hash' => $hash,
            ];


            // Send the deposit request to JenaPay API
            $response = $this->client->request('POST', $this->apiUrl . '/api/v1/session', [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($payload),
            ]);

            // Parse the response
            $data = json_decode($response->getBody()->getContents(), true);
            
            // Update transaction with response data
            $transaction = Transaction::tnx($this->txn);
            if ($transaction) {
                $transaction->manual_field_data = $data;
                $transaction->save();
            }

            // Handle response (get the checkout URL and other details)
            if (isset($data['redirect_url'])) {
                $checkoutUrl = $data['redirect_url'];
                return redirect($checkoutUrl);
            }

            // Handle errors or missing fields if necessary
            throw new \Exception('Unable to generate checkout URL: ' . json_encode($data));

        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('JenaPay deposit error: ' . $e->getMessage(), [
                'txn' => $this->txn,
                'amount' => $this->amount,
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception('Payment gateway error: ' . $e->getMessage());
        }
    }

    /**
     * Generate authentication signature for payment request.
     * Formula: sha1(md5(strtoupper(order.number + order.amount + order.currency + order.description + merchant.pass)))
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    public function generateAuthSignature(string $orderId, string $amount, string $currency, string $description): string
    {
        // Order: order.number + order.amount + order.currency + order.description + merchant.pass
        $string = $orderId . $amount . $currency . $description . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate callback signature for verification.
     * Formula: sha1(md5(strtoupper(payment_public_id + order.number + order.amount + order.currency + order.description + merchant.pass)))
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    public function generateCallbackSignature(string $paymentPublicId, string $orderId, string $amount, string $currency, string $description): string
    {
        // Order: payment_public_id + order.number + order.amount + order.currency + order.description + merchant.pass
        $string = $paymentPublicId . $orderId . $amount . $currency . $description . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Verify callback signature.
     * Uses payment_public_id as per official documentation
     */
    public function verifyCallbackSignature(array $data, string $receivedHash): bool
    {
        // Use payment_public_id (not payment_id) as per documentation
        $paymentPublicId = $data['payment_public_id'] ?? $data['payment_id'] ?? '';
        $calculatedHash = $this->generateCallbackSignature(
            $paymentPublicId,
            $data['order']['number'] ?? '',
            $data['order']['amount'] ?? '',
            $data['order']['currency'] ?? '',
            $data['order']['description'] ?? ''
        );

        return hash_equals($calculatedHash, $receivedHash);
    }

    /**
     * Get transaction status by payment ID.
     * Endpoint: /api/v1/payment/status
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    public function getTransactionStatusByPaymentId(string $paymentId): array
    {
        $hash = $this->generateStatusSignature($paymentId);

        $payload = [
            'merchant_key' => $this->merchantKey,
            'payment_id' => $paymentId,
            'hash' => $hash,
        ];

        $response = $this->client->request('POST', $this->apiUrl . '/api/v1/payment/status', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($payload),
        ]);

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    /**
     * Get transaction status by order ID.
     * Endpoint: /api/v1/payment/status
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    public function getTransactionStatusByOrderId(string $orderId): array
    {
        $hash = $this->generateStatusSignatureByOrderId($orderId);

        $payload = [
            'merchant_key' => $this->merchantKey,
            'order_id' => $orderId,
            'hash' => $hash,
        ];

        $response = $this->client->request('POST', $this->apiUrl . '/api/v1/payment/status', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($payload),
        ]);

        return json_decode($response->getBody()->getContents(), true) ?? [];
    }

    /**
     * Generate status signature by payment ID.
     * Formula: sha1(md5(strtoupper(payment.id + merchant.pass)))
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    protected function generateStatusSignature(string $paymentId): string
    {
        // Order: payment.id + merchant.pass
        $string = $paymentId . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate status signature by order ID.
     * Formula: sha1(md5(strtoupper(order.id + merchant.pass)))
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    protected function generateStatusSignatureByOrderId(string $orderId): string
    {
        // Order: order.id + merchant.pass
        $string = $orderId . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }
}
