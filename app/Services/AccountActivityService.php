<?php

namespace App\Services;

use App\Models\AccountActivity;


class AccountActivityService
{
  // public function createRecord($data) {
  //   return AccountActivity::create($data);
  // }

  public static function log($account, $status, $action = 1){
      $account_activity = AccountActivity::create([
        'account_type_investment_id' => $account->id,
        'account_type_phase_id' => $account->getPhaseSnapshotData()['id'],
        'phase_type' => $account->getPhaseSnapshotData()['type'],
        'status' => $status,
        'action' => $action
      ]);

      return $account_activity;
  }
   
}
