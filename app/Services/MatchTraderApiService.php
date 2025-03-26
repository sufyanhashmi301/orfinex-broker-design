<?php

namespace App\Services;

use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class MatchTraderApiService
{
  protected $baseUrlReal;
  protected $apiKeyReal;

  public function __construct()
  {
    $this->baseUrlReal = setting('mt_network_address_real', 'match_trader_platform_api') . '/' . setting('mt_api_version_real', 'match_trader_platform_api');
    $this->apiKeyReal = setting('mt_api_key_real', 'match_trader_platform_api');
  }

  /**
   * Make GET, POST, PUT request
   */
  private function request($type, $endpoint, $params = [])
  {
      $headers = [
        'Authorization' => $this->apiKeyReal,
      ];
      
      $url = $this->baseUrlReal . '/' . $endpoint;

      try { 

        // GET Request
        if($type == 'get') {
          $response = Http::withHeaders($headers)
                          ->retry(3, 100)
                          ->get($url, $params);
        } 
        
        // POST Request
        elseif ($type == 'post') {
          $body = json_encode($params);
          $response = Http::withHeaders($headers)
                          ->retry(3, 100)
                          ->withBody($body, 'application/json')
                          ->post($url);
        }

        // No response but success
        if($response->getStatusCode() === 204) {
          return ['status' => 'success'];
        }

      } catch (RequestException $e) {
        $error = (array) json_decode($e->response);
        
        if(in_array($error['status'], [400, 401, 403, 409, 500, 503])) {
          notify()->error('Error ' . $error['status'] . ': ' . $error['title']);
        } else {
          notify()->error('Unknown error occured! Contact Support.');
        }

        return false;

      } 

      return (array) json_decode($response);

  }

  // -------- API Endpoints --------
  
  // Get All Offers
  public function getAllOffers($data = [])
  {
      $endpoint = 'offers';
      return $this->request('get', $endpoint, $data);
  }

  // Create User Account
  public function createUserAccount($data = []){
      $endpoint = 'accounts';
      return $this->request('post', $endpoint, $data);
  }

  // Create Trading Account
  public function createForexTradingAccount($data = []) {
    $endpoint = 'accounts/' . $data['account_uuid'] .'/trading-accounts';
    return $this->request('post', $endpoint, $data);
  }

  // Deposit Balance
  public function depositBalance($data = []) {
      $endpoint = 'credit/in';
      return $this->request('post', $endpoint, $data);
  }

  // Deduct Balance
  public function deductBalance($data = []) {
    $endpoint = 'credit/out';
    return $this->request('post', $endpoint, $data);
}
  
  // Get All Trading Accounts
  public function getAllForexTradingAccounts($data = []) {
      $endpoint = 'trading-accounts';
      return $this->request('get', $endpoint, $data);
  }

}
