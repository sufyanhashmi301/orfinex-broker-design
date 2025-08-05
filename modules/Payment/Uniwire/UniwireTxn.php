<?php

namespace Payment\Uniwire;

use App\Enums\TxnStatus;
use App\Models\Transaction;
use Payment\Transaction\BaseTxn;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Base64\Base64;

class UniwireTxn extends BaseTxn
{
    protected $api_url;
    protected $api_key;
    protected $api_secret;
    protected $profile_id;
    protected $client;
    protected $txnInfo;


    public function __construct($txnInfo)
    {
        parent::__construct($txnInfo);
        
        $this->txnInfo = $txnInfo;
    //  dd($this->txnInfo);
        // Get gateway credentials
        $credentials = gateway_info('uniwire');

        $this->api_key = $credentials->API_KEY;
        $this->api_secret = $credentials->API_SECRET;
        $this->profile_id = $credentials->PROFILE_ID;

        
        // Set API URL based on environment
        $this->api_url = $credentials->ENVIRONMENT === 'testnet' 
            ? 'https://testnet-api.uniwire.com'
            : 'https://api.uniwire.com';
    }

    protected function makeRequest($endpoint, $payload = [], $method = 'GET')
    {
        // dd($txnInfo);
        try {
            // Add required fields
            $payload_nonce = round(microtime(true) * 1000);
            $request_path = "/v1/{$endpoint}/";
            $payload['request'] = $request_path;
            $payload['nonce'] = $payload_nonce;

            // Encode payload to base64 format and create signature
            $encoded_payload = base64_encode(json_encode($payload));
            $signature = hash_hmac('sha256', $encoded_payload, $this->api_secret);

            // Set headers
            $headers = [
                'X-CC-KEY' => $this->api_key,
                'X-CC-PAYLOAD' => $encoded_payload,
                'X-CC-SIGNATURE' => $signature,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ];
// dd($headers,$this->api_url . $request_path, $payload);
            // Make request with increased timeout
            $response = Http::withHeaders($headers)
                ->withOptions([
                    'verify' => false, // Disable SSL verification temporarily for testing
                    'timeout' => 30, // Increase timeout to 30 seconds
                    'connect_timeout' => 30 // Increase connection timeout
                ])
                ->$method($this->api_url . $request_path);
                // dd($response->json());

            if ($response->successful()) {
                return $response->json();
            }

            // Handle specific error cases
            $error = $response->json();
            $errorMessage = $error['message'] ?? 'Unknown error occurred';
            $errorReason = $error['reason'] ?? 'UnknownError';
            
            Log::error('Uniwire API Error', [
                'endpoint' => $endpoint,
                'method' => $method,
                'error_reason' => $errorReason,
                'error_message' => $errorMessage,
                'status_code' => $response->status()
            ]);

            throw new \Exception($errorMessage, $response->status());

        } catch (\Exception $e) {
            Log::error('Uniwire API Error', [
                'message' => $e->getMessage(),
                'endpoint' => $endpoint,
                'method' => $method,
                'payload' => $payload,
                'url' => $this->api_url . $request_path
            ]);
            throw $e;
        }
    }

    public function deposit()
    {

        // $invoice = $this->makeRequest('profiles');
        // dd($invoice);
        // dd('ss');
        try {
            // Get supported currencies from config
            $supportedCurrencies = $this->getSupportedCurrencies();
            
            // Validate currency is supported
            if (!in_array($this->currency, $supportedCurrencies)) {
                throw new \Exception("Currency {$this->currency} is not supported");
            }
            // Create invoice with exact parameters from documentation
            $invoice = $this->makeRequest('invoices', [
                'profile_id' => $this->profile_id,
                'amount' => $this->final_amount,
                'currency' => base_currency(),
                'kind' => $this->getKindForCurrency($this->currency),
                'pricing_type' => 'fixed_price',
                'passthrough' => json_encode([
                    'transaction_id' => $this->txn,
                    'user_id' => $this->userId
                ])
            ], 'POST');

// Get invoice ID and construct URL
            $invoiceId = $invoice['result']['id'];
            $invoiceUrl = "https://uniwire.com/invoice/{$invoiceId}";
            // Store invoice data
            $this->txnInfo->update([
                'status' => TxnStatus::Pending,
                'approval_cause' => json_encode($invoice),
                'manual_field_data' => json_encode($invoice['result'])
            ]);

            
            return redirect()->away($invoiceUrl);

        } catch (\Exception $e) {
            $this->txnInfo->update([
                'status' => TxnStatus::Failed,
                'approval_cause' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function withdraw()
    {
        try {
            // Get withdrawal address from manual_field_data
            $fieldData = json_decode($this->txnInfo->manual_field_data, true);
            $withdrawalAddress = $fieldData['withdrawal_address'] ?? null;
            $destinationTag = $fieldData['destination_tag'] ?? null; // For Ripple/TON

            if (!$withdrawalAddress) {
                throw new \Exception('Withdrawal address not provided');
            }

            // Create payout with exact parameters from documentation
            $payload = [
                'profile_id' => $this->profile_id,
                'kind' => $this->getKindForCurrency($this->currency),
                'passthrough' => json_encode([
                    'transaction_id' => $this->txn,
                    'user_id' => $this->userId
                ]),
                'recipients' => [
                    [
                        'amount' => $this->amount,
                        'currency' => $this->currency,
                        'address' => $withdrawalAddress,
                        'notes' => "Withdrawal #{$this->txn}",
                        'destination_tag' => $destinationTag
                    ]
                ],
                'network_fee_pays' => 'merchant', // Merchant pays network fee
                'network_fee_preset' => 'normal' // Normal fee preset
            ];

            $payout = $this->makeRequest('payouts', $payload, 'POST');

            // Update transaction status
            $this->txnInfo->update([
                'status' => TxnStatus::Pending,
                'approval_cause' => json_encode($payout),
                'manual_field_data' => json_encode([
                    'payout_id' => $payout['result']['id'] ?? null,
                    'withdrawal_address' => $withdrawalAddress,
                    'destination_tag' => $destinationTag
                ])
            ]);

            return $payout;

        } catch (\Exception $e) {
            $this->txnInfo->update([
                'status' => TxnStatus::Failed,
                'approval_cause' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    protected function getSupportedCurrencies()
    {
        return [
            // Main Network Coins
            'BTC', 'LTC', 'XRP', 'DOGE', 'TON', 'SOL', 'ETH', 
            'ETH-BASE', 'ETH-ARBITRUM', 'POL', 'BNB', 'TRX', 'CELO',

            // Ethereum ERC-20 Tokens
            'ETH_USDT', 'ETH_USDC', 'ETH_TUSD', 'ETH_PAX', 'ETH_GUSD',
            'ETH_SAND', 'ETH_SHIB', 'ETH_BUSD', 'ETH_SHFL', 'ETH_cbBTC', 'ETH_USD1',

            // Polygon Tokens
            'USDT-POLYGON', 'USDC-POLYGON', 'USDCE-POLYGON',

            // Base Network Tokens
            'USDC-BASE', 'cbBTC-BASE',

            // Arbitrum Tokens
            'USDT-ARBITRUM', 'USDC-ARBITRUM', 'USDCE-ARBITRUM',

            // Celo Tokens
            'CELO-CELO', 'CUSD-CELO', 'USDT-CELO', 'USDC-CELO',

            // Tron TRC-20 Tokens
            'USDT-TRX', 'USDC-TRX',

            // Solana SPL Tokens
            'USDT-SOL', 'USDC-SOL', 'WSOL-SOL', 'BONK-SOL', 'TRUMP-SOL', 'JAMBO-SOL',

            // BSC BEP-20 Tokens
            'USDT-BSC', 'USDC-BSC', 'ETH-BSC', 'DAI-BSC', 'SHIB-BSC', 
            'BUSD', 'WBNB', 'USD1-BSC',

            // Liquid Network
            'L-BTC', 'L-USDT',

            // TON Tokens
            'USDT-TON', 'NOT-TON'
        ];
    }

    protected function getKindForCurrency($currency)
    {
        // Direct token kinds - return as is
        if (in_array($currency, [
            // Network-specific tokens
            'USDT-POLYGON', 'USDC-POLYGON', 'USDCE-POLYGON',
            'USDC-BASE', 'cbBTC-BASE',
            'USDT-ARBITRUM', 'USDC-ARBITRUM', 'USDCE-ARBITRUM',
            'CELO-CELO', 'CUSD-CELO', 'USDT-CELO', 'USDC-CELO',
            'USDT-TRX', 'USDC-TRX',
            'USDT-SOL', 'USDC-SOL', 'WSOL-SOL', 'BONK-SOL', 'TRUMP-SOL', 'JAMBO-SOL',
            'USDT-BSC', 'USDC-BSC', 'ETH-BSC', 'DAI-BSC', 'SHIB-BSC', 'BUSD', 'WBNB', 'USD1-BSC',
            'L-BTC', 'L-USDT',
            'USDT-TON', 'NOT-TON',
            // Ethereum ERC-20 tokens in their full form
            'ETH_USDT', 'ETH_USDC', 'ETH_TUSD', 'ETH_PAX', 'ETH_GUSD',
            'ETH_SAND', 'ETH_SHIB', 'ETH_BUSD', 'ETH_SHFL', 'ETH_cbBTC', 'ETH_USD1'
        ])) {
            return $currency;
        }

        // Map currency to appropriate kind for base currencies and shortened forms
        $kindMap = [
            // Main Network Coins
            'BTC' => 'BTC', // Default to standard format
            'BTC-P2SH' => 'BTC_P2SH', // P2SH Segwit addresses
            'BTC-BECH32' => 'BTC_BECH32', // Native Segwit addresses
            'BTC-LIGHTNING' => 'BTC_LIGHTNING', // Lightning Network
            'LTC' => 'LTC',
            'XRP' => 'XRP',
            'DOGE' => 'DOGE',
            'TON' => 'TON',
            'SOL' => 'SOL',
            'ETH' => 'ETH',
            'ETH-BASE' => 'ETH-BASE',
            'ETH-ARBITRUM' => 'ETH-ARBITRUM',
            'POL' => 'POL',
            'BNB' => 'BNB',
            'TRX' => 'TRX',
            'CELO' => 'CELO',

            // Short forms of tokens defaulting to specific networks
            'USDT' => 'ETH_USDT', // Default USDT to Ethereum network
            'USDC' => 'ETH_USDC', // Default USDC to Ethereum network
            'TUSD' => 'ETH_TUSD',
            'PAX' => 'ETH_PAX',
            'GUSD' => 'ETH_GUSD',
            'SAND' => 'ETH_SAND',
            'SHIB' => 'ETH_SHIB',
            'SHFL' => 'ETH_SHFL',
            'cbBTC' => 'ETH_cbBTC',
            'USD1' => 'ETH_USD1'
        ];

        return $kindMap[$currency] ?? $currency;
    }

    
} 