<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Contract;
use App\Traits\NotifyTrait;
use Illuminate\Support\Str;
use App\Enums\InvestmentStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Enums\StorageMethodEnums;
use App\Enums\ContractStatusEnums;
use Illuminate\Support\Facades\File;
use App\Models\AccountTypeInvestment;
use App\Http\Controllers\StorageController;

class ContractService
{
  use NotifyTrait;

  /**
   * Create Contract
   */
  public function createContract($account){

    $account_type = $account->getAccountTypeSnapshotData();

    $new_contract = new Contract();
    $new_contract->user_id = $account->user_id;
    $new_contract->account_type_investment_id = $account->id;
    $new_contract->user_profit_share = 100 - $account_type['profit_share'];
    $new_contract->expiry_at = Carbon::now()->addDays(setting('contract_expiry'))->toDateTimeString();
    $new_contract->status = ContractStatusEnums::PENDING;
    $new_contract->save();

    return $new_contract;

  }

  /**
   * Send Email when the contract is signed
   */
  private function sendNewAccountEmail($account) {
    
    $shortcodes = [
      '[[site_title]]' => setting('site_title', 'global'),
      '[[full_name]]' => $account->user->first_name . ' ' . $account->user->last_name,
      '[[account_login]]' => $account->login,
      '[[account_password]]' => $account->main_password,
      '[[server]]' => setting('live_server', 'platform_api'),
    ];

    $mail = $this->mailNotify($account->user->email, 'contract_signed', $shortcodes);
    // $mail = $this->mailNotify($account->user->email, 'new_account_details', $shortcodes);

    if($mail) {
      $account->mail_sent = 1;
      $account->save();
    }

  }

  /**
   * Mark Contract Signed
   */
  public function signed($contract, $pdf_path) {
      $contract->status = ContractStatusEnums::SIGNED;
      $contract->file_path = $pdf_path;
      $contract->signed_at = Carbon::now()->toDateTimeString();
      $contract->expired_at = null;
      $contract->save();


      $account = AccountTypeInvestment::find($contract->account_type_investment_id);
      $account->status = InvestmentStatus::ACTIVE;
      $account->save();
      $this->sendNewAccountEmail($account);

      return $contract;
  }

  /**
   * Mark Contract Expired
   */
  public function expired($contract){
    $contract->status = ContractStatusEnums::EXPIRED;
    $contract->expired_at = Carbon::now()->toDateTimeString();
    $contract->signed_at = null;
    $contract->save();

    $account = AccountTypeInvestment::find($contract->account_type_investment_id);
    $account->status = InvestmentStatus::EXPIRED;
    $account->save();

    // Activity log
    AccountActivityService::log($account, InvestmentStatus::EXPIRED);


    $shortcodes = [
      '[[site_title]]' => setting('site_title', 'global'),
      '[[full_name]]' => $account->user->first_name . ' ' . $account->user->last_name,
      '[[account_login]]' => $account->login,
    ];

    $mail = $this->mailNotify($account->user->email, 'contract_expired', $shortcodes);

    return $contract;
  }

  /**
   * Mark Contract Pending
   */
  public function pending($contract){
    $contract->status = ContractStatusEnums::PENDING;
    $contract->expired_at = null;
    $contract->signed_at = null;
    $contract->expiry_at = Carbon::now()->addDays(setting('contract_expiry'))->toDateTimeString();
    $contract->save();

    $account = AccountTypeInvestment::find($contract->account_type_investment_id);
    $account->status = InvestmentStatus::ACTIVE;
    $account->save();

    return $contract;
  }

  /**
   * Check for contract expiry and expire accounts accordingly
   */
  public function checkExpired() {
      $contracts = Contract::where('status', ContractStatusEnums::PENDING)->get();

      foreach ($contracts as $contract) {
        $is_expired = $contract->expiry_at < Carbon::now();

        if($is_expired) {
          $this->expired($contract);
        }
      }

  }

  /**
   * Generate Contract Helper function
   */
  private function assetsPath($path) {
    $public_path = public_path($path);
    $path = str_replace('public/', 'assets/', $public_path);
    $path = str_replace('public\\', 'assets/', $path);

    return $path;
  }

  /**
   * Generate Contract
   */
  public function generateContract($contract_id, $signature) {

    $contract = Contract::find($contract_id);

    $contractData = [
        'signature' => $signature,
        'shouldHideElement' => false,
        'contract' => $contract
    ];

    $pdf = Pdf::loadView('frontend::contracts.include.__contract_template', $contractData);
    
    $fileName = Str::random(40) . '.pdf';

    
    if(getStorageMethod() == StorageMethodEnums::AWS_S3) {
      $path = 'user/contracts/' . $contract->user->id . '/' . $fileName;
      $url = StorageController::AWSUpload($pdf->output(), $path);
      $url = substr($url, 0, -1) . $path;

      return ["file_path" => $url];
    }

    if(getStorageMethod() == StorageMethodEnums::FILESYSTEM) {
      $path = 'global/storage/user/contracts/' . $contract->user->id;
      $directory = $this->assetsPath($path);

      if (!File::exists($directory)) {
          File::makeDirectory($directory, 0775, true);
      }

      try {
          $pdf_path = $pdf->save($directory . '/' . $fileName);
      } catch (\Exception $e) {
          notify()->error('There was an error generating the contract: ' . $e->getMessage(), 'Error');
          return redirect()->back();
      }
      return ["file_path" => $path . '/' . $fileName];
    }
    

  }

}