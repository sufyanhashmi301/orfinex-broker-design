<?php

namespace App\Jobs;

use App\Console\Commands\MultiLevelRebateDistribution;
use App\Models\ReferralRelationship;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessReferralRebate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $referralId;

    public function __construct(int $referralId)
    {
        $this->referralId = $referralId;
    }

    public function handle(): void
    {
        // try {
            $referral = ReferralRelationship::with('referralLink', 'user')->findOrFail($this->referralId);

            DB::transaction(function () use ($referral) {
                $command = app(MultiLevelRebateDistribution::class);
                $command->processReferralRelationship($referral);
            }, 3);

        // } catch (Throwable $e) {
        //     Log::error("Failed rebate processing for referral ID {$this->referralId}: {$e->getMessage()}");
        // }
    }
}
