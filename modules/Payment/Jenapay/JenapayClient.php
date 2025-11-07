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

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/api/v1/payment/status", $payload);

        return $response->json() ?? [];
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

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUrl}/api/v1/payment/status", $payload);

        return $response->json() ?? [];
    }

    /**
     * Refund a payment.
     * Endpoint: /api/v1/payment/refund
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
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
        ])->post("{$this->apiUrl}/api/v1/payment/refund", $payload);

        return $response->json() ?? [];
    }

    /**
     * Void a payment.
     * Endpoint: /api/v1/payment/void
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
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
        ])->post("{$this->apiUrl}/api/v1/payment/void", $payload);

        return $response->json() ?? [];
    }

    /**
     * Capture a payment (for DMS mode).
     * Endpoint: /api/v1/payment/capture
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
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
        ])->post("{$this->apiUrl}/api/v1/payment/capture", $payload);

        return $response->json() ?? [];
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

    /**
     * Generate refund signature.
     * Formula: sha1(md5(strtoupper(payment.id + amount + merchant.pass)))
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    protected function generateRefundSignature(string $paymentId, string $amount): string
    {
        // Order: payment.id + amount + merchant.pass
        $string = $paymentId . $amount . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate void signature.
     * Formula: sha1(md5(strtoupper(payment.id + merchant.pass)))
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    protected function generateVoidSignature(string $paymentId): string
    {
        // Order: payment.id + merchant.pass
        $string = $paymentId . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }

    /**
     * Generate capture signature.
     * Formula: sha1(md5(strtoupper(payment.id + amount + merchant.pass)))
     * According to: https://docs.jenapay.com/docs/guides/checkout_integration
     */
    protected function generateCaptureSignature(string $paymentId, string $amount): string
    {
        // Order: payment.id + amount + merchant.pass
        $string = $paymentId . $amount . $this->merchantPass;
        $md5Hex = md5(strtoupper($string));
        
        // SHA1 of MD5 hex string (NOT binary)
        return sha1($md5Hex);
    }
}
