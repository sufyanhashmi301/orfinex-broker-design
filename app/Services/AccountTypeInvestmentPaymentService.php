<?php

namespace App\Services;

use App\Models\Contract;
use App\Enums\TraderType;
use App\Traits\NotifyTrait;
use Carbon\CarbonImmutable;
use App\Enums\InvestmentStatus;
use App\Models\AccountTypeInvestment;
use App\Enums\AccountTypePhase as AccountTypePhaseEnum;

class AccountTypeInvestmentPaymentService
{

  use NotifyTrait;
  
  protected $accountTypeData;
  protected $phaseData;
  protected $ruleData;
  public $affiliate;
  protected $contract;
  protected $mt5TradingAccount;
  protected $matchTraderTradingAccount;

  public function __construct(UserAffiliateService $userAffiliate, ContractService $contract, Mt5TradingAccountService $mt5TradingAccount, MatchTraderTradingAccountService $matchTraderTradingAccount)
  {
    $this->affiliate = $userAffiliate;
    $this->contract = $contract;
    $this->mt5TradingAccount = $mt5TradingAccount;
    $this->matchTraderTradingAccount = $matchTraderTradingAccount;
  }

  /**
   * Main function
   */
  public function investmentActive($account_type_investment_id, $data = []){

    $investment = AccountTypeInvestment::findOrFail($account_type_investment_id);

    $is_trial = false;
    if($investment->is_trial == 1) {
      $is_trial = true;
    }

    // set the snapshot data of the investment
    $snapshot = $investment->accountTypeInvestmentSnapshot;
    $this->accountTypeData = $investment->getAccountTypeSnapshotData();
    $this->phaseData = $investment->getPhaseSnapshotData();
    $this->ruleData = $investment->getRuleSnapshotData();
    
    // Create trading account wrt TraderType
    if($investment->trader_type == TraderType::MT5) {
      $trading_account_login_id = $this->mt5TradingAccount->createTradingAccount($investment);
    } elseif($investment->trader_type == TraderType::MT) {
      $trading_account_login_id = $this->matchTraderTradingAccount->createUser($investment);
    }

    // Throw Error
    if(!$trading_account_login_id) {
      return false;
    }

    // Deposit balance to trading account
    if($investment->trader_type == TraderType::MT5) {
        $balance_deposit = $this->mt5TradingAccount->depositBalance($trading_account_login_id, $investment);
    } elseif($investment->trader_type == TraderType::MT) {
        $balance_deposit = $this->matchTraderTradingAccount->depositBalance($trading_account_login_id);
    }

    // Throw Error
    if(!$balance_deposit) {
      return false;
    }
    
    // If deposit is successful, update the Investment table and add the record to investment_phase_approvals_table
    $time_now = CarbonImmutable::now();

    $investment->account_name = $this->accountTypeData['title'] . '_' . $investment->id;
    $investment->login = $trading_account_login_id;
    $investment->status = InvestmentStatus::ACTIVE;
    $investment->phase_started_at = $time_now; 

    // if it is phase promotion then add the mail sent to queue
    $is_phase_promotion = false;
    if($data['phase_promotion'] ?? false) {
      $investment->mail_sent = 0;
      $is_phase_promotion = true;
    }

    $investment->save();

    // apply commissions
    if(!$is_trial) {
      $this->affiliate->applyCommission($investment);
    }

    // send mail if user promoted to next phase -> Moved to commands
    // $this->doEmail('phase_promotion_email', $investment, $data);

    // Funded Phase - Contract Creation
    if($this->phaseData['type'] == AccountTypePhaseEnum::FUNDED && !$is_trial) {
      if(!Contract::where('account_type_investment_id', $investment->id)->exists()){
        $this->contract->createContract($investment);
        $email_data = [
          'passed_phase_step' => $this->phaseData['phase_step'] == 2 ? AccountTypePhaseEnum::EVALUATION :  AccountTypePhaseEnum::VERIFICATION
        ];
        $this->doEmail('pending_contract_email', $investment, $email_data);
      }
    }

    // send email to user only if approved by admin and not phase promotion, 
    if(!$is_phase_promotion && $this->phaseData['type'] != AccountTypePhaseEnum::FUNDED && !$is_trial) {
      $this->doEmail('new_account_email', $investment);
    }

    // send email to user if it is a trial account
    if($is_trial) {
      $this->doEmail('trial_activation_email', $investment);
    }

    // Fetch and store latest stats and hourly stats (Moved to command)
    return $investment;

  }

  /**
   * Do necessary Emails related to account buy
   */
  private function doEmail($slug, $investment, $data = []) {
    
    $shortcodes = [
      '[[full_name]]' => $investment->user->first_name . ' ' . $investment->user->last_name,
      '[[account_login]]' => $investment->login,
      '[[account_password]]' => $investment->main_password,
      '[[server]]' => setting('live_server', 'platform_api'),
      '[[site_title]]' => setting('site_title', 'global')
    ];

    // New account Email
    if($slug == "new_account_email") {
      $this->mailNotify($investment->user->email, 'new_account_details', $shortcodes);
    }

    // Pending Contract Email
    if($slug == 'pending_contract_email') {
      $shortcodes2 = [
        '[[full_name]]' => $investment->user->first_name . ' ' . $investment->user->last_name,
        '[[account_login]]' => $investment->login,
        '[[account_password]]' => $investment->main_password,
        '[[server]]' => setting('live_server', 'platform_api'),
        '[[phase_step]]' => $data['passed_phase_step'] == AccountTypePhaseEnum::EVALUATION ? 'Evaluation' : 'Verification',
        '[[site_title]]' => setting('site_title', 'global'),
      ];
      $mail = $this->mailNotify($investment->user->email, 'contract_pending', $shortcodes2);
    }

    if($slug == 'trial_activation_email') {
      $shortcodes['[[package_name]]'] = $investment->getAccountTypeSnapshotData()['title'];
      $shortcodes['[[expiry_days]]'] = setting('auto_expire_expiry_days');
      $shortcodes['[[expiry_date]]'] = date('jS F, Y', strtotime($investment->accountTrial->trial_expiry_at));
      $this->mailNotify($investment->user->email, 'trial_activation', $shortcodes);
    }

    //  Promotion Email
    // if($slug == "phase_promotion_email") {
    //   if($data['phase_promotion'] ?? false) {
    //     $shortcodes['[[full_name]]'] = $investment->user->first_name . ' ' . $investment->user->last_name;
    //     $shortcodes['[[site_title]]'] = setting('site_title', 'global');

    //     if($data['passed_phase_step'] == 1) {
    //       // evaluation
    //       $shortcodes['[[phase_step]]'] = 'Evaluation';
    //     } else {
    //       // verification
    //       $shortcodes['[[phase_step]]'] = 'Verification';
    //     }
    //     $this->mailNotify($investment->user->email, 'phase_promotion', $shortcodes);
    //   }
    // }

  }

}