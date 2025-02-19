<?php

namespace App\Console\Commands;

use App\Enums\IBStatus;
use App\Models\Ranking;
use App\Models\RebateRule;
use App\Models\User;
use App\Models\UserIbRule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ManageIBUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manage:ib-users {ibGroupId} {users*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage IB group ID, ranking, and handle missing users.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        //php artisan manage:ib-users 8 "{\"email\":\"amabhijitmukherjee5@gmail.com\",\"name\":\"Abhijeet Mukherjee\",\"phone\":\"+91 9425524814\",\"gender\":\"male\",\"address\":\"Shankar Nagar\"}" "{\"email\":\"Ajayrajput6562@gmail.com\",\"name\":\"Ajay Chauhan\",\"phone\":\"9999422261\",\"gender\":\"male\",\"address\":\"Sector-44, Noida\"}" "{\"email\":\"ajitinamke7@gmail.com\",\"name\":\"Ajit Devaram Inamke\",\"phone\":\"+91 9922939095\",\"gender\":\"male\",\"address\":\"A/p Saswad Inamkemala Tal Puran Dhar Dist Pune\"}" "{\"email\":\"adeep1809@gmail.com\",\"name\":\"Amandeep Singh Sarpal\",\"phone\":\"+91 09098684438\",\"gender\":\"male\",\"address\":\"EWS 723, Vaishali Nagar, Bhilai\"}" "{\"email\":\"sahuanil1970aks@gmail.com\",\"name\":\"Anil Kumar Sahu\",\"phone\":\"+91 7987044484\",\"gender\":\"male\",\"address\":\"Dindayal Nagar Rajnandgaon\"}"


        $ibGroupId = $this->argument('ibGroupId');
        $users = $this->argument('users');

        // Use a default rank
        $rank = Ranking::find(1);
        if (!$rank) {
            $this->error("Default ranking with ID 1 not found.");
            return;
        }

        foreach ($users as $userData) {
            $user = json_decode($userData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error("Invalid JSON format for user data: {$userData}");
                continue;
            }

            $email = $user['email'] ;
            $phone = $user['phone'] ?? '+971';
            $gender = $user['gender'] ?? 'other';
            $address = $user['address'] ?? null;
            $countryName =  'United Arab Emirates';

            // Split name into first and last name
            $nameParts = explode(' ', $user['name'] ?? 'Default User');
            $firstName = $nameParts[0] ?? 'Default';
            $lastName = $nameParts[1] ?? 'User';

            if (!$email) {
                $this->error("Invalid user data: email is required.");
                continue;
            }

            $this->info("Processing user email: {$email}");

            // Find or create the user
            $existingUser = User::where('email', $email)->first();
            if (!$existingUser) {
                $this->info("User not found. Creating user: {$email}");

                // Generate unique username
                $username = $this->generateUniqueUsername($firstName);

                $newUser = new User();
                $newUser->email = $email;
                $newUser->first_name = $firstName;
                $newUser->last_name = $lastName;
                $newUser->username = $username;
                $newUser->phone = $phone;
                $newUser->gender = $gender;
                $newUser->country = $countryName;
                $newUser->address = $address;
                $newUser->ib_status = IBStatus::APPROVED;
                $newUser->ib_group_id = $ibGroupId;
                $newUser->ranking_id = $rank->id; // Assign default rank
                $newUser->password = Hash::make('password123');
                $newUser->email_verified_at = Carbon::now();
                $newUser->created_at = Carbon::now();
                $newUser->updated_at = Carbon::now();

                try {
                    if (!$newUser->save()) {
                        $this->error("Failed to save user: {$email}. Possible validation error.");
                        continue;
                    }
                    $this->manageUserRebateRules($newUser, $ibGroupId);
                    $this->info("User created with email: {$email}");
                } catch (\Exception $e) {
                    $this->error("Failed to create user: {$email}. Error: {$e->getMessage()}");
                    continue;
                }

            } else {
                $this->info("User found. Updating IB group ID and ranking: {$email}");

                try {
                    $existingUser->update([
                        'ib_status' => IBStatus::APPROVED,
                        'email_verified_at' => Carbon::now(),
                        'ib_group_id' => $ibGroupId,
                    ]);
                    $this->manageUserRebateRules($existingUser, $ibGroupId);

                    $this->info("IB group ID and ranking updated for user: {$email}");
                } catch (\Exception $e) {
                    $this->error("Failed to update user: {$email}. Error: {$e->getMessage()}");
                    continue;
                }
            }
        }

        $this->info("IB user management completed.");
    }

    /**
     * Generate a unique username based on the first name.
     *
     * @param string $firstName The first name of the user.
     * @return string A unique username.
     */
    private function generateUniqueUsername(string $firstName): string
    {
        // Sanitize the base username by removing spaces and converting to lowercase
        $usernameBase = strtolower(preg_replace('/\s+/', '', $firstName));

        // Start with a random 4-digit number appended to the base
        $username = $usernameBase . rand(1000, 9999);

        // Check for uniqueness in the database and regenerate if necessary
        while (User::where('username', $username)->exists()) {
            $username = $usernameBase . rand(1000, 9999);
        }

        return $username;
    }

    protected function manageUserRebateRules($user, $ibGroup)
    {
        // Remove existing rebate rules for the user
        UserIbRule::where('user_id', $user->id)->delete();

        if ($ibGroup) {
            // Fetch all rebate rules associated with the IB Group
            $rebateRules = RebateRule::whereHas('ibGroups', function ($query) use ($ibGroup) {
                $query->where('ib_groups.id', $ibGroup);
            })->get();

            // Assign rebate rules to the user
            foreach ($rebateRules as $rebateRule) {
                UserIbRule::create([
                    'user_id' => $user->id,
                    'ib_group_id' => $ibGroup,
                    'rebate_rule_id' => $rebateRule->id,
//                    'sub_ib_share' => $rebateRule->rebate_amount // Example: Use rebate amount as sub_ib_share; modify as needed
                ]);
            }
        }
    }

}
