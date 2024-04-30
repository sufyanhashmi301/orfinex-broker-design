<?php

namespace App\Console\Commands;

use App\Models\ForexAccount;
use App\Models\IbQuestionAnswer;
use App\Models\LoginActivities;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Ranking;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\Ticket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawAccount;
use App\Traits\ForexApiTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AddDeletedUsers extends Command
{
    use ForexApiTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleted:users';

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
        $missingUsers = DB::table('forex_accounts')
            ->leftJoin('users', 'forex_accounts.user_id', '=', 'users.id')
            ->whereNull('users.id')
            ->select('forex_accounts.user_id', 'forex_accounts.login', 'forex_accounts.account_type')
            ->get();

        foreach ($missingUsers as $missingUser) {
//            dd($missingUser);
            $missingUserOldID = $missingUser->user_id;
            $user = User::find($missingUserOldID);
            if ($user) {
                echo 'User ID: ' . $user->ID . ' already exists' . '\n';
                continue;
            }

            $getUserResponse = $this->getUserInfoUrl($missingUser->login);

//            $getUserResponse = $this->getUserInfoUrl(874641);
//                dd($getUserResponse->object());
            if (!$getUserResponse) {
                echo 'Account Not exist: ' . $missingUser->login . "\n";
                continue;
            }
            $data = $getUserResponse->object();
            if ($data->Login == 0) {
                echo 'Account Not exist: ' . $missingUser->login . "\n";
                continue;
            }
//            dd($data);
//            if (User::where('email', $data->Email)->exists()) {
//                echo 'Email: ' . $data->Email . ' already exists' . "\n";
//                continue;
//            }

            if ($missingUser->account_type == 'real' && $data->Balance > 0) {
                if (User::where('email', $data->Email)->exists()) {
                    echo 'Email: ' . $data->Email . ' already exists' . "\n";
                    $newUser = User::where('email', $data->Email)->first();
                    $this->updateRecordWithNewUser($missingUserOldID, $newUser);

                } else {
                    echo 'Missing Email: ' . $data->Email .' Account: ' . $missingUser->login . ' balance: ' . $data->Balance . "\n";
                    $newUser = $this->createUser($data, $missingUserOldID);
//                    $newUser =  $this->updateRecordWithNewUser($newUser, $missingUserOldID);
                }

            }
//            echo 'Missing Email: ' . $data->Email . "\n";
//            if ($data->Agent != 0) {
////                $IbParent = $this->getUserInfoUrl(874641);
//                $IbParent = $this->getUserInfoUrl($data->Agent);
//                $IbParent = $IbParent->object();
//                if ($IbParent->Login == 0) {
//                    echo 'IB Account Not exist in Mt5: ' . $data->Agent . "\n";
////                    continue;
//                } else {
//                    $parentUser = User::where('email', $IbParent->Email)->first();
//
//                    echo 'Parent Email: ' . $parentUser->email . ' of ' . $data->Email . "\n";
//                }
//
//            }

//            Login::where('user_id',$missingUserOldID)->update(['user_id'=>$user->id]);
//            ForexAccount::where('user_id',$missingUserOldID)->update(['user_id'=>$user->id]);
//            Transaction::where('user_id',$missingUserOldID)->update(['user_id'=>$user->id]);

        }

        echo  '************ Deleted Records ****************' ."\n";

        //delete records
        foreach ($missingUsers as $missingUser) {
//            dd($missingUser);
            $missingUserOldID = $missingUser->user_id;
            $user = User::find($missingUserOldID);
            if ($user) {
                echo 'User ID: ' . $user->ID . ' already exists' ."\n";
                continue;
            }

            echo  'UserID: ' . $missingUserOldID .' Account: ' . $missingUser->login ."\n";

            $this->deleteRecords($missingUserOldID);

        }



        return Command::SUCCESS;
    }

    public function createUser($data, $missingUserOldID)
    {
        $firstNameParts = explode(' ', $data->Name);

        // Determine the number of parts in the first name
        $numParts = count($firstNameParts);

        if ($numParts == 1) {
            // If only one part, replace last_name with single value
            $firstName = $data->Name;
            $lastName = $firstNameParts[0];
        } elseif ($numParts == 2) {
            // If two parts, replace first_name with the first value and last_name with the second value
            $firstName = $firstNameParts[0];
            $lastName = $firstNameParts[1];
        } elseif ($numParts >= 3) {
            // If three or more parts, replace first_name with the first two values and last_name with the third value
            $firstName = implode(' ', array_slice($firstNameParts, 0, 2));
            $lastName = $firstNameParts[2];
        }

        $rank = Ranking::find(1);
        $newUser = new User();
// Set the attributes for the new user
        $newUser->id = $missingUserOldID; // Assign the deleted ID to the new user
        $newUser->ranking_id = $rank->id;
        $newUser->rankings = json_encode([$rank->id]);
        $newUser->first_name = $firstName;
        $newUser->last_name = $lastName;
        $newUser->username = $firstName . $lastName . rand(1000, 9999);
        $newUser->country = $data->Country;
        $newUser->phone = $data->Phone;
        $newUser->email = $data->Email;
        $newUser->password = Hash::make('orfinex123');
        $newUser->kyc = 1;
        $newUser->email_verified_at = Carbon::now();

        // Save the new user to the database
        $newUser->save();
        return $newUser;
    }

    public function updateRecordWithNewUser($missingUserOldID, $newUser)
    {
//            Login::where('user_id',$missingUserOldID)->update(['user_id'=>$newUser->id]);
        LoginActivities::where('user_id', $missingUserOldID)->delete();
        Notification::where('user_id', $missingUserOldID)->delete();
        ReferralLink::where('user_id', $missingUserOldID)->delete();
        ReferralRelationship::where('user_id', $missingUserOldID)->delete();
        ReferralRelationship::where('referral_link_id', $missingUserOldID)->delete();

        User::where('ref_id', $missingUserOldID)->update(['ref_id' => null]);
        Ticket::where('user_id', $missingUserOldID)->update(['user_id' => $newUser->id]);
        Message::where('user_id', $missingUserOldID)->delete();
        WithdrawAccount::where('user_id', $missingUserOldID)->update(['user_id' => $newUser->id]);
        ForexAccount::where('user_id', $missingUserOldID)->update(['user_id' => $newUser->id]);
        IbQuestionAnswer::where('user_id', $missingUserOldID)->update(['user_id' => $newUser->id]);
        Transaction::where('user_id', $missingUserOldID)->update(['user_id' => $newUser->id]);
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
    }
}
