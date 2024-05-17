<?php

namespace App\Console\Commands;

use App\Models\ForexAccount;
use App\Models\IbQuestionAnswer;
use App\Models\LoginActivities;
use App\Models\Message;
use App\Models\Notification;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteNonActiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nonActive:users';

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
        $filteredusers = DB::connection('mt5_db')->table('users')
//            ->where('id',2)
            ->pluck('id');
        $users = User::whereIn('id',$filteredusers)->get();
//        $users = DB::select("SELECT users.*, forex_accounts.login AS forex_login FROM users
//                             LEFT JOIN forex_accounts ON users.id = forex_accounts.user_id WHERE users.id NOT IN (
//                                SELECT user_id FROM login_activities WHERE created_at >= NOW() - INTERVAL 4 MONTH
//                            UNION
//                            SELECT user_id FROM transactions WHERE target_type = 'forex_deposit' AND status = 'success' AND created_at >= NOW() - INTERVAL 4 MONTH
//                            UNION
//                            SELECT user_id FROM forex_accounts fa WHERE account_type = 'real' AND
//                                            EXISTS ( SELECT 1 FROM orfinex_new.mt5_accounts ma WHERE ma.Equity > 0 AND ma.Login = fa.login ) )");
//dd($users);
        foreach ($users as $user){
//            dd($user);
            $this->deleteRecords($user->id);
        }
        return Command::SUCCESS;
    }
    public function deleteRecords($missingUserOldID)
    {
//            Login::where('user_id',$missingUserOldID)->update(['user_id'=>$newUser->id]);
        LoginActivities::where('user_id', $missingUserOldID)->delete();
        Notification::where('user_id', $missingUserOldID)->delete();
        ReferralLink::where('user_id', $missingUserOldID)->delete();
        ReferralRelationship::where('user_id', $missingUserOldID)->delete();

        User::where('ref_id', $missingUserOldID)->update(['ref_id' => null]);
        Ticket::where('user_id', $missingUserOldID)->delete();
        Message::where('user_id', $missingUserOldID)->delete();
        WithdrawAccount::where('user_id', $missingUserOldID)->delete();
        ForexAccount::where('user_id', $missingUserOldID)->delete();
        IbQuestionAnswer::where('user_id', $missingUserOldID)->delete();
        Transaction::where('user_id', $missingUserOldID)->delete();
        User::where('id', $missingUserOldID)->delete();
    }
}
