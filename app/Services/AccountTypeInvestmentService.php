<?php

namespace App\Services;

use App\Enums\InvestmentStatus;
use App\Models\AccountTypePhaseRule;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;
use App\Models\AccountTypeInvestmentSnapshot;

class AccountTypeInvestmentService
{

  /**
   * Save Investment Attributes Snapshot
   * createInvestment() helper fn
   */
  private function saveInvestmentAttributesSnapshot($new_investment) {
    $snapshot_data = [
      'account_type_investment_id' => $new_investment->id,
      'account_types_data' => $new_investment->accountTypePhaseRule->accountTypePhase->accountType->toArray(),
      'account_types_phases_data' => $new_investment->accountTypePhaseRule->accountTypePhase->toArray(),
      'account_types_phases_rules_data' => $new_investment->accountTypePhaseRule->toArray(),
    ];

    return AccountTypeInvestmentSnapshot::create($snapshot_data);
  }

  /**
   * Create an investment
   */
  public function createInvestment($data) {

    $currency = setting('site_currency', 'global');
    $rule = AccountTypePhaseRule::findOrFail($data->rule_id);

    // data
    $data = [
        'user_id' => Auth::id(),
        'currency' => $currency,
        'account_type_phase_rule_id' => $rule->id,
        'trader_type' => $rule->accountTypePhase->accountType->trader_type,
        'platform_group' => $rule->accountTypePhase->accountType->platform_group,
        'total' => $rule->amount - $rule->discount,
        'status' => InvestmentStatus::PENDING
    ];

    // Creating Investment and its snapshot
    $new_investment = AccountTypeInvestment::create($data);
    $investment_snapshot = $this->saveInvestmentAttributesSnapshot($new_investment);
     

    return $new_investment;
  }

}