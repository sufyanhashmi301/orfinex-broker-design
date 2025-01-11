<?php

namespace App\Services;

use App\Enums\TraderType;
use App\Models\AccountType;
use App\Traits\NotifyTrait;
use Carbon\CarbonImmutable;
use App\Enums\InvestmentStatus;
use App\Models\AccountTypeInvestment;
use App\Enums\InvestmentPhaseApproval;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\TradingStatsRunCommandsJob;
use Illuminate\Http\Client\RequestException;

class AccountTypeInvestmentPaymentService
{

  use NotifyTrait;

  protected $investment_phase_approve;
  protected $forexApiService;
  protected $accountTypeData;
  protected $phaseData;
  protected $ruleData;
  public $affiliate;

  public function __construct(ForexApiService $forexApiService, InvestmentPhaseApprovalService $investment_phase_approve, UserAffiliateService $userAffiliate)
  {
    $this->investment_phase_approve = $investment_phase_approve;
    $this->forexApiService = $forexApiService;
    $this->affiliate = $userAffiliate;
  }

  private function createUserApiCall($user_data) {
    try {
      $response = $this->forexApiService->createUser($user_data);
      return $response;
    } catch (RequestException $e) {
        abort($e->getCode());
    }
  }

  /**
   * Create MT5 account Via API call using ForexApiService
   * createTradingAccount() helper
   */
  private function createMT5Account($investment, $login_id, $password) {
    // dd($login_id);
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

    // dd($user_data);

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
      abort($e->getCode());
    }

    // if it still fails after $maxRetries tries
    if ($response['result'] == null) {
      abort(400);
    } 

    if ($response['success']) {
        $resResult = $response['result'];
        $mt5_login = $resResult['login'];

        if ($mt5_login && $resResult['responseCode'] == 0) {
            // No need to set rights individually
            // $user_rights_data =  [
            //     "login" => $mt5_login,
            //     "rights" => 'USER_RIGHT_ENABLED',
            // ];
            // $user_rights_response = $this->forexApiService->setUserRights($user_rights_data);
            // if($user_rights_response['success'] == false){
            //   dd($response);
            // }
            
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
    // if($response['success'] == false){
    //   dd($response);
    // }

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
      })->whereIn('status', [InvestmentStatus::ACTIVE, InvestmentStatus::VIOLATED, InvestmentStatus::PASSED])->latest('login')->first();

    // Suggesting the login ID of investment w.r.t account range
    // if($latest_investments) {
        
    //     if(isset($latest_investments->login) && $latest_investments->login >= $investment->accountTypePhaseRule->accountTypePhase->accountType->accounts_range_end){
            
    //         $message = __('The account creation range is completed of :title account type.', ['title'=> $this->accountTypeData['title']]);
    //         notify()->error($message, 'Error');
    //         return redirect()->back();

    //     }elseif(isset($latest_investments->login)) {

    //         $login = ++$latest_investments->login;
    //         $investment_for_login = AccountTypeInvestment::where('login', $login)->exists();

    //         if($investment_for_login){
    //             ++$login;
    //         }

    //     }

    // }else{
    //     $login = $this->accountTypeData['accounts_range_start'];
    // }
      $login = 0;
    

    if($investment->trader_type == TraderType::MT5) {
      return $this->createMT5Account($investment, $login, $password);
    }

  }

  /**
   * Main function
   */
  public function investmentActive($account_type_investment_id, $data = []){

    // abort(400);

    $investment = AccountTypeInvestment::findOrFail($account_type_investment_id);

    // set the snapshot data of the investment
    $snapshot = $investment->accountTypeInvestmentSnapshot;
    $this->accountTypeData = $investment->getAccountTypeSnapshotData();
    $this->phaseData = $investment->getPhaseSnapshotData();
    $this->ruleData = $investment->getRuleSnapshotData();
    
    // create trading account
    $trading_account_login_id = $this->createTradingAccount($investment);
    
    // deposit balance to trading account
    if($investment->trader_type == TraderType::MT5) {
      $deposit = $this->tradingAccountDeposit($trading_account_login_id, $investment);
    }

    // If deposit is successful, update the Investment table and add the record to investment_phase_approvals_table
    if ($deposit) {

      $time_now = CarbonImmutable::now();

      $investment->account_name = $this->accountTypeData['title'] .'_'. $investment->id;
      $investment->login = $trading_account_login_id;
      $investment->status = InvestmentStatus::ACTIVE;
      $investment->phase_started_at = $time_now;

      $investment->save();

      // apply commissions
      $this->affiliate->applyCommission($this->ruleData['id'], $investment->user_id);

      // send mail if user promoted to next phase
      $this->doEmail('phase_promotion_email', $investment, $data);

      // send email to user
      $this->doEmail('new_account_email', $investment);

    }

    // Fetch and store latest stats and hourly stats (Moved to command)
  

    return $investment;

  }

  /**
   * Do necessary Emails related to account buy
   */
  private function doEmail($slug, $investment, $data = []) {
    
    // New account Email
    if($slug == "new_account_email") {
      $shortcodes2 = [
        '[[full_name]]' => $investment->user->first_name . ' ' . $investment->user->last_name,
        '[[account_login]]' => $investment->login,
        '[[account_password]]' => $investment->main_password,
        '[[server]]' => setting('live_server', 'platform_api'),
      ];
      $this->mailNotify($investment->user->email, 'new_account_details', $shortcodes2);
    }
    
    //  Promotion Email
    if($slug == "phase_promotion_email") {
      if($data['phase_promotion'] ?? false) {
        $shortcodes['[[full_name]]'] = $investment->user->first_name . ' ' . $investment->user->last_name;
        $shortcodes['[[site_title]]'] = setting('site_title', 'global');

        if($data['passed_phase_step'] == 1) {
          // evaluation
          $shortcodes['[[phase_step]]'] = 'Evaluation';
        } else {
          // verification
          $shortcodes['[[phase_step]]'] = 'Verification';
        }
        $this->mailNotify($investment->user->email, 'phase_promotion', $shortcodes);
      }
    }

  }

}