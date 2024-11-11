<?php

namespace App\Services;

use App\Models\AccountTypeInvestmentSnapshot;
use App\Models\AccountTypeInvestmentPhaseApproval;


class InvestmentPhaseApprovalService
{
  public function createRecord($data) {
    return AccountTypeInvestmentPhaseApproval::create($data);
  }

   
}
