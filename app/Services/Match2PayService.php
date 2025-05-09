<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Match2PayService
{
    protected $url;
    protected $key;
    protected $secret;

    public function __construct()
    {
        $this->url = config('services.match2pay.url');
        $this->key = config('services.match2pay.key');
        $this->secret = config('services.match2pay.secret');
    }

    protected function generateSignature(array $data): string
    {
        unset($data['signature']); // Make sure signature isn't included
        ksort($data); // Sort keys alphabetically

        $concatenated = '';
        foreach ($data as $value) {
            $concatenated .= $value;
        }

        $concatenated .= $this->secret;
        
        return hash('sha384', $concatenated);
    }

    public function createCryptoAgentDeposit(array $requestData)
    {
        $requestData['apiToken'] = $this->key;
        $requestData['timestamp'] = time();
        $requestData['callbackUrl'] = url('/').'/ipn/match2pay';

        // Generate and attach signature
        $requestData['signature'] = $this->generateSignature($requestData);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post("{$this->url}/api/v2/deposit/crypto_agent", $requestData);

        return $response->json();
    }
}
