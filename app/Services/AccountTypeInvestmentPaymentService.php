<?php

namespace App\Services;

use App\Enums\TraderType;
use Carbon\CarbonImmutable;
use App\Enums\InvestmentStatus;
use App\Models\AccountType;
use App\Models\AccountTypeInvestment;

class AccountTypeInvestmentPaymentService
{

  protected $forexApiService;
  protected $accountTypeData;
  protected $phaseData;
  protected $ruleData;

  public function __construct(ForexApiService $forexApiService)
  {
    $this->forexApiService = $forexApiService;
  }

  /**
   * Create MT5 account Via API call using ForexApiService
   * createTradingAccount() helper
   */
  private function createMT5Account($investment, $login_id, $password) {

    // will replace the values by static table later
    $user_data = [
      "login" => $login_id,
      "group" => $investment->platform_group,
      "firstName" => 'Phase 1 $' . $this->ruleData['allotted_funds'] . '-' . $investment->user->first_name,
      "middleName" => "",
      "lastName" => $investment->user->last_name,
      "leverage" => $this->accountTypeData['leverage'],
      "rights" => "USER_RIGHT_ALL",
      "country" => $investment->user->country,
      "city" => $investment->user->city,
      "state" => "",
      "zipCode" => $investment->user->zip_code,
      "address" => $investment->user->address,
      "phone" => $investment->user->phone,
      "email" => $investment->user->email,
      "agent" => 0,
      "company" => setting('site_title', 'global'),
      "account" => "",
      "language" => 0,
      "phonePassword" => 'SNNH@2024@bol',
      "status" => "RE",
      "masterPassword" => $password,
      "investorPassword" => 'SNNH@2024@bol'
    ];

    // dd($user_data);

    // API call for creating MT5 user
    $response = $this->forexApiService->createUser($user_data);
    

    if ($response['success']) {
        $resResult = $response['result'];
        $mt5_login = $resResult['login'];

        if ($mt5_login && $resResult['responseCode'] == 0) {
            $user_rights_data =  [
                "login" => $mt5_login,
                "rights" => 'USER_RIGHT_ENABLED',
            ];
            $this->forexApiService->setUserRights($user_rights_data);

            $investment->main_password = $password;
            $investment->save();
            return $mt5_login;
        }

    }


  }


  /**
   * Use to deposit starting balance in Trading Account
   * investmentActive helper()
   */
  private function tradingAccountDeposit($login_id, $investment) {

    // will replace it by static table
    $deposit_amount = $this->ruleData['allotted_funds'];

    $data = [
        'login' => $login_id,
        'Amount' => $deposit_amount,
        'type' => 1, //deposit
        'TransactionComments' => 'Initial Deposit'
    ];
  
    $response = $this->forexApiService->balanceOperation($data);

    if ($response['success'] && $response['result']['responseCode'] == 10009) {
        return true;
    } else {
        return false;
    }

  }

  /**
   * General method to create tradding account for MT5 and others
   * investmentActive() helper
   */
  private function createTradingAccount($investment) {
    $password = generate_dummy_password();
    
    $phase_ids = AccountType::find($this->accountTypeData['id'])->accountTypePhases->pluck('id');
  
    $latest_investments = AccountTypeInvestment::whereHas('accountTypePhaseRule', function($query) use ($phase_ids) {
        $query->whereIn('account_type_phase_id', $phase_ids);
      })->whereIn('status', [InvestmentStatus::ACTIVE, InvestmentStatus::VIOLATED])->latest('login')->first();

    // Suggesting the login ID of investment w.r.t account range
    if($latest_investments) {
        
        if(isset($latest_investments->login) && $latest_investments->login >= $investment->accountTypePhaseRule->accountTypePhase->accountType->accounts_range_end){
            
            $message = __('The account creation range is completed of :title account type.', ['title'=> $this->accountTypeData['title']]);
            notify()->error($message, 'Error');
            return redirect()->back();

        }elseif(isset($latest_investments->login)) {

            $login = ++$latest_investments->login;
            $investment_for_login = AccountTypeInvestment::where('login', $login)->exists();

            if($investment_for_login){
                ++$login;
            }

        }

    }else{
        $login = $this->accountTypeData['accounts_range_start'];
    }

    if($investment->trader_type == TraderType::MT5) {
      return $this->createMT5Account($investment, $login, $password);
    }

  }

  /**
   * Main function
   */
  public function investmentActive($account_type_investment_id){

    $investment = AccountTypeInvestment::findOrFail($account_type_investment_id);
    $account_type = $investment->accountTypePhaseRule->accountTypePhase->accountType;

    // set the snapshot data of the investment
    $snapshot = $investment->accountTypeInvestmentSnapshot;
    $this->accountTypeData = $snapshot->account_types_data;
    $this->phaseData = $snapshot->account_types_phases_data;
    $this->ruleData = $snapshot->account_types_phases_rules_data;  
    
    
    // create trading account
    $trading_account_login_id = $this->createTradingAccount($investment);
    
    // deposit balance to trading account
    if($investment->trader_type == TraderType::MT5) {
      $deposit = $this->tradingAccountDeposit($trading_account_login_id, $investment);
    }

    // If deposit is successful, update the Investment table
    if ($deposit) {
      $time_now = CarbonImmutable::now();

      $investment->account_name = $this->accountTypeData['title'] .'_'. $investment->id;
      $investment->login = $trading_account_login_id;
      $investment->status = InvestmentStatus::ACTIVE;
      $investment->phase_started_at = $time_now;

      $investment->save();

    }

    return $investment;

  }

}