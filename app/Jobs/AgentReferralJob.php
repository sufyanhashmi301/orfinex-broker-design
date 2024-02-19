<?php

namespace App\Jobs;

use App\Events\UserReferred;
use App\Models\ForexAccount;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\User;
use App\Traits\ForexApiTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AgentReferralJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,ForexApiTrait;
    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get all real ForexAccounts for the user
        $forexAccounts = ForexAccount::where('user_id', $this->user->id)
            ->where('account_type', 'real')
//            ->where('agent', 0)
            ->get();

//        dd($forexAccounts,$this->user);

        foreach($forexAccounts as $forexAccount) {
            // Send request to Forex API to get user info
            $userInfo = $this->getUserInfoApi($forexAccount->login);

            // Check if the agent is not 0 in the response
            if ($userInfo) {
                $userInfo = $userInfo->object();
                // Send request to get info of the agent
                $agentInfo = $this->getUserInfoApi($userInfo->Agent);

                if($agentInfo) {
                    $agentInfo = $agentInfo->object();
                    // Find the agent's email in the users table
                    $agentUser = User::where('email', $agentInfo->Email)->first();

                    // If the agent is found, find the referral link
                    if ($agentUser) {
                        $referral = ReferralLink::where('user_id', $agentUser->id)->first();
                        if (!ReferralRelationship::where('user_id', $this->user->id)->exists()) {
                            // Call the UserReferred event
                            event(new UserReferred($referral->id, $this->user));
                        }
                    }
                }
            }
        }
    }
}
