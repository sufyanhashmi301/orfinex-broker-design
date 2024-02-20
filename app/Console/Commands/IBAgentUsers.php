<?php

namespace App\Console\Commands;

use App\Enums\IBStatus;
use App\Events\UserReferred;
use App\Models\ForexAccount;
use App\Models\ReferralRelationship;
use App\Models\User;
use App\Models\UserImport;
use Illuminate\Console\Command;

class IBAgentUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ib:agent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = UserImport::whereNotNull('agent')->get();
        foreach ($users as $userOld){
            $user = User::where('email',$userOld->email)->first();
            if($user) {
                $user->update(['ib_login' => $userOld->agent,'ib_status' => IBStatus::APPROVED]);
                $agentAccount = ForexAccount::where('login',$userOld->agent)->first();
                if($agentAccount) {
                    $parentReferral = $agentAccount->user;

                    $referral = $parentReferral->getReferrals()->first();
                    if (!ReferralRelationship::where('user_id', $user->id)->exists()) {
                        // Call the UserReferred event
                        event(new UserReferred($referral->id, $user));
                    }
                }
            }

        }
        return Command::SUCCESS;
    }
}
