<?php

namespace App\Services\Kyc;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class VeriffClient
{
    public function __construct(
        private string $apiKey,
        private string $sharedSecret,
        private string $baseUrl,
        private ?string $integrationId = null,
    ) {}

    public static function make(): self
    {
        // Get credentials from plugins table, just like Sumsub
        $plugin = \App\Models\Plugin::where('name', 'Veriff (Automated KYC)')->first();
        
        if (!$plugin || $plugin->status !== 1) {
            throw new \RuntimeException('Veriff plugin not found or inactive');
        }

        $credentials = json_decode($plugin->data);
        
        return new self(
            $credentials->api_key ?? '',
            $credentials->shared_secret ?? '',
            rtrim($credentials->base_url ?? 'https://api.veriff.com/v1', '/'),
            $credentials->integration_id ?? null,
        );
    }

    /**
     * Create verification session and return array with id + url (+ token if provided)
     */
    public function createSession(array $verification): array
    {
        $body = ['verification' => $verification];

        $headers = [
            'x-auth-client'    => $this->apiKey,
            'x-hmac-signature' => $this->hmac(json_encode($body, JSON_UNESCAPED_SLASHES)),
            'content-type'     => 'application/json',
        ];
        
        if ($this->integrationId) {
            $headers['vrf-integration-id'] = $this->integrationId;
        }

        // try {
            $response = Http::withHeaders($headers)->post("{$this->baseUrl}/sessions", $body);
            
            Log::info('Veriff session creation request', [
                'url' => "{$this->baseUrl}/sessions",
                'status' => $response->status(),
                'headers' => array_keys($headers),
            ]);

            $json = $response->json();
            
            if (!$response->successful() || !is_array($json)) {
                Log::error('Veriff create session failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'verification' => $verification,
                ]);
                throw new RuntimeException('Veriff create session failed: ' . $response->body());
            }

            Log::info('Veriff session created successfully', [
                'session_id' => $json['verification']['id'] ?? 'unknown',
                'end_user_id' => $verification['endUserId'] ?? 'unknown',
            ]);

            // Typical fields: verification.id, verification.url, verification.sessionToken
            return $json['verification'] ?? $json;
            
        // } catch (\Exception $e) {
        //     Log::error('Veriff session creation exception', [
        //         'error' => $e->getMessage(),
        //         'verification' => $verification,
        //     ]);
        //     throw $e;
        // }
    }

    /**
     * Upload one media file (if you run API-only capture)
     */
    public function uploadMedia(string $sessionId, array $imagePayload): array
    {
        $body = ['image' => $imagePayload];
        $headers = [
            'x-auth-client'    => $this->apiKey,
            'x-hmac-signature' => $this->hmac(json_encode($body, JSON_UNESCAPED_SLASHES)),
            'content-type'     => 'application/json',
        ];
        
        try {
            $response = Http::withHeaders($headers)->post("{$this->baseUrl}/sessions/{$sessionId}/media", $body);
            
            if (!$response->successful()) {
                Log::error('Veriff media upload failed', [
                    'session_id' => $sessionId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new RuntimeException('Veriff media upload failed: ' . $response->body());
            }
            
            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Veriff media upload exception', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Submit session (for API-only capture)
     */
    public function submit(string $sessionId): void
    {
        $body = ['verification' => ['status' => 'submitted']];
        $headers = [
            'x-auth-client'    => $this->apiKey,
            'x-hmac-signature' => $this->hmac(json_encode($body, JSON_UNESCAPED_SLASHES)),
            'content-type'     => 'application/json',
        ];
        
        try {
            $response = Http::withHeaders($headers)->patch("{$this->baseUrl}/sessions/{$sessionId}", $body);
            
            if (!$response->successful()) {
                Log::error('Veriff submit failed', [
                    'session_id' => $sessionId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                throw new RuntimeException('Veriff submit failed: ' . $response->body());
            }
            
            Log::info('Veriff session submitted successfully', [
                'session_id' => $sessionId,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Veriff submit exception', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Verify webhook HMAC signature
     */
    public function verifySignature(string $rawBody, string $headerSig): bool
    {
        try {
            $expectedSignature = $this->hmac($rawBody);
            $isValid = hash_equals($expectedSignature, $headerSig);
            
            Log::info('Veriff webhook signature verification', [
                'is_valid' => $isValid,
                'header_signature_length' => strlen($headerSig),
                'expected_signature_length' => strlen($expectedSignature),
            ]);
            
            return $isValid;
            
        } catch (\Exception $e) {
            Log::error('Veriff signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Generate HMAC signature for request body
     */
    private function hmac(string $payload): string
    {
        return hash_hmac('sha256', $payload, $this->sharedSecret);
    }
}
