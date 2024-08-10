<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserImport;
use Illuminate\Console\Command;

class UpdateNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:names';

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
        $lname = 'lname';
        // Get users with specified last name
//        $users = User::all();
//        $user= User::where('email','walimuhammad5173865')->first();
//        dd($user);
        $users= User::where('last_name','')->get();
        foreach ($users as $user) {

//            if (!isset($user->last_name) || empty($user->last_name)) {
                // Split the first name into an array of words
                $firstNameParts = explode(' ', $user->first_name);

                // Determine the number of parts in the first name
                $numParts = count($firstNameParts);

                if ($numParts == 1) {
                    // If only one part, replace last_name with single value
                    $user->last_name = $firstNameParts[0];
                } elseif ($numParts == 2) {
                    // If two parts, replace first_name with the first value and last_name with the second value
                    $user->first_name = $firstNameParts[0];
                    $user->last_name = $firstNameParts[1];
                } elseif ($numParts >= 3) {
                    // If three or more parts, replace first_name with the first two values and last_name with the third value
                    $user->first_name = implode(' ', array_slice($firstNameParts, 0, 2));
                    $user->last_name = $firstNameParts[2];
                }
                $user->save();
//            }else{
//                $user->last_name = $user->l_name;
//                $user->save();
//            }
            }


//            }
//            }
//        }

//        return $users;
    }

}
