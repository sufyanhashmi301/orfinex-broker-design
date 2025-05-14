<?php
namespace Payment\Match2pay;

use Txn;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Payment\Transaction\BaseTxn;
use Illuminate\Support\Facades\Hash;

class Match2payTxn extends BaseTxn
{
    private $baseUrl;
    private $client;
    private $txnInfo;
    private $payCurrency;
    private $apiToken;
    private $apiSecretKey;
    private $gatewayName;

    public function __construct($txnInfo)
    {
        parent::__construct($txnInfo);
        $credentials = gateway_info('match2pay');

        // Set the base URL for staging environment
        $this->baseUrl = $credentials->base_url;
        $this->client = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json'
            ],
        ]);

        // Define required credentials
        $this->txnInfo = $txnInfo;
        $this->amount = $txnInfo->final_amount;
        $this->payCurrency = $txnInfo->pay_currency;
        $this->apiToken = $credentials->api_token;
        $this->apiSecretKey = $credentials->secret_key;


        // Set the gateway based on the currency
        $this->gatewayName = $this->getPaymentGatewayName($this->payCurrency);
    }

    /**
     * Handle the deposit request for Match2Pay crypto_agent
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deposit()
    {
        // Generate timestamp in seconds
        $timestamp = Carbon::now()->timestamp;

        // Prepare the payload according to Match2Pay staging deposit request
        $payload = [
            'amount' => $this->amount,                 // Amount to deposit              // Final currency (USD)
            'currency' => base_currency(),                       // Final currency (USD)
            'paymentGatewayName' => $this->gatewayName, // Payment gateway used for crypto deposits
            'paymentCurrency' => $this->payCurrency,   // Payment currency (e.g., USX)
            'callbackUrl' => url('/') . '/ipn/match2pay',       // Callback URL for handling the response
            'apiToken' => $this->apiToken,             // API token for authorization
            'timestamp' => $timestamp,                 // Current timestamp
            'tradingAccountLogin' => Str::random(),       // Trading account login (transaction/order ID)
        ];
        // Generate signature using payload and secret key
        $payload['signature'] = $this->generateSignature($payload);
        
        // Send the deposit request to Match2Pay API
        $response = $this->client->request('POST', $this->baseUrl . '/api/v2/deposit/crypto_agent', [
            'body' => json_encode($payload),
        ]);
        
        // Parse the response
        $data = json_decode($response->getBody()->getContents(), true);
        $transaction =  $this->txnInfo;
        $transaction->tnx = $data['paymentId'];
        $transaction->manual_field_data = $data;
        $transaction->save();

        if(str_contains($data['checkoutUrl'], 'pp-staging')) {
            $data['checkoutUrl'] = str_replace('pp-staging', 'pp-staging-micro', $data['checkoutUrl']);
        }

        if(str_contains($data['checkoutUrl'], 'localhost')) {
            $data['checkoutUrl'] = str_replace('https', 'http', $data['checkoutUrl']);
        }

        // if match2pay then return the url to ajax for iframe
        // if(str_contains($this->gatewayName, 'match2')) {
        //     return $data['checkoutUrl'];
        // }
    
        // Handle response (get the checkout URL and other details)
        if (isset($data['checkoutUrl'])) {
            $checkoutUrl = $data['checkoutUrl'];
            return redirect($checkoutUrl);
            // return $checkoutUrl;
        }

        // Handle errors or missing fields if necessary
        throw new \Exception('Unable to generate checkout URL: ' . json_encode($data));
    }

    /**
     * Generate a signature using the payload and secret key
     *
     * @param array $payload
     * @return string Signature
     */
    private function generateSignature(array $payload)
    {
        // Sort payload by keys in A-Z order
        ksort($payload);

        // Concatenate payload values according to sorted keys, excluding 'signature'
        $payloadString = '';
        foreach ($payload as $key => $value) {
            if ($key !== 'signature') { // Exclude the signature field from the string
                $payloadString .= $value;
            }
        }

        // Append the API secret key to the concatenated string
        $payloadString .= $this->apiSecretKey;

        // Generate the SHA-384 hash of the concatenated string
        return hash('sha384', $payloadString);
    }

    /**
     * Map the pay_currency to the corresponding payment gateway name
     *
     * @param string $currency
     * @return string
     */
    private function getPaymentGatewayName($currency)
    {
        // Define the mapping of payment currencies to their gateway names
        $currencyGatewayMap = [
            'BTC' => 'BTC',
            'ETH' => 'ETH',
            'UST' => 'USDT ERC20',
            'UCC' => 'USDC ERC20',
            'TRX' => 'TRX',
            'USX' => 'USDT TRC20',
            'UCX' => 'USDC TRC20',
            'BNB' => 'BNB',
            'USB' => 'USDT BEP20',
            'MAT' => 'MATIC',
            'USP' => 'USDT POLYGON',
            'UCP' => 'USDC POLYGON',
            'XRP' => 'XRP',
            'DOG' => 'DOGECOIN',
            'LTC' => 'LTC',
            'SOL' => 'SOL',
            'USS' => 'USDT SOL',
            'UCS' => 'USDC SOL',
            'TON' => 'TON',
            'UTT' => 'USDT TON',
        ];

        // Return the payment gateway name based on the pay_currency, or default to 'USDT TRC20'
        return $currencyGatewayMap[$currency] ?? 'USDT TRC20';
    }
}

