<?php

namespace sumsub;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class SumsubClient
{
    protected const BASE_URL = 'https://api.sumsub.com';

    protected string $appToken;
    protected string $secretKey;

    public function __construct(string $appToken, string $secretKey)
    {
        $this->appToken = $appToken;
        $this->secretKey = $secretKey;
    }

    public function createApplicant(string $externalUserId, string $levelName): string
    {
        $requestBody = [
            'externalUserId' => $externalUserId
        ];

        $url = '/resources/applicants?' . http_build_query(['levelName' => $levelName]);
//dd($url);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-App-Token' => $this->appToken,
            'X-App-Access-Sig' => $this->createSignature('post', $url, $requestBody),
            'X-App-Access-Ts' => time(),
        ])->post(self::BASE_URL . $url, $requestBody);

        $body = $this->parseBody($response);
//        dd($body);
        return $body['id'];
    }

    public function addDocument(
        string $applicantId,
        string $filePath,
        array $metadata,
    ): string {
        $multipart = [
            [
                'name' => 'metadata',
                'contents' => json_encode($metadata)
            ],
            [
                'name' => 'content',
                'contents' => fopen($filePath, 'r')
            ],
        ];

        $url = '/resources/applicants/' . urlencode($applicantId) . '/info/idDoc';

        $response = Http::attach($multipart)
            ->withHeaders([
                'X-App-Token' => $this->appToken,
                'X-App-Access-Sig' => $this->createSignature('post', $url, $multipart),
                'X-App-Access-Ts' => time(),
            ])->post($url,$multipart);

        return $response->header('X-Image-Id')[0] ?? '';
    }

    public function getApplicantStatus(string $applicantId): array
    {
        $url = '/resources/applicants/' . urlencode($applicantId) . '/requiredIdDocsStatus';

        $response = Http::withHeaders([
            'X-App-Token' => $this->appToken,
            'X-App-Access-Sig' => $this->createSignature('get', $url, []),
            'X-App-Access-Ts' => time(),
        ])->get(self::BASE_URL . $url);

        return $this->parseBody($response);
    }

    public function getAccessToken(string $externalUserId, string $levelName): array
    {
        $url = '/resources/accessTokens?' . http_build_query(['userId' => $externalUserId, 'levelName' => $levelName]);

        $response = Http::withHeaders([
            'X-App-Token' => $this->appToken,
            'X-App-Access-Sig' => $this->createSignature('post', $url, []),
            'X-App-Access-Ts' => time(),
        ])->post(self::BASE_URL . $url);

        return $this->parseBody($response);
    }

    protected function parseBody(object $response): array
    {
        $data = (string)$response->body();
        $json = json_decode($data, true, JSON_THROW_ON_ERROR);
        if (!is_array($json)) {
            throw new RuntimeException('Invalid response received: ' . $data);
        }

        return $json;
    }

    protected function createSignature(string $method, string $url, array $data): string
    {
        $body = json_encode($data);
        return hash_hmac('sha256', time() . strtoupper($method) . $url . $body, $this->secretKey);
    }
}
