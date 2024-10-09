<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;
use Brick\Math\BigDecimal;

class x9ApiService
{
    protected $baseUrlReal;
    protected $apiKeyReal;
    protected $baseUrlDemo;
    protected $apiKeyDemo;

    public function __construct()
    {
        $this->baseUrlReal = setting('x9_network_address', 'x9_api') . '/api/crm/';
        $this->apiKeyReal = setting('x9_API_access_key', 'x9_api');

//        $this->baseUrlReal ='https://shareafunds-5000.encrypted-gateway.com/api/crm/';
//        $this->apiKeyReal = 'CRegMvGeoX9O24nSHQ';

//        $demoServerEnabled = setting('demo_server_enable', 'platform_api');
//        $demoUrl = setting('mt5_api_url_demo', 'platform_api'). '/api';
//        $demoKey = setting('mt5_api_key_demo', 'platform_api');
//
//        if ($demoServerEnabled && $demoUrl) {
//            $this->baseUrlDemo = $demoUrl;
//            $this->apiKeyDemo = $demoKey;
//        } else {
//            $this->baseUrlDemo = $this->baseUrlReal;
//            $this->apiKeyDemo = $this->apiKeyReal;
//        }
    }

    public function createUser($data)
    {
        $endpoint = 'create_user';
//        dd($endpoint);
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
//        dd($endpoint,$data);
        return $this->post($endpoint, $data);
    }

    public function setCommissionOfAgent($data)
    {
        $endpoint = 'user/setCommission';
        return $this->post($endpoint, $data);
    }

    public function getClientGroupType()
    {
        $endpoint = 'client_group_types';
        return $this->get($endpoint);
    }

    public function getClientGroup($type)
    {
        $endpoint = 'client_groups_by_type/'.$type;
        return $this->get($endpoint);
    }

    public function getUserByEmail($data)
    {
        $endpoint = 'user/userbyemail';
        return $this->getByBody($endpoint, $data);
    }

    public function getBalance($data)
    {
        $endpoint = 'user/balance';
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

    public function balanceOperation($data)
    {
        $endpoint = 'user/balance';
        return $this->post($endpoint, $data);
    }
    public function transferFunds($data)
    {
        $endpoint = 'useraccount/transferfunds';
        return $this->post($endpoint, $data);
    }

    public function creditOperation($data)
    {
        $endpoint = 'useraccount/creditOperation';
        return $this->post($endpoint, $data);
    }

    public function bonusOperation($data)
    {
        $endpoint = 'useraccount/bonusOperation';
        return $this->post($endpoint, $data);
    }

    public function balanceOperationDemo($data)
    {
        $endpoint = 'useraccount/balanceOperation';
        return $this->postDemo($endpoint, $data);
    }

    public function getValidatedBalance($data)
    {
        $response = $this->getBalance($data);
        if ($response['success'] === true) {
            return BigDecimal::of($response['result']['equity'])->minus($response['result']['credit']);
        }
        return BigDecimal::of(0);
    }


    // Risk Score APIs
    public function getTotalRiskScore($data)
    {
        $endpoint = 'Risk/total/score';
        return $this->post($endpoint, $data);
    }

    public function getWeekRiskScore($data)
    {
        $endpoint = 'Risk/week/score';
        return $this->post($endpoint, $data);
    }

    public function getTodayRiskScore($data)
    {
        $endpoint = 'Risk/today/score';
        return $this->post($endpoint, $data);
    }

    // Positions APIs
    public function getPositionsByGroup($group)
    {
        $endpoint = 'Position/list/group';
        $params = ['group' => $group];
        return $this->get($endpoint, $params);
    }
// Summary of Positions by Client API
    public function getClientPositionSummary($login)
    {
        $endpoint = 'Position/summary';
        $data = ['login' => $login];
        return $this->post($endpoint, $data);
    }

// Summary of Positions by Group API
    public function getGroupPositionSummary($group)
    {
        $endpoint = 'Position/summarygroup';
        $data = ['group' => $group];
        return $this->post($endpoint, $data);
    }
// Positions by Days API
    public function getPositionsByDays($days)
    {
        $endpoint = 'Position/list/positionDays';
        $data = ['Days' => $days];
        return $this->post($endpoint, $data);
    }


    protected function getByBody($endpoint, $params = [])
    {
        try {
            $URL = $this->baseUrlReal . '/' . $endpoint;
            $body = json_encode($params);
            $response = Http::withHeaders($this->getCommonHeadersReal())
                ->withBody($body, 'application/json')
                ->send('GET', $URL);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    protected function get($endpoint, $params = [])
    {
//        try {
            $URL = $this->baseUrlReal . $endpoint;
//            dd($URL,$params,$this->getCommonHeadersReal());
            $response = Http::withHeaders($this->getCommonHeadersReal())
                ->retry(3, 100)
                ->get($URL, $params);

//            dd($response);
            return $this->handleResponse($response);
//        } catch (RequestException $e) {
//            return $this->handleException($e);
//        }
    }

    protected function post($endpoint, $params = [])
    {
//        try {
            $URL = $this->baseUrlReal  . $endpoint;
            $body = json_encode($params);
//            dd($URL,$body);
            $response = Http::withHeaders($this->getCommonHeadersReal())
                ->retry(3, 100)
                ->withBody($body, 'application/json')
                ->post($URL);
//            $response = Http::withHeaders($this->getCommonHeadersReal())
////                ->retry(3, 100)
////                ->withBody($body, 'application/json')
//                ->post($URL,$body);
//            dd($URL,$body,$response->json());

            return $this->handleResponse($response);
//        } catch (RequestException $e) {
//            return $this->handleException($e);
//        }
    }

    protected function postDemo($endpoint, $params = [])
    {
        try {
            $URL = $this->baseUrlDemo . '/' . $endpoint;
            $body = json_encode($params);
            $response = Http::withHeaders($this->getCommonHeadersDemo())
                ->retry(3, 100)
                ->withBody($body, 'application/json')
                ->post($URL);

            return $this->handleResponse($response);
        } catch (RequestException $e) {
            return $this->handleException($e);
        }
    }

    protected function getCommonHeadersReal()
    {
        return [
            'x-access-token' => $this->apiKeyReal,
        ];
    }

    protected function getCommonHeadersDemo()
    {
        return [
            'x-access-token' => $this->apiKeyDemo,
        ];
    }

    protected function handleResponse($response)
    {
//        dd($response->json());
//        $statusCode = $response->json('statusCode');
//        $messages = $response->json('messages');
//        $result = $response->json('result');

//        if ($statusCode === 200) {
            return [
                'success' => true,
//                'statusCode' => $statusCode,
//                'messages' => $messages,
                'result' => $response->json()
            ];
//        }

//        return [
//            'success' => false,
//            'statusCode' => $statusCode,
//            'messages' => $messages,
//            'result' => $result
//        ];
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
