<?php

namespace App\Services;

use App\Enums\AccountActivityStatusEnums;
use App\Enums\AccountTypePhase as EnumsAccountTypePhase;
use Carbon\Carbon;
use App\Models\AccountType;
use App\Traits\NotifyTrait;
use Illuminate\Support\Str;
use App\Models\FundedBalance;
use App\Enums\InvestmentStatus;
use App\Enums\TradingObjective;
use App\Models\AccountTypePhase;
use App\Services\InvoiceService;
use App\Models\AccountTypePhaseRule;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentSnapshot;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;
use App\Enums\KycNoticeInvokeEnums;
use App\Enums\KycStatusEnums;
use App\Enums\TraderType;
use App\Models\User;

class AccountTypeInvestmentService
{

  use NotifyTrait;

  private $investment_payment;
  protected $forexApiService;
  protected $matchTraderApiService;
  protected $payout;
  protected $invoice;

  public function __construct( AccountTypeInvestmentPaymentService $investment_payment, ForexApiService $forexApiService, PayoutService $payout, InvoiceService $invoice, MatchTraderApiService $matchTraderApiService) {
    $this->forexApiService = $forexApiService;
    $this->matchTraderApiService = $matchTraderApiService;
    $this->investment_payment = $investment_payment;
    $this->payout = $payout;
    $this->invoice = $invoice;
  }

  /**
   * To generate a unique id for investments
   */
  function generateUniqueString($length = 8) {
    do {
        $uniqueString = Str::random($length);
    } while (AccountTypeInvestment::where('unique_id', $uniqueString)->exists()); // Replace `ModelName` and `column_name`
    
    return $uniqueString;
  }

  /**
   * Save Investment Attributes Snapshot
   * createInvestment() helper fn
   */
  private function saveInvestmentAttributesSnapshot($new_investment, $copy_snapshot_id) {
    $snapshot_data = [];

    if($copy_snapshot_id == 0){
      $account_type_id = $new_investment->accountTypePhaseRule->accountTypePhase->accountType->id;
      $account_type = AccountType::with('accountTypePhases.accountTypePhaseRules')->find($account_type_id);

      $snapshot_data = [
        'account_type_investment_id' => $new_investment->id,
        'account_types_data' => $new_investment->accountTypePhaseRule->accountTypePhase->accountType->toArray(),
        'account_types_phases_data' => AccountTypePhase::where('account_type_id', $account_type_id)->get()->toArray(),
        'account_types_phases_rules_data' => $account_type->accountTypePhases->flatMap->accountTypePhaseRules->toArray(),
      ];

    } else {
      $snapshot = AccountTypeInvestmentSnapshot::find($copy_snapshot_id);
      
      $snapshot_data = [
        'account_type_investment_id' => $new_investment->id,
        'account_types_data' => $snapshot->account_types_data,
        'account_types_phases_data' => $snapshot->account_types_phases_data,
        'account_types_phases_rules_data' => $snapshot->account_types_phases_rules_data,
      ];
    }

    return AccountTypeInvestmentSnapshot::create($snapshot_data);
  }

  /**
   * Create an investment
   */
  public function createInvestment($data, $copy_snapshot_id = 0, $is_trial = false, $added_manually = false, $added_manually_to_user = 0) {
    
    // it means that creating the new investment for Phase 1
    if($copy_snapshot_id == 0){
      $currency = setting('site_currency', 'global');
      $rule = AccountTypePhaseRule::findOrFail($data->rule_id);
      $total_amount = $rule->amount;

			// If account is beinbg added manually then skip discounts and addons
      if(!$added_manually) {
				// Discount (Coupon Code) Management
				if($data['discount_id']){
					// if coupon code is applied by the buyer
					$total_amount = $this->invoice->processCouponDiscount($data['discount_id'], $rule->amount)['final_amount'];
				}

				// Addons Management
				if($data['addons']){
					// if one or more addons have ben applied
					$addons = explode(',', $data['addons']);
					for($i=0; $i < count($addons); $i++) {
					$total_amount += $this->invoice->processAddons($addons[$i], $rule->amount);
					}
				}

				// Invoice crucial data
				$invoice_data = [
					'addons' => $data['addons'],
					'discount_id' => $data['discount_id']
				];
      }
			
      // data
      $data = [
          'unique_id' => $this->generateUniqueString(),
          'user_id' => $added_manually ? $added_manually_to_user : Auth::id(),
          'currency' => $currency,
          'account_type_phase_id' => $rule->accountTypePhase->id,
          'account_type_phase_rule_id' => $rule->id,
          'trader_type' => $rule->accountTypePhase->accountType->trader_type,
          'platform_group' => $rule->accountTypePhase->accountType->platform_group,
          'total' => $total_amount - $rule->discount,
          'status' => $added_manually ? InvestmentStatus::ACTIVE : InvestmentStatus::PENDING_NOT_PAID,
          'is_trial' => $is_trial ? 1 : 0
      ];
    }

    // Creating Investment and its snapshot
    $new_investment = AccountTypeInvestment::create($data);

    
    // Create Invoice only for phase 1
    if($copy_snapshot_id == 0 && !$added_manually){
      $invoice = $this->invoice->createInvoice($invoice_data, $rule, $new_investment, $total_amount);
    }
    
    
    $investment_snapshot = $this->saveInvestmentAttributesSnapshot($new_investment, $copy_snapshot_id);
    // Investment phase log
    if($copy_snapshot_id == 0 && !$is_trial && !$added_manually) {
      AccountActivityService::log($new_investment, AccountActivityStatusEnums::PAYMENT_APPROVE);
    }
		
    return $new_investment;
  }

  /**
   * Trading Objectives Evaluation
   */
  private function tradingObjectives($investment, $first_record_after_midnight) {

    $trading_objectives = [];


    // ---- Daily Drawdown Stats ----
    $trading_objectives['daily_drawdown_status'] = TradingObjective::PASSING;
            
    $trading_objectives['daily_drawdown_pnl'] = $investment->accountTypeInvestmentStat->current_equity - $first_record_after_midnight->current_equity;
    

    $trading_objectives['daily_drawdown_remaining_loss_limit'] = ($investment->getRuleSnapshotData()['daily_drawdown_limit'] + $trading_objectives['daily_drawdown_pnl']);
    if($trading_objectives['daily_drawdown_remaining_loss_limit'] < 0){
      $trading_objectives['daily_drawdown_remaining_loss_limit'] = 'Limit Over';
      $trading_objectives['daily_drawdown_status'] = TradingObjective::VIOLATED;
    }

    
    // ---- Max Drawdown stats ----
    $trading_objectives['max_drawdown_status'] = TradingObjective::PASSING;
    $trading_objectives['max_drawdown_pnl'] =  $investment->accountTypeInvestmentStat->current_equity - $investment->getRuleSnapshotData()['allotted_funds'];

    $trading_objectives['max_drawdown_remaining_loss_limit'] = ($investment->getRuleSnapshotData()['max_drawdown_limit'] + $trading_objectives['max_drawdown_pnl']);
    if($trading_objectives['max_drawdown_remaining_loss_limit'] <= 0){
        $trading_objectives['max_drawdown_remaining_loss_limit'] = 'Limit Over';
        $trading_objectives['max_drawdown_status'] = TradingObjective::VIOLATED;
    }else{
        $trading_objectives['max_drawdown_remaining_loss_limit'] = $trading_objectives['max_drawdown_remaining_loss_limit'];
    }

    // ---- Profit target ----
    $trading_objectives['profit_target_status'] = TradingObjective::PASSING;
    $trading_objectives['profit_target'] = $investment->getRuleSnapshotData()['profit_target'];

    // Achievied Profit
    $trading_objectives['current_profit_target'] = $investment->accountTypeInvestmentStat->current_equity - ($investment->getRuleSnapshotData()['allotted_funds']);
    // if($trading_objectives['current_profit_target'] < 0) {
    //     $trading_objectives['current_profit_target'] = 0;
    // }

    if($trading_objectives['current_profit_target'] >= $trading_objectives['profit_target']){
        $trading_objectives['profit_target_status'] = TradingObjective::PASSED;
    }

    // remaining profit target
    $trading_objectives['remaining_profit_target'] = $trading_objectives['profit_target'] - $trading_objectives['current_profit_target'];
    if( ($trading_objectives['profit_target'] - $trading_objectives['current_profit_target']) < 0 ) {
        $trading_objectives['remaining_profit_target'] = 0;
    }

    // ---- Trading Days ----
    $trading_objectives['minimum_trading_days_status'] = TradingObjective::PASSING;
    $trading_objectives['minimum_trading_days'] = $investment->getAccountTypeSnapshotData()['trading_days'] ?? null;

    // if the trading days are set at rules level
    if($trading_objectives['minimum_trading_days'] == null) {
      $trading_objectives['minimum_trading_days'] = $investment->getRuleSnapshotData()['trading_days'];
    }

    $trading_objectives['remaining_trading_days'] = $trading_objectives['minimum_trading_days'] - $investment->accountTypeInvestmentStat->trading_days;

    if($investment->accountTypeInvestmentStat->trading_days >= $trading_objectives['minimum_trading_days']){
        $trading_objectives['minimum_trading_days_status'] = TradingObjective::PASSED;
    }

    return $trading_objectives;
  }

  /**
   * Evaluate the trading objectives to check the status of investment: active, violated, passed
   */
  private function tradingObjectivesEvaluation($trading_objectives) {

    if($trading_objectives['daily_drawdown_status'] == TradingObjective::VIOLATED) {
      return TradingObjective::DD_VIOLATED;
    }

    if($trading_objectives['max_drawdown_status'] == TradingObjective::VIOLATED) {
      return TradingObjective::MD_VIOLATED;
    }

    if($trading_objectives['profit_target_status'] == TradingObjective::PASSED && $trading_objectives['minimum_trading_days_status'] == TradingObjective::PASSED){
      return TradingObjective::PASSED;
    }

    return TradingObjective::PASSING;

  }

  /**
   * Tradings Stats of the investment
   */
  public function tradingStats($investment_id){
    $investment = AccountTypeInvestment::where('id', $investment_id)->firstOrFail();

    // Minute Stats dont exist 
    if($investment->accountTypeInvestmentStat == null) {
      return true;
    }

    // --- KYC Check  --- 
    $kyc_verified = true;
    $user = User::find($investment->user_id);
    // KYC STATUS
    if($user->kyc->status == KycStatusEnums::UNVERIFIED && 
      (kyc_invoke_at() == KycNoticeInvokeEnums::VERIFICATION_PHASE || kyc_invoke_at() == KycNoticeInvokeEnums::FUNDED_PHASE)
    ) {
      $kyc_verified = false;
    }
    
    // ->where('status', 'active')

    // Same day 1st record after 12AM
    $first_record_after_midnight = AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $investment->id)->where('created_at', '>=', Carbon::today())->orderBy('created_at', 'asc')->first();

    
    // If no record found, fallback to the most recent record
    if (!$first_record_after_midnight) {
        $first_record_after_midnight = AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $investment->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    // Calculate the trading objectives
    $trading_objectives = $this->tradingObjectives($investment, $first_record_after_midnight);

    // Evaluate the trading objectives
    $trading_objectives_evaluation = $this->tradingObjectivesEvaluation($trading_objectives);
    if( $trading_objectives_evaluation == TradingObjective::DD_VIOLATED || $trading_objectives_evaluation == TradingObjective::MD_VIOLATED) {
      
      // Stats when the violation is about to happen
      $violation_data = [
        'balance' => $investment->accountTypeInvestmentStat->balance,
        'equity' => $investment->accountTypeInvestmentStat->current_equity,
        'trading_days' => $investment->accountTypeInvestmentStat->trading_days,
        'floating_pnl' => $investment->accountTypeInvestmentStat->current_equity - $investment->accountTypeInvestmentStat->balance,
        'daily_drawdown_pnl' => $trading_objectives['daily_drawdown_pnl'],
        'max_drawdown_pnl' => $trading_objectives['max_drawdown_pnl'],
        'daily_drawdown_remaining_loss_limit' => $trading_objectives['daily_drawdown_remaining_loss_limit'],
        'max_drawdown_remaining_loss_limit' => $trading_objectives['max_drawdown_remaining_loss_limit'],
        'violated_at' => Carbon::now()
      ];

      // Initiate Violation process
      $this->violatePhase($investment, $trading_objectives_evaluation, $violation_data);

    }elseif($trading_objectives_evaluation ==  TradingObjective::PASSING) {
      $investment->status = InvestmentStatus::ACTIVE;
    }elseif( $trading_objectives_evaluation ==  TradingObjective::PASSED ) {
      // Should have no active trade
      if( $investment->accountTypeInvestmentStat->current_equity != $investment->accountTypeInvestmentStat->balance ){
        $investment->status = InvestmentStatus::ACTIVE;
      }else{

        if($investment->is_trial == 1) {
          $investment->status = InvestmentStatus::ACTIVE;
        }

        // if promoted then set the current investment as passed
        if($investment->getPhaseSnapshotData()['type'] != 'funded_phase') {
          $investment->status = InvestmentStatus::PASSED;
        }
  
        // create a new investment with new account for next phase if the account_type type is "challenge"
        $account_type_type = $investment->getAccountTypeSnapshotData()['type'];
        if($account_type_type == "challenge"){
          $is_promoted = $this->promoteToNextPhase($investment);
        }
        

      }
    }

    $investment->save();


    return get_defined_vars();

  }

  /**
   * Promotion to next phase
   */
  public function promoteToNextPhase($passed_investment) {

    $user = User::find($passed_investment->user_id);

    // skip if trial
    if($passed_investment->is_trial == 1) {
      return false;
    }

    $snapshot = AccountTypeInvestmentSnapshot::where('account_type_investment_id', $passed_investment->id)->first();
    $all_phases_of_investment_account_type = $snapshot->account_types_phases_data;
    $passed_phase = $passed_investment->getPhaseSnapshotData();
    $passed_phase_rules = $passed_investment->getRuleSnapshotData();

    // check if there are more phases to current account type (automatically filter funded phase out)
    if((count($all_phases_of_investment_account_type) - $passed_phase['phase_step']) != 0){


      // get the next phase and rule
      $next_phase = collect($snapshot->account_types_phases_data)->firstWhere('phase_step', $passed_phase['phase_step'] + 1);
      $next_phase_rule = collect($snapshot->account_types_phases_rules_data)
                        ->where('account_type_phase_id', $next_phase['id'])
                        ->where('unique_id', $passed_phase_rules['unique_id'])
                        ->first();

      // Check if there is any KYC Check for Verification Phase
      if($user->kyc->status == KycStatusEnums::UNVERIFIED && kyc_invoke_at() == KycNoticeInvokeEnums::VERIFICATION_PHASE && $next_phase['type'] == EnumsAccountTypePhase::VERIFICATION) {
        return false;
      }

      // Check if there is any KYC Check for Funded Phase
      if($user->kyc->status == KycStatusEnums::UNVERIFIED && kyc_invoke_at() == KycNoticeInvokeEnums::FUNDED_PHASE && $next_phase['type'] == EnumsAccountTypePhase::FUNDED) {
        return false;
      }

      // Check if the next phase investment is already created, then return
      $check_user_and_next_phase_exists = AccountTypeInvestment::where([
                                            'account_type_phase_id' => $next_phase['id'], 
                                            'unique_id' =>     $passed_investment->unique_id
                                          ])->exists();
      if($check_user_and_next_phase_exists) {
        return false;
      }

      // Update the phase_ended_at of passed phase
      $passed_investment->phase_ended_at = Carbon::now();
      $passed_investment->mail_sent = 0;

      // --- Create the investment along with the snapshot ---
      $currency = setting('site_currency', 'global');
      $new_investment_data = [
        'unique_id' => $passed_investment->unique_id,
        'user_id' => $passed_investment->user_id,
        'currency' => $currency,
        'account_type_phase_id' => $next_phase['id'],
        'account_type_phase_rule_id' => $next_phase_rule['id'],
        'trader_type' => $passed_investment->getAccountTypeSnapshotData()['trader_type'],
        'platform_group' => $passed_investment->getAccountTypeSnapshotData()['platform_group'],
        'total' => $next_phase_rule['amount'] - $next_phase_rule['discount'],
        'status' => $next_phase['phase_approval_method'] == 'admin_approval' ? InvestmentStatus::PENDING : InvestmentStatus::ACTIVE
      ];
      $new_investment = $this->createInvestment($new_investment_data, $snapshot->id);

      // --- Create the Phase Approval Record ---
      // Phase Approval Passed Investment data
      AccountActivityService::log($passed_investment, AccountActivityStatusEnums::PASSED);

      // Phase Approval Next Phase Investment data
      AccountActivityService::log(
        $new_investment, 
        $next_phase['phase_approval_method'] == 'admin_approval' ? AccountActivityStatusEnums::ADMIN_APPROVE : AccountActivityStatusEnums::AUTO_APPROVE,
        $next_phase['phase_approval_method'] == 'admin_approval' ? 0 : 1
      );

      if($next_phase['phase_approval_method'] == 'auto_approval'){
        // Auto approve the next phase 
        $investment_active_data = [
          'phase_promotion' => true,
          'passed_phase_step' => $passed_phase['phase_step']
        ];
        $this->investment_payment->investmentActive($new_investment->id, $investment_active_data);

        // Investment phase approval table updation
        AccountActivityService::log($new_investment, AccountActivityStatusEnums::ACTIVE);


      }

      return true;

    }else{
      // payout phase
      
      // Create Funded Balance Entry if no existing
      $funded_balance_exists = FundedBalance::where('user_id', Auth::id())->where('account_type_investment_id', $passed_investment->id)->exists();
      if(!$funded_balance_exists) {
        $this->payout->fundedBalanceInit($passed_investment->id);
      }

      // update the profit values of funded balance
      $this->payout->updateFundedBalance($passed_investment);
      
    }
    
  }

  /**
   * Violation Occured
   */
  public function violatePhase($violate_investment, $reason, $violation_data) {

    // skip if trial
    if($violate_investment->is_trial == 1) {
      return true;
    }

    // skip if the account is already violated
    if($violate_investment->status == InvestmentStatus::VIOLATED) {
      return true;
    }
 
    // Violate wrt Platform/TraderType
    if($violate_investment->trader_type == TraderType::MT5) {
      // Empty the balance from account
      $data = [
        'login' => $violate_investment->login,
        'Amount' => $violate_investment->accountTypeInvestmentStat->balance,
        'type' => 0,
        'TransactionComments' => 'Account Violated'
      ];

      $response = $this->forexApiService->balanceOperation($data);
    } elseif($violate_investment->trader_type == TraderType::MT) {
      $deduct_balance_data = [
        "systemUuid" => $violate_investment->getAccountTypeSnapshotData()['system_uuid'],
        "login" => $violate_investment->login,
        "amount" => $violate_investment->accountTypeInvestmentStat->balance,
        "comment" => "Account Violated"
      ];
  
      $deduct_balance_response = $this->matchTraderApiService->deductBalance($deduct_balance_data);
    }
    

    // Update account
    $violate_investment->update(
      [
        'status' => InvestmentStatus::VIOLATED,
        'violation_reason' => $reason,
        'violation_data' => $violation_data,
        'phase_ended_at' => Carbon::now(),
        'mail_sent' => 0
      ]
    );

    // Update account stats
    $violate_investment->accountTypeInvestmentStat->update(['balance' => 0, 'current_equity' => 0]);

    // Add the record in investment phase approvals table
    AccountActivityService::log($violate_investment, AccountActivityStatusEnums::VIOLATED);

  }

}