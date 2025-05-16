<?php
namespace App\Services;

use App\Models\ReferralRelationship;

class RebateDistributionService
{
    public function processReferral(ReferralRelationship $referral): void
    {
        // Move the entire logic from `processReferralRelationship` here
        app(\App\Console\Commands\MultiLevelRebateDistribution::class)->processReferralRelationship($referral);
    }
}
