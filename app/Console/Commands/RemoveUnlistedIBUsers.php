<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserIbRule;
use Illuminate\Console\Command;

class RemoveUnlistedIBUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:ib-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all IB users from users and user_ib_rules tables based on a provided list.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // List of users to delete (provided in the command details)
        $emailsToDelete = [
            "sumitagarwal@gmail.com",
            "priyagupta@gmail.com",
            "amitsharma@gmail.com",
            "riyakhan@gmail.com",
            "sureshsingh@gmail.com",
            "karansharma@gmail.com",
            "deepikamathur@gmail.com",
            "vikashgupta@gmail.com",
            "anushkaverma@gmail.com",
            "arunsingh@gmail.com",
            "aloksharma@gmail.com",
            "nehamishra@gmail.com",
            "rajatgupta@gmail.com",
            "soniyadav@gmail.com",
            "manojtiwari@gmail.com",
            "vishalkumar@gmail.com",
            "poojabhatia@gmail.com",
            "ankitverma@gmail.com",
            "meghasahu@gmail.com",
            "deepakrana@gmail.com",
            "satishphirke@gmail.com",
            "rashmi.tiwari@gmail.com",
            "rohitkumar.chauhan@gmail.com",
            "shivamthakur@gmail.com",
            "kiranmehta@gmail.com",
            "aspakbmw@gmail.com",
            "dwgalgo@gmail.com",
            "richgrowmate@gmail.com",
            "tripta12singh@gmail.com",
            "vinodpawar2306@gmail.com",
            "dr.bhagyashreerewale@gmail.com",
            "deepikakoshima@gmail.com",
            "dattapadale96@gmail.com",
            "patelsino1@gmail.com",
            "vmoustudent@gmail.com",
            "sohamrajdev@gmail.com",
            "Hardevchoudhary2000@gmail.com",
            "yaoJJJyao@aliyun.com",
            "kunalkhedekarft@gmail.com",
            "kunalkhedekar8@yahoo.com"
        ];

        // Get all users matching the emails
        $usersToDelete = User::whereIn('email', $emailsToDelete)->get();

        if ($usersToDelete->isEmpty()) {
            $this->info("No users found to delete.");
            return;
        }

        foreach ($usersToDelete as $user) {
            $this->info("Deleting user: {$user->email}");

            try {
                // Delete associated UserIbRule records
                UserIbRule::where('user_id', $user->id)->delete();

                // Delete the user record
                $user->delete();

                $this->info("User {$user->email} and associated records deleted successfully.");
            } catch (\Exception $e) {
                $this->error("Failed to delete user: {$user->email}. Error: {$e->getMessage()}");
            }
        }

        $this->info("All specified users have been deleted.");
    }
}
