<?php

namespace App\Services;

use App\Enums\TxnStatus;
use App\Models\AccountType;
use App\Models\Transaction;
use App\Models\AccountActivity;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountTypeInvestment;


class AccountBuyService
{

  public function checkAccountCreationLimit($id)
  {
    $account_type = AccountType::find($id);

    $no_of_accounts = AccountTypeInvestment::where('user_id', Auth::id())
      ->whereHas('accountTypeInvestmentSnapshot', function ($query) use ($account_type) {
        $query->whereJsonContains('account_types_data->id', $account_type->id);
      })->get();

    $failed_transactions = Transaction::where('user_id', Auth::id())->where('status', TxnStatus::Failed)->get();
    $exclude_limit = count(array_intersect($failed_transactions->pluck('target_id')->toArray(), $no_of_accounts->pluck('id')->toArray()));
    
    if ((count($no_of_accounts) - $exclude_limit) >= $account_type->accounts_limit) {
      return false;
    }

    return true;
  }
}
