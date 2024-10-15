<?php

namespace App\Services;

use Txn;
use App\Models\User;
use App\Models\Bonus;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Models\ForexSchema;
use Brick\Math\BigDecimal;
use Illuminate\Support\Facades\Auth;

class BonusService{

  protected $forexApiService;

  public function __construct(ForexApiService $forexApiService) {
      $this->forexApiService = $forexApiService;
  }

  /**
   * Getting current credit amount
   * 
   */
  private function getAccountCurrentCreditAmount($account_target_id) {
    $balance = $this->forexApiService->getCurrentCredit([
      'login' => $account_target_id
    ]);

    return $balance;
  }

  /**
   * Responsible for adding or subtracting from bonus/credit
   * 
   */
  private function addOrSubtractBonusToAccount($account_target_type, $account_target_id, $amount, $comment, $type) {

    $forexApiData = [
      'login' => $account_target_id,
      'Amount' => $amount,
      'type' => $type == 'add' ? 1 : 2, // 1: deposit, 2: withdraw
      'TransactionComments' => $comment
    ];
    
    // Adding or Subtarcting Bonus to Balance
    if ($account_target_type == 'forex') {
      $this->forexApiService->bonusOperation($forexApiData);
    }

  }

  /**
   * Use to add manual bonus from one user, admin to other user
   * 
   */
  public function addManualBonus($request, $user) {

    $type = $request->bonus_type;
    $amount = $request->amount;
    $account_target_id = $request->target_id;
    $comment = $request->comment ?? 'N/A';
    $account_target_type = $request->target_type; // hardcoding for now
    $user_id = $user->id;
    $admin_id = Auth::id();
    
    // if adding the bonus manually
    if($type == 'add'){
      // Transaction description
      $description = setting('site_currency', 'global') . $request->amount . " Bonus assigned to " . $user->first_name . ' by ' . Auth::user()->name;

      // Txn Type
      $transaction_type = TxnType::Bonus;
    }

    // if subtracting the bonus manually
    if($type == 'subtract') {

      // get account balance
      $user_credit = $this->getAccountCurrentCreditAmount($account_target_id);

      // if amount to be subtracted is bigger than user's current balance 
      if (BigDecimal::of($amount)->compareTo($user_credit) > 0) {
        return ['status' => 'error', 'message' => "Specify an amount to subtract that is less than the user's current available credit. The user's current credit is " . setting('site_currency', 'global') . $user_credit . '.' ];
      }

      // Transaction description
      $description = setting('site_currency', 'global') . $request->amount . " Bonus removed from " . $user->first_name . ' by ' . Auth::user()->name;
      
      // Txn Type
      $transaction_type = TxnType::BonusSubtract;
    }

    // Adding or Subtracting funds from Forex/? Account
    $this->addOrSubtractBonusToAccount($account_target_type, $account_target_id, $amount, $comment, $type);

    // Making a transaction record
    $transaction = Txn::new($amount, 0, $amount, 'system', $description, $transaction_type, TxnStatus::Success, null, null, $user_id, $admin_id, 'Admin', [], $comment, $account_target_id, $account_target_type);

    // Adding relation of bonus to user
    $bonus_id = 0; // refers to manual bonus added to user's account
    $user->bonuses()->attach($bonus_id, [
      'added_by' => Auth::id(),
      'type' => $type,
      'amount' => $amount,
      'transaction_id' => $transaction->id,
      'account_target_id' => $account_target_id,
      'account_target_type' => $account_target_type
    ]);

    return ['status' => 'success', 'message' => $description];
    
  }

  public function assignBonusToAccountType($bonus_id, $forex_account_types, $type = 'forex'){
      
    if($type == 'forex') {

      // assigning the bonus id to schema types
      for($i = 0; $i < count($forex_account_types); $i++){
        $forexSchema = ForexSchema::findOrFail($forex_account_types[$i]);
        $forexSchema->update(['bonus_id' => $bonus_id]);
      }

      // in case if he deselects the bonus
      // Now check if there are any ForexSchemas that have the old bonus_id
      $schemasToUpdate = ForexSchema::where('bonus_id', $bonus_id)
          ->whereNotIn('id', $forex_account_types) // Get schemas that are not in the provided array
          ->get();

      // Update those schemas to set bonus_id to null
      foreach ($schemasToUpdate as $schema) {
          $schema->update(['bonus_id' => null]);
      }

    }

  }
}
