<?php

namespace App\Services;

use App\Models\Wallet;
use App\Enums\WalletType;
use App\Models\FundedBalance;
use App\Enums\InvestmentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;

class PayoutService
{

  /**
   * Initialize Funded Balance with empty values
   */
  public function fundedBalanceInit($investment_id) {
    $new_funded_balance = new FundedBalance();
    $new_funded_balance->user_id = Auth::id();
    $new_funded_balance->account_type_investment_id = $investment_id;
    $new_funded_balance->user_profit_share = 100 - AccountTypeInvestment::find($investment_id)->getAccountTypeSnapshotData()['profit_share'];
    $new_funded_balance->profit = 0.00;
    $new_funded_balance->last_retrieved_profit = 0.00;
    $new_funded_balance->save();

    return $new_funded_balance;
  }

  /**
   * Update Funded balance Profit Values
   */
  public function updateFundedBalance(AccountTypeInvestment $investment) {
    $total_profit = $investment->accountTypeInvestmentStat->balance - $investment->getRuleSnapshotData()['allotted_funds'];

    $funded_balance = FundedBalance::where('user_id', Auth::id())->where('account_type_investment_id', $investment->id)->first();
    $funded_balance->profit = $funded_balance->profit + ($total_profit - $funded_balance->last_retrieved_profit);

    if( $total_profit <= $funded_balance->last_retrieved_profit ) {
      return true;
    }

    $funded_balance->last_retrieved_profit = $total_profit;
    $funded_balance->save();

    return $funded_balance;

  }

  /**
   * Update all the funded balances specific to a user
   */
  public function updateAllFundedBalance($user_id) {
    $active_funded_accounts = AccountTypeInvestment::where('user_id', $user_id)->where('status', InvestmentStatus::ACTIVE)->whereHas('accountTypePhase', function ($query) {
        $query->where('type', 'funded_phase');
     })->get();

    // check for the funded account that meet the rules
    $eligible_for_payout_accounts = [];
    foreach($active_funded_accounts as $acc) {
        $account_rule = $acc->getRuleSnapshotData();
        $account_stats = $acc->accountTypeInvestmentStat;

        // $trading_days = ;
        if(!isset($acc->getAccountTypeSnapshotData()['trading_days'])) {
          $trading_days = $acc->getRuleSnapshotData()['trading_days'];
        } else {
          $trading_days = $acc->getAccountTypeSnapshotData()['trading_days'];
        }

        if(!$account_stats) {
          continue;
        }

        if( 
            $account_stats->balance >= ( $account_rule['allotted_funds'] + $account_rule['profit_target'] ) && 
            $account_stats->trading_days >= $trading_days &&
            $account_stats->balance == $account_stats->current_equity
        ){
            array_push($eligible_for_payout_accounts, $acc);
        }
    }
    
    // create the funded_balances entry for all the eligible funded accounts
    foreach($eligible_for_payout_accounts as $acc) {

        // Create Funded Balance Entry if no existing
        $funded_balance_exists = FundedBalance::where('user_id', $user_id)->where('account_type_investment_id', $acc->id)->exists();
        if(!$funded_balance_exists) {
            $this->fundedBalanceInit($acc->id);
        }

        // update the profit values of funded balance
        $this->updateFundedBalance($acc);
    }

    
  }

  // Unique id for the wallet
  private function generateUniqueId($table, $column, $length = 8, $letters = '') {
      do {
          // Generate a random numeric ID with the specified length
          $uniqueId = $letters . random_int(pow(10, $length - 1), pow(10, $length) - 1);
  
          // Check if the ID already exists in the specified column of the table
          $exists = DB::table($table)->where($column, $uniqueId)->exists();
      } while ($exists);
  
      return $uniqueId;
  }
  
  /**
   * Create new payout Wallet
   */
  public function createNewWallet($type) {
    $new_wallet = new Wallet();
    $new_wallet->user_id = Auth::id();
    $new_wallet->unique_id = $this->generateUniqueId('wallets', 'unique_id', 6, $type == WalletType::PAYOUT ? 'PAY-' : 'AFF-');
    $new_wallet->slug = $type == WalletType::PAYOUT ? WalletType::PAYOUT : WalletType::AFFILIATE;
    $new_wallet->title = $type == WalletType::PAYOUT ? 'Payout Wallet' : 'Affiliate Wallet';
    $new_wallet->available_balance = 0.00;
    $new_wallet->save();

    return $new_wallet;
  }

}