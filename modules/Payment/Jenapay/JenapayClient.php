<?php
namespace Payment\Jenapay;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class JenapayClient
{
    protected string $apiUrl;
    protected ?string $merchantKey;
    protected ?string $merchantPass;
    protected ?string $successUrl;
    protected ?string $cancelUrl;
    protected ?string $callbackUrl;
    protected $client;

    public function __construct()
    {
        $credentials = gateway_info('jenapay');
        
        $this->apiUrl = $credentials->api_url ?? 'https://checkout.jenapay.com';
        $this->merchantKey = $credentials->merchant_key;
        $this->merchantPass = $credentials->merchant_pass;
        $this->successUrl = url('/status/success');
        $this->cancelUrl = url('/status/cancel');
        $this->callbackUrl = url('/ipn/jenapay');
        
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Generate authentication signature for payment request.
     * Formula: sha1(md5(strtoupper(order_id.amount.currency.description.merchant_pass)))
     * SHA1 of MD5 hex string (NOT binary)
     */
    public function generateAuthSignature(string $orderId, string $amount, string $currency, string $description): string
    {
        $string = $orderId . $amount . $currency . $description . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate callback signature for verification.
     * Formula: sha1(md5(strtoupper(payment_id.order_id.amount.currency.description.merchant_pass)))
     * SHA1 of MD5 hex string (NOT binary)
     */
    public function generateCallbackSignature(string $paymentId, string $orderId, string $amount, string $currency, string $description): string
    {
        $string = $paymentId . $orderId . $amount . $currency . $description . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Verify callback signature.
     */
    public function verifyCallbackSignature(array $data, string $receivedHash): bool
    {
        $calculatedHash = $this->generateCallbackSignature(
            $data['payment_id'] ?? '',
            $data['order']['number'] ?? '',
            $data['order']['amount'] ?? '',
            $data['order']['currency'] ?? '',
            $data['order']['description'] ?? ''
        );

        return hash_equals($calculatedHash, $receivedHash);
    }

    /**
     * Create a payment authentication request.
     */
    public function createPayment(array $orderData): array
    {
        $hash = $this->generateAuthSignature(
            $orderData['order_id'],
            $orderData['amount'],
            $orderData['currency'],
            $orderData['description']
        );

        $payload = [
            'merchant_key' => $this->merchantKey,
            'operation' => 'purchase',
            'methods' => $orderData['methods'] ?? [],
            'order' => [
                'number' => $orderData['order_id'],
                'amount' => $orderData['amount'],
                'currency' => $orderData['currency'],
                'description' => $orderData['description'],
            ],
            'customer' => $orderData['customer'] ?? [],
            'success_url' => $orderData['success_url'] ?? $this->successUrl,
            'cancel_url' => $orderData['cancel_url'] ?? $this->cancelUrl,
            'callback_url' => $orderData['callback_url'] ?? $this->callbackUrl,
            'hash' => $hash,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/api/v1/session", $payload);

        return $response->json() ?? [];
    }

    /**
     * Get transaction status by payment ID.
     */
    public function getTransactionStatusByPaymentId(string $paymentId): array
    {
        $hash = $this->generateStatusSignature($paymentId);

        $payload = [
            'merchant_key' => $this->merchantKey,
            'payment_id' => $paymentId,
            'hash' => $hash,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/payment/status", $payload);

        return $response->json() ?? [];
    }

    /**
     * Get transaction status by order ID.
     */
    public function getTransactionStatusByOrderId(string $orderId): array
    {
        $hash = $this->generateStatusSignatureByOrderId($orderId);

        $payload = [
            'merchant_key' => $this->merchantKey,
            'order_id' => $orderId,
            'hash' => $hash,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/payment/status", $payload);

        return $response->json() ?? [];
    }

    /**
     * Refund a payment.
     */
    public function refundPayment(string $paymentId, string $amount): array
    {
        $hash = $this->generateRefundSignature($paymentId, $amount);

        $payload = [
            'merchant_key' => $this->merchantKey,
            'payment_id' => $paymentId,
            'amount' => $amount,
            'hash' => $hash,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/payment/refund", $payload);

        return $response->json() ?? [];
    }

    /**
     * Void a payment.
     */
    public function voidPayment(string $paymentId): array
    {
        $hash = $this->generateVoidSignature($paymentId);

        $payload = [
            'merchant_key' => $this->merchantKey,
            'payment_id' => $paymentId,
            'hash' => $hash,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/payment/void", $payload);

        return $response->json() ?? [];
    }

    /**
     * Capture a payment (for DMS mode).
     */
    public function capturePayment(string $paymentId, string $amount): array
    {
        $hash = $this->generateCaptureSignature($paymentId, $amount);

        $payload = [
            'merchant_key' => $this->merchantKey,
            'payment_id' => $paymentId,
            'amount' => $amount,
            'hash' => $hash,
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/payment/capture", $payload);

        return $response->json() ?? [];
    }

    /**
     * Generate status signature by payment ID.
     * Formula: sha1(md5(strtoupper(payment_id.merchant_pass)))
     * SHA1 of MD5 hex string (NOT binary)
     */
    protected function generateStatusSignature(string $paymentId): string
    {
        $string = $paymentId . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate status signature by order ID.
     * Formula: sha1(md5(strtoupper(order_id.merchant_pass)))
     * SHA1 of MD5 hex string (NOT binary)
     */
    protected function generateStatusSignatureByOrderId(string $orderId): string
    {
        $string = $orderId . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate refund signature.
     * Formula: sha1(md5(strtoupper(payment_id.amount.merchant_pass)))
     * SHA1 of MD5 hex string (NOT binary)
     */
    protected function generateRefundSignature(string $paymentId, string $amount): string
    {
        $string = $paymentId . $amount . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate void signature.
     * Formula: sha1(md5(strtoupper(payment_id.merchant_pass)))
     * SHA1 of MD5 hex string (NOT binary)
     */
    protected function generateVoidSignature(string $paymentId): string
    {
        $string = $paymentId . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate capture signature.
     * Formula: sha1(md5(strtoupper(payment_id.amount.merchant_pass)))
     * SHA1 of MD5 hex string (NOT binary)
     */
    protected function generateCaptureSignature(string $paymentId, string $amount): string
    {
        $string = $paymentId . $amount . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }
}
