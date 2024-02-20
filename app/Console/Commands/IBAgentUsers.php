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
        $users = UserImport::where('agent','!=',0)->get();
        foreach ($users as $userOld){
//            dd($userOld);
            $childUser = User::where('email',$userOld->email)->first();
            if($childUser) {
                $agentAccount = ForexAccount::where('login',$userOld->agent)->first();
                if($agentAccount) {
                    $parentReferral = $agentAccount->user;
                    $parentReferral->update(['ib_login' => $userOld->agent,'ib_status' => IBStatus::APPROVED]);

                    $referral = $parentReferral->getReferrals()->first();
                    if (!ReferralRelationship::where('user_id', $childUser->id)->exists()) {
                        // Call the UserReferred event
                        event(new UserReferred($referral->id, $childUser));
                        echo "parent User: ".$parentReferral->email.", child: ".$childUser->email.' Agent: '.$userOld->agent."\n";
                    }else{
                        echo "already created referral : ".$childUser->email." id: ".$childUser->id.' Agent: '.$userOld->agent."\n";
                    }
                }
            }else{
                echo "Not exist user : ".$userOld->email." login: ".$userOld->login.' Agent: '.$userOld->agent."\n";
            }

        }
        return Command::SUCCESS;
    }
}
