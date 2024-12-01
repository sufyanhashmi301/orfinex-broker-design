<?php

namespace App\Services;

use App\Models\User;
use App\Models\AffiliateRule;
use App\Models\AccountTypePhaseRule;
use Illuminate\Support\Facades\Auth;


class UserAffiliateService
{

  public function calculatePartAmount(float $total, float $percentage): float {
    if ($percentage == 0) {
        return 0;  // If percentage is 0, the part amount is 0
    }

    $part = ($total * $percentage) / 100;
    return number_format($part, 2, '.', ''); 
  }

  public function getReferralChain($user, &$chain = [], $loop = 0)
  {
      if (!$user) {
          return $chain; // Stop when there's no referrer
      }
  
      // Add the current user to the chain
      $chain[$loop] = ["user_id" => $user->id, "level" => $loop];
  
      // Recursively call for the referrer
      if ($user->referrer) {
          $this->getReferralChain($user->referrer, $chain, $loop + 1);
      }
  
      return $chain;
  }

  // Apply commission to direct referrer + levels
  public function applyCommission($account_type_rule_id) {  

    // check if the affiliate rule applys to account type
    $rule = AccountTypePhaseRule::findOrFail($account_type_rule_id);
    $account_type_id = (string) $rule->accountTypePhase->accountType->id;

    // getting the latest rule affiliate assigned to accountType
    $affiliate_rule = AffiliateRule::whereJsonContains('for_account_type_ids', $account_type_id)->orderBy('id', 'DESC')->first();
    $affiliate_rule_configuration = $affiliate_rule->affiliateRuleConfiguration;
    $affiliate_rule_levels = $affiliate_rule->affiliateRuleLevel;
    
    // get the direct refferer
    $direct_refferer = User::find(Auth::id())->referrer;
    $refer_count = $direct_refferer->userAffiliate->refer_count + 1;

    $direct_refferer_commission_percentage = $affiliate_rule_configuration->where('count_start', '<=', $refer_count)->where('count_end', '>=', $refer_count)->first()->commission_percentage;

    // calculate the total amount and commission 
    $total_amount = $rule->amount - $rule->discount;
    $total_commission_amount = $this->calculatePartAmount($total_amount, $direct_refferer_commission_percentage);
    $direct_referral_commission_amount = $total_commission_amount;

    // --- pay the levels (if any) ---
    $referral_chain = $this->getReferralChain( User::find(Auth::id()) );
    
    // attach commission percentages and amounts to the whole referral chain
    for( $i=0; $i < count( $referral_chain ); $i++ ) {

      if( $referral_chain[$i]['level'] == 0 ){
        continue;
      }

      $referral_chain[$i]['commission_percentage'] = $affiliate_rule_levels->where('level', $referral_chain[$i]['level'])->first()->commission_percentage ?? 0;

      $referral_chain[$i]['commission_amount'] = $this->calculatePartAmount($total_commission_amount, $referral_chain[$i]['commission_percentage']);

      $total_commission_amount = $referral_chain[$i]['commission_amount'];
    }
    
    // update the balances of users' affiliates
    for($i=0; $i < count( $referral_chain ); $i++ ) {

      if( $referral_chain[$i]['level'] == 0 ){
        continue;
      }

      if( $referral_chain[$i]['commission_amount'] == 0 ){
        break;
      }

      $user_affiliate_info = User::find( $referral_chain[$i]['user_id'] )->userAffiliate;

      if( $referral_chain[$i]['level'] == 1 ) {
        $user_affiliate_info->refer_count = $refer_count;
        $user_affiliate_info->total_purchase_amount = $user_affiliate_info->total_purchase_amount + $total_amount;
      }

      // Update user affiliate info
      
      $user_affiliate_info->total_commission = $user_affiliate_info->total_commission + $referral_chain[$i]['commission_amount'];

      if($user_affiliate_info->highest_commission_earned < $referral_chain[$i]['commission_amount']) {
        $user_affiliate_info->highest_commission_earned = $referral_chain[$i]['commission_amount'];
      }

      // $user_affiliate_info->current_balance = User::find( $referral_chain[$i]['user_id'] )->userAffiliate->current_balance + $referral_chain[$i]['commission_amount'];
      $user_affiliate_info->withdrawable_balance = User::find( $referral_chain[$i]['user_id'] )->userAffiliate->withdrawable_balance + $referral_chain[$i]['commission_amount'];
      $user_affiliate_info->save();
    }


    
  }

}