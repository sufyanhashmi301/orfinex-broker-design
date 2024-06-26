<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;

class ForexApiService
{
    protected $baseUrlReal;
    protected $apiKeyReal;
    protected $baseUrlDemo;
    protected $apiKeyDemo;

    public function __construct()
    {
        // $this->baseUrlReal = Setting::where('name', 'api_base_url')->value('val');
        // $this->apiKeyReal = Setting::where('name', 'api_key')->value('val');
        // prime broker credentials
    //        $this->baseUrlReal = 'http://92.204.253.130:4001/api';
    //        $this->apiKeyReal = 'PVTfAIPjQZ4GganFp6bCI0ni7p1YSAxM';
        $demoUrl = setting('mt5_api_url_demo','platform_api');
        $demoKey = setting('mt5_api_key_demo','platform_api');
        $this->baseUrlReal = setting('mt5_api_url_real','platform_api').'/api';
        $this->apiKeyReal = setting('mt5_api_key_real','platform_api');
        $this->baseUrlDemo = empty($demoUrl) ? setting('mt5_api_url_demo','platform_api') : setting('mt5_api_url_real','platform_api').'/api';
        $this->apiKeyDemo = isset($demoKey) ? setting('mt5_api_key_demo','platform_api') : setting('mt5_api_key_real','platform_api');
    }

    public function createUser($data)
    {
        $endpoint = 'user/Create';
        return $this->post($endpoint, $data);
    }
    public function createUserDemo($data)
    {
        $endpoint = 'user/Create';
        return $this->postDemo($endpoint, $data);
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
        $endpoint = 'user/get';
        return $this->getByBody($endpoint, $data);
    }

    public function getUserByEmail($data)
    {
        $endpoint = 'user/userbyemail';
        return $this->get($endpoint, $data);
    }

    public function getBalance($data)
    {
        $endpoint = 'useraccount/balance';
        return $this->get($endpoint, $data);
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

    protected function getByBody($endpoint, $params = [])
    {
        try {
            $URL = $this->baseUrlReal . '/' . $endpoint;

//            dd($URL,$params);
            $body = json_encode($params);
            $response = Http::withHeaders($this->getCommonHeadersReal())
//                ->retry(3, 100)
                ->withBody($body, 'application/json')->send('GET', $URL);
//                ->get($URL, $params);
//            dd($body,$response->object());

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    protected function get($endpoint, $params = [])
    {
        try {
            $URL = $this->baseUrlReal . '/' . $endpoint;
            $response = Http::withHeaders($this->getCommonHeadersReal())
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
            $URL = $this->baseUrlReal . '/' . $endpoint;
//        dd($URL,$params);
            $response = Http::withHeaders($this->getCommonHeadersReal())
                ->retry(3, 100)
                ->post($URL, $params);
//            dd($response->json(),$params);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }
    protected function postDemo($endpoint, $params = [])
    {
        try {
            $URL = $this->baseUrlDemo . '/' . $endpoint;
//        dd($URL,$params);
            $response = Http::withHeaders($this->getCommonHeadersDemo())
                ->retry(3, 100)
                ->post($URL, $params);
//            dd($response->json(),$params);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    protected function getCommonHeadersReal()
    {
        return [
            'SNC-X-API-KEY' => $this->apiKeyReal,
        ];
    }
    protected function getCommonHeadersDemo()
    {
        return [
            'SNC-X-API-KEY' => $this->apiKeyDemo,
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
