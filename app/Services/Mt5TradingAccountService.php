<?php

namespace App\Services;
use Illuminate\Support\Str;


use Illuminate\Http\Client\RequestException;

class Mt5TradingAccountService 
{

  protected $forexApiService;
  protected $accountTypeData;
  protected $phaseData;
  protected $ruleData;

  public function __construct(ForexApiService $forexApiService)
  {
    $this->forexApiService = $forexApiService;
  }

  private function createUserApiCall($user_data) {
    try {
      $response = $this->forexApiService->createUser($user_data);
      return $response;
    } catch (RequestException $e) {
        abort($e->getCode(), 'API Response Error. Please try again later.');
    }
  }

  /**
   * Create MT5 account Via API call using ForexApiService
   */
  public function createTradingAccount($investment) {

    $this->accountTypeData = $investment->getAccountTypeSnapshotData();
    $this->phaseData = $investment->getPhaseSnapshotData();
    $this->ruleData = $investment->getRuleSnapshotData();

    $password = Str::random(11) . '$';
   
    // will replace the values by static table later
    $user_data = [
      "login" => 0,
      "group" => $investment->platform_group,
      "firstName" => 'Phase ' . $investment->getPhaseSnapshotData()['phase_step'] . ' $' . $this->ruleData['allotted_funds'] . '-' . $investment->user->first_name,
      "middleName" => "",
      "lastName" => $investment->user->last_name,
      "leverage" => (int)$this->accountTypeData['leverage'],
      "rights" => "USER_RIGHT_ENABLED,USER_RIGHT_EXPERT,USER_RIGHT_PASSWORD", // "USER_RIGHT_ALL",
      "country" => $investment->user->country,
      "city" => $investment->user->city ?? '',
      "state" => "",
      "zipCode" => $investment->user->zip_code ?? '',
      "address" => $investment->user->address ?? '',
      "phone" => $investment->user->phone,
      "email" => $investment->user->email,
      "agent" => 0,
      "company" => setting('site_title', 'global'),
      "account" => "",
      "language" => 0,
      "phonePassword" => 'string', // SNNH@2024@bol
      "status" => "RE",
      "masterPassword" => $password,
      "investorPassword" => 'Abc12345$' // SNNH@2024@bol
    ];

    $retryCount = 0;
    $maxRetries = 3;
    
    // Start a loop that will execute up to 3 times
    try {

      do {
          // Make the API call
          $response = $this->createUserApiCall($user_data);

          // Check if the API response was not successful
          if ($response['statusCode'] == 400) {
              // if 400 error then retry
              $retryCount++;

              // Sleep for 1 second before retrying
              sleep(1);
          } else {
              // If the response is successful or the error is not "Account already exists", break the loop
              break;
          }

      // Continue the loop while the retry counter is less than the maximum retries
      } while ($retryCount < $maxRetries);

    } catch (RequestException $e) {
      abort($e->getCode(), 'API Response Error. Please try again later.');
    }
    

    // if it still fails after $maxRetries tries
    if ($response['result'] == null) {
      abort(400, 'API Response Error. Please try again later.');
    } 


    if ($response['success']) {



      $resResult = $response['result'];
      $mt5_login = $resResult['login'];
      
      if ($mt5_login && $resResult['responseCode'] == 0) {
        
        $user_rights_data =  [
          "login" => $mt5_login,
          "rights" => 'USER_RIGHT_ENABLED',
        ];
        $user_rights_response = $this->forexApiService->setUserRights($user_rights_data);
        
        $investment->main_password = $password;
        $investment->save();
        
        return $mt5_login;
      }
      
    }
    

  }


  /**
   * Use to deposit starting balance in Trading Account
   */
  public function depositBalance($login_id) {

    // will replace it by static table
    $deposit_amount = $this->ruleData['allotted_funds'];

    $data = [
        'login' => $login_id,
        'Amount' => $deposit_amount,
        'type' => 1, // deposit
        'TransactionComments' => 'Initial Deposit'
    ];
  
    $response = $this->forexApiService->balanceOperation($data);

    if ($response['success'] && $response['result']['responseCode'] == 10009) {
        return true;
    } else {
        return false;
    }

  }

}