<?php

namespace App\Services;

use App\Models\User;
use App\Enums\TraderType;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentStat;
use App\Models\PlatformAccountCredential;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;

class MatchTraderTradingAccountService
{

  protected $matchTraderApiService;
  protected $accountTypeData;
  protected $phaseData;
  protected $ruleData;

  public function __construct(MatchTraderApiService $matchTraderApiService)
  {
    $this->matchTraderApiService = $matchTraderApiService;
  }

  /**
   * Create User Account on Trading Platform if user account not exists
   * Store the trading account credentials 
   */
  public function createUser($investment) {

    $user = User::find($investment->user_id);

    // Set Global Variables
    $this->accountTypeData = $investment->getAccountTypeSnapshotData();
    $this->phaseData = $investment->getPhaseSnapshotData();
    $this->ruleData = $investment->getRuleSnapshotData();

    // Check if the user on platform exists
    $user_exists_on_platform = PlatformAccountCredential::where('user_id', $user->id)->where('platform', TraderType::MT)->first();
    if($user_exists_on_platform) {
      // Create Trading Account
      return $this->createTradingAccount($user_exists_on_platform->data['uuid']);
    }

    $password = Str::random(12);
   
    // Create User Account on Match Trader Platform data
    $user_account_data = [
      "email" => $user->email,
      "password" => $password,
      "clientType" => "RETAIL",
      "createAsDepositedAccount" => false,
      "personalDetails" => [
          "firstname" => $user->first_name,
          "lastname" => $user->last_name
      ]
    ];

    $create_user_response = $this->matchTraderApiService->createUserAccount($user_account_data);

    // In case of error
    if(!$create_user_response) {
      return false;
    }

    // Add the record for account in CRM
    $user_platform_account = new PlatformAccountCredential();
    $user_platform_account->user_id = $user->id;
    $user_platform_account->platform = TraderType::MT;
    $user_platform_account->email = $user->email;
    $user_platform_account->password = $password;
    $user_platform_account->data = [
      'uuid' => $create_user_response['uuid']
    ];
    $user_platform_account->save();

    // Update Password in Account
    $investment->main_password = $password;
    $investment->save();

    // Create Trading Account
    return $this->createTradingAccount($create_user_response['uuid']);
  }

  /**
   * Create Trading Account
   */
  private function createTradingAccount($account_uuid) {
    $trading_account_data = [
      'offerUuid' => $this->accountTypeData['offer_uuid'],
      'account_uuid' => $account_uuid
    ];

    $create_trading_account_response = $this->matchTraderApiService->createForexTradingAccount($trading_account_data);

    if(!$create_trading_account_response) {
      return false;
    }

    return $create_trading_account_response['login'];
    
  }

  /**
   * Deposit Balance
   */
  public function depositBalance($login_id, $account) {
    $deposit_balance_data = [
      "systemUuid" => $this->accountTypeData['system_uuid'],
      "login" => $login_id,
      "amount" => $this->ruleData['allotted_funds'],
      "comment" => "Initial Deposit"
    ];

    $deposit_balance_response = $this->matchTraderApiService->depositBalance($deposit_balance_data);

    if(!$deposit_balance_response) {
      return false;
    }

    // // initialize stats records
    // $this->createInitialRecords($account);

    return true;
  }

  

}