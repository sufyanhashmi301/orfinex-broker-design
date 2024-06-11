<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;

class ForexApiService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        // $this->baseUrl = Setting::where('name', 'api_base_url')->value('val');
        // $this->apiKey = Setting::where('name', 'api_key')->value('val');
        $this->baseUrl = 'http://92.204.253.130:5001/api';
        $this->apiKey = 'PVTfAIPjQZ4GganFp6bCI0ni7p1YSAxM';
    }

    public function createUser($data)
    {
        $endpoint = 'user/Create';
        return $this->post($endpoint, $data);
    }

    public function updateUser($data)
    {
        $endpoint = 'user/Update';
        return $this->post($endpoint, $data);
    }

    public function resetMasterPassword($data)
    {
        $endpoint = 'user/ResetMasterPassword';
        return $this->post($endpoint, $data);
    }

    public function resetInvestorPassword($data)
    {
        $endpoint = 'user/ResetInvestorPassword';
        return $this->post($endpoint, $data);
    }

    public function setUserRights($data)
    {
        $endpoint = 'user/SetRights';
        return $this->post($endpoint, $data);
    }

    public function setUserLeverage($data)
    {
        $endpoint = 'user/updateLeverage';
        return $this->post($endpoint, $data);
    }

    public function updateUserGroup($data)
    {
        $endpoint = 'user/updateMT5Group';
        return $this->post($endpoint, $data);
    }

    public function updateAgentAccount($data)
    {
        $endpoint = 'user/updateAgentAccount';
        return $this->post($endpoint, $data);
    }

    public function setCommissionOfAgent($data)
    {
        $endpoint = 'user/setCommission';
        return $this->post($endpoint, $data);
    }

    public function getUserList($data)
    {
        $endpoint = 'user/list';
        return $this->get($endpoint, $data);
    }

    public function getUserByLogin($data)
    {
        $endpoint = 'user/getByLogin';
        return $this->get($endpoint, $data);
    }

    public function getUserByEmail($data)
    {
        $endpoint = 'user/userbyemail';
        return $this->get($endpoint, $data);
    }

    public function getBalance($data)
    {
        $endpoint = 'useraccount/balance';
        return $this->post($endpoint, $data);
    }

    public function getUserBalanceByGroup($data)
    {
        $endpoint = 'useraccount/list';
        return $this->post($endpoint, $data);
    }

    public function getOrders($data)
    {
        $endpoint = 'order/list/user';
        return $this->get($endpoint, $data);
    }

    public function getBalanceReport($data)
    {
        $endpoint = 'useraccount/getBalanceReport';
        return $this->get($endpoint, $data);
    }

    protected function get($endpoint, $params = [])
    {
        try {
            $URL = $this->baseUrl . '/' . $endpoint;
            $response = Http::withHeaders($this->getCommonHeaders())
                ->retry(3, 100)
                ->get($URL, $params);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    protected function post($endpoint, $params = [])
    {
        try {
            $URL = $this->baseUrl . '/' . $endpoint;
//        dd($URL,$params);
            $response = Http::withHeaders($this->getCommonHeaders())
                ->retry(3, 100)
                ->post($URL, $params);
//            dd($response->json(),$params);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    protected function getCommonHeaders()
    {
        return [
            'SNC-X-API-KEY' => $this->apiKey,
        ];
    }

    protected function handleResponse($response)
    {
        $statusCode = $response->json('statusCode');
        $messages = $response->json('messages');
        $result = $response->json('result');

        if ($statusCode === 200) {
            return [
                'success' => true,
                'statusCode' => $statusCode,
                'messages' => $messages,
                'result' => $result
            ];
        }

        return [
            'success' => false,
            'statusCode' => $statusCode,
            'messages' => $messages,
            'result' => $result
        ];
    }

    protected function handleException(RequestException $e)
    {
        if ($e->hasResponse()) {
            $response = $e->getResponse();
            $responseBody = json_decode($response->getBody()->getContents(), true);

            return [
                'success' => false,
                'statusCode' => $response->getStatusCode(),
                'messages' => $responseBody['messages'] ?? $response->getBody()->getContents(),
                'result' => $responseBody['result'] ?? false,
            ];
        }

        return [
            'success' => false,
            'message' => $e->getMessage(),
        ];
    }
}
