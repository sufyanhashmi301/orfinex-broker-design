<?php

namespace App\Services;

use App\Enums\ContractStatusEnums;
use App\Enums\InvestmentStatus;
use App\Models\AccountTypeInvestment;
use Carbon\Carbon;
use App\Models\Contract;
use App\Traits\NotifyTrait;

class ContractService
{
  use NotifyTrait;

  public function createContract($account){

    $account_type = $account->getAccountTypeSnapshotData();

    $new_contract = new Contract();
    $new_contract->user_id = $account->user_id;
    $new_contract->account_type_investment_id = $account->id;
    $new_contract->user_profit_share = 100 - $account_type['profit_share'];
    $new_contract->expiry_at = Carbon::now()->addDays(10)->toDateTimeString();
    $new_contract->status = ContractStatusEnums::PENDING;
    $new_contract->save();

    return $new_contract;

  }

  /**
   * Send Email when the contract is signed
   */
  private function sendNewAccountEmail($account) {
    
    $shortcodes = [
      '[[full_name]]' => $account->user->first_name . ' ' . $account->user->last_name,
      '[[account_login]]' => $account->login,
      '[[account_password]]' => $account->main_password,
      '[[server]]' => setting('live_server', 'platform_api'),
    ];
    
    $mail = $this->mailNotify($account->user->email, 'new_account_details', $shortcodes);

    if($mail) {
      $account->mail_sent = 1;
      $account->save();
    }

  }

  public function signed($contract, $pdf_path) {
      $contract->status = ContractStatusEnums::SIGNED;
      $contract->file_path = $pdf_path;
      $contract->signed_at = Carbon::now()->toDateTimeString();
      $contract->save();


      $account = AccountTypeInvestment::find($contract->account_type_investment_id);
      $this->sendNewAccountEmail($account);

      return $contract;
  }

  public function checkExpired() {
      $contracts = Contract::where('status', ContractStatusEnums::PENDING)->get();

      foreach ($contracts as $contract) {
        $is_expired = $contract->expiry_at < Carbon::now();

        if($is_expired) {
          $contract->status = ContractStatusEnums::EXPIRED;
          $contract->expired_at = Carbon::now()->toDateTimeString();
          $contract->save();

          $account = AccountTypeInvestment::find($contract->account_type_investment_id);
          $account->status = InvestmentStatus::EXPIRED;
          $account->save();

        }
      }

  }

}