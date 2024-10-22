<?php

namespace App\Services;

use Txn;
use App\Models\User;
use App\Models\Bonus;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use Brick\Math\BigDecimal;
use App\Models\ForexSchema;
use App\Models\ForexAccount;
use App\Models\BonusTransaction;
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
  public function addOrSubtractBonusToAccount($account_target_type, $account_target_id, $amount, $comment, $type) {

    $forexApiData = [
      'login' => $account_target_id,
      'Amount' => $amount,
      'type' => $type == 'add' ? 1 : 2, // 1: deposit, 2: withdraw
      'TransactionComments' => $comment
    ];

    
    // Adding or Subtarcting Bonus to Balance
    if ($account_target_type == 'forex') {

      // Get account target id and check if the amount to be deducted is larger than current available credit then set the credit to 0, to avoid credit in -
      $user_credit = $this->getAccountCurrentCreditAmount($account_target_id);
      $amount_decimal = \Brick\Math\BigDecimal::of($amount);
      if($type != 'add' && $user_credit->isLessThan($amount_decimal)){
        $forexApiData['Amount'] = $user_credit;
      }

      $this->forexApiService->bonusOperation($forexApiData);

    }

  }

  /**
   * Use to add manual bonus from one user, admin to other user
   * 
   */
  public function addBonus($request, $user) {

    $type = $request->bonus_type;
    $amount = $request->amount;
    $account_target_id = $request->target_id;
    $comment = $request->comment ?? 'N/A';
    $account_target_type = $request->target_type; 
    $user_id = $user->id;
    $admin_id = Auth::id();

    $forex_account = ForexAccount::where('login', $account_target_id)->first();
    
    // if adding the bonus manually by Admin
    if($type == 'add'){
      // Transaction description
      
      // Txn 
      $transaction_description = "Bonus rewarded to " . $forex_account->account_name . ' forex account';;
      $transaction_type = TxnType::Bonus;
      $transaction_status = TxnStatus::Success;
    }

    // if subtracting the bonus manually by Admin
    if($type == 'subtract') {

      // get account balance
      $user_credit = $this->getAccountCurrentCreditAmount($account_target_id);

      // if amount to be subtracted is bigger than user's current balance 
      if (BigDecimal::of($amount)->compareTo($user_credit) > 0) {
        return ['status' => 'error', 'message' => "Specify an amount to subtract that is less than the user's current available credit. The user's current credit is " . setting('site_currency', 'global') . $user_credit . '.' ];
      }

      // Transaction description
      $transaction_description = "Bonus deducted from " . $forex_account->account_name . ' forex account';;
      
      // Txn 
      $transaction_type = TxnType::BonusSubtract;
      $transaction_status = TxnStatus::Success;
    }

    // Adding or Subtracting funds from Forex/? Account
    $this->addOrSubtractBonusToAccount($account_target_type, $account_target_id, $amount, $comment, $type);

    // Making a transaction record
    if($type == 'add' || $type == 'subtract'){
      $new_transaction = Txn::new($amount, 0, $amount, 'admin', $transaction_description, $transaction_type, $transaction_status, null, null, $user_id, $admin_id, 'Admin', [], $comment, $account_target_id, $account_target_type);
    }

    // Add record to Bonus Transactions
    $bonus_txn = new BonusTransaction();
    $bonus_txn->account_id = $forex_account->id;
    $bonus_txn->transaction_id = $new_transaction->id;
    $bonus_txn->bonus_id = 0;
    $bonus_txn->account_target_id = $account_target_id;
    $bonus_txn->account_type = 'forex';
    $bonus_txn->given_by = 'Admin';
    $bonus_txn->bonus_amount = $type == 'add' ? $amount : (-$amount) ;
    $bonus_txn->bonus_amount_left = $type == 'add' ? $amount : (-$amount);
    $bonus_txn->bonus_removal_type = 'N/A';
    $bonus_txn->bonus_removal_amount = 'N/A';
    $bonus_txn->save();

    return ['status' => 'success', 'message' => $transaction_description];
    
  }

  public function assignBonusToAccountType($bonus, $forex_account_types, $type = 'forex'){

    if($type == 'forex') {

      // --- Bonus Basic fns ---
      // assigning the bonus id to schema types
      $bonus->forex_schemas()->sync($forex_account_types);
      // --- Bonus Basic fns ---

      // --- Attaching to accounts ---
      // Get all the forex_accounts (account_type:real, status:ongoing) of the selected account types
      $forexAccounts = ForexAccount::whereIn('forex_schema_id', $forex_account_types)->where('account_type', 'real')->where('status', 'ongoing')->with('user')->get();

      foreach($forexAccounts as $account) {
        $account->user->bonuses()->attach($bonus->id, [
          'added_by' => Auth::id(),
          'type' => $type,
          'amount' => 0,
          'transaction_id' => 0,
          'account_target_id' => $account->login,
          'account_target_type' => $type
        ]);
      }
      // --- Attaching to accounts ---


    }

  }









}
