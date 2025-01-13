<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\AffiliateRule;
use App\Models\UserAffiliate;
use Illuminate\Support\Facades\DB;
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
      $chain[$loop] = ["user_id" => $user->id, "level" => $loop ];
  
      // Recursively call for the referrer
      if ($user->referrer) {
          $this->getReferralChain($user->referrer, $chain, $loop + 1);
      }
  
      return $chain;
  }

  // Apply commission to direct referrer + levels
  public function applyCommission($investment) {  

    

    // check if the affiliate rule applys to account type
    $buyer_user_id = $investment->user_id;
    $rule = $investment->getRuleSnapshotData();
    $account_type_id = (string) $investment->getAccountTypeSnapshotData()['id'];

    // getting the latest rule affiliate assigned to accountType if not set to all
    if( !AffiliateRule::whereJsonContains('for_account_type_ids', 'all')->orderBy('id', 'DESC')->exists() ){
      $affiliate_rule = AffiliateRule::whereJsonContains('for_account_type_ids', $account_type_id)->orderBy('id', 'DESC')->first();
    }else{
      $affiliate_rule = AffiliateRule::whereJsonContains('for_account_type_ids', 'all')->orderBy('id', 'DESC')->first();
    }
    $affiliate_rule_configuration = $affiliate_rule->affiliateRuleConfiguration;
    $affiliate_rule_levels = $affiliate_rule->affiliateRuleLevel;

    // is Active?
    if($affiliate_rule->is_active == 0){
      return false;
    }
    
    // get the direct refferer
    $direct_refferer = User::find($buyer_user_id)->referrer;
    
    // if there is no referrer
    if($direct_refferer == null) {
      return false;
    }

    // or if the referrer does not have affiliate account (not possible but good to have checks)
    if($direct_refferer->userAffiliate == null){
      return false;
    }

    $refer_count = $direct_refferer->userAffiliate->refer_count + 1;

    $direct_refferer_commission_percentage = $affiliate_rule_configuration->where('count_start', '<=', $refer_count)->where('count_end', '>=', $refer_count)->first()->commission_percentage;

    // calculate the total amount and commission 
    $total_amount = $rule['amount'] - $rule['discount'];
    $total_commission_amount = $this->calculatePartAmount($total_amount, $direct_refferer_commission_percentage);
    $direct_referral_commission_amount = $total_commission_amount;


    // --- pay the levels (if any) ---
    $referral_chain = $this->getReferralChain( User::find($buyer_user_id) );

    
    
    // attach commission percentages and amounts to the whole referral chain
    for( $i=0; $i < count( $referral_chain ); $i++ ) {

      if( $referral_chain[$i]['level'] == 0 ){
        continue;
      }

      $referral_chain[$i]['commission_percentage'] = $affiliate_rule_levels->where('level', $referral_chain[$i]['level'])->first()->commission_percentage ?? 0;

      $referral_chain[$i]['commission_amount'] = $this->calculatePartAmount($total_commission_amount, $referral_chain[$i]['commission_percentage']);

      $total_commission_amount = $referral_chain[$i]['commission_amount'];
    }

    // dd($referral_chain);
    
    // update the balances of users' affiliates
    for($i=0; $i < count( $referral_chain ); $i++ ) {

      // skip the user from the chain who is purchasing the account
      if( $referral_chain[$i]['level'] == 0 ){
        continue;
      }

      // If the commission amount started getting 0 it means max levels have been reached, so break.
      if( $referral_chain[$i]['commission_amount'] == 0 ){
        break;
      }

      // if the user already got the commission by $buyer_user_id and the count mode is set to customers then no one gets the commission, break.
      $check_users_ids_used_exists = UserAffiliate::whereJsonContains('user_ids_used', $buyer_user_id)->orderBy('id', 'DESC')->exists();
      if( $affiliate_rule->count_mode == 'customer' && $check_users_ids_used_exists ) {
        break;
      }


      
      $user_affiliate_info = User::find( $referral_chain[$i]['user_id'] )->userAffiliate;
      
      if( $referral_chain[$i]['level'] == 1 ) {
        $user_affiliate_info->refer_count = $refer_count;
        $user_affiliate_info->total_purchase_amount = $user_affiliate_info->total_purchase_amount + $total_amount;
      }

      // to handle null error
      if($user_affiliate_info == null) {
        continue;
      }

      // Update user affiliate info
      $user_affiliate_info->total_commission = $user_affiliate_info->total_commission + $referral_chain[$i]['commission_amount'];

      // If Balance Retention period is 0
      if($affiliate_rule->balance_retention_period == 0) {

        if($user_affiliate_info->highest_commission_earned < $referral_chain[$i]['commission_amount']) {
          $user_affiliate_info->highest_commission_earned = $referral_chain[$i]['commission_amount'];
        }

        $user_affiliate_info->withdrawable_balance = User::find( $referral_chain[$i]['user_id'] )->userAffiliate->withdrawable_balance + $referral_chain[$i]['commission_amount'];
      } else{
        // Retrieve the current data or initialize an empty array
        $currentData = $user_affiliate_info->commission_pending ?? [];

        // Define the new entry
        $newEntry = [
            'commission' => $referral_chain[$i]['commission_amount'], 
            'receiving_date' => Carbon::now()->addDays( $affiliate_rule->balance_retention_period )->format('d/m/y'), // Current date + balance_retention_period days
            'status' => 'pending',
            'balance_retention_period' => $affiliate_rule->balance_retention_period,
            'created_at' => Carbon::now()->format('d/m/y'),
        ];

        // Append the new entry
        $currentData[] = $newEntry;

        // Update the column in the database
        $user_affiliate_info->commission_pending = $currentData;
      }

      $user_affiliate_info->save();

      // Add the $buyer_user_id in user_ids_used if not already existed in the array, and only add to the direct referrer i.e. level=1
      if(!$check_users_ids_used_exists && $referral_chain[$i]['level'] == 1) {
        DB::table('user_affiliates')->where('id', $user_affiliate_info->id)->update([
          'user_ids_used' => DB::raw("JSON_ARRAY_APPEND(user_ids_used, '$', " . intval($buyer_user_id) . ")")
        ]);
      }

    }


    
  }

  // Initialize User Affiliate Commission

  // update pending commissions
  private function processPendingCommissions($user_id) {
    // Fetch the UserAffiliate record
    $userAffiliate = UserAffiliate::where('user_id', $user_id)->first();

    if (!$userAffiliate) {
        return "User Affiliate Info not found.";
    }

    // Retrieve the `commission_pending` data or initialize an empty array
    $commissionData = $userAffiliate->commission_pending ?? [];

    // Get today's date
    $today = Carbon::now();

    // Loop through and filter the data based on conditions
    $pendingCommissions = collect($commissionData)->filter(function ($item) use ($today) {
        return isset($item['receiving_date'], $item['status']) &&
               $item['status'] === 'pending' &&
               Carbon::createFromFormat('d/m/y', $item['receiving_date'])->lessThanOrEqualTo($today);
    });

    // Reindex the filtered commissions starting from 0
    return $pendingCommissions->values()->all();
  }

  public function pendingCommissionClearance($user_id) {
      $user_affiliate_info = UserAffiliate::where('user_id', $user_id)->first();
      $commissions_to_be_added = $this->processPendingCommissions($user_id);
      

      if( count($commissions_to_be_added) > 0 ) {
        for($i=0; $i < count($commissions_to_be_added); $i++){

          // highest commission record update
          if($user_affiliate_info->highest_commission_earned < $commissions_to_be_added[$i]['commission']) {
            $user_affiliate_info->highest_commission_earned = $commissions_to_be_added[$i]['commission'];
          }
  
          // Add to withdrawal
          $user_affiliate_info->withdrawable_balance = $user_affiliate_info->withdrawable_balance + $commissions_to_be_added[$i]['commission'];
          
        }
  
        // Update the commission_pending column
        $currentData = $user_affiliate_info->commission_pending ?? [];
  
        // Loop through and process each commission entry
        foreach ($currentData as &$commission) {
            // If commission is in the filtered list (i.e., should be updated)
            if (in_array($commission, $commissions_to_be_added)) {
                // Update the status to 'paid'
                $commission['status'] = 'paid';
            }
        }
  
        // Save the updated commission_pending data
        $user_affiliate_info->commission_pending = $currentData;
  
        $user_affiliate_info->save();
      }
      

  }

  
  

}