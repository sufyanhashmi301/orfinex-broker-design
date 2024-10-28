<?php

namespace App\Console\Commands;

use App\Enums\IBStatus;
use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Country;
use App\Models\Ranking;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Enums\KYCStatus;

class IBUsersForBanex extends Command
{
    protected $signature = 'create:banex-users';
    protected $description = 'Create users with predefined names and emails for Banex Capital';

    public function handle()
    {
        // List of names for all users
        $names = [
            "Afraz New Deal 15",
//            "Afraz Ul Haq UAE Live Rate",
            "Afzal Imran",
            "Amannulla Abdul Rahman Abdul Rahman",
            "Amit Panara Bipinchandra",
            "Amjad Sardar",
            "Asad Saeed",
            "Ashraf Asad",
            "Asif Iqbal Malik Qadir Dad",
            "Asif New Deal 15",
            "BackOffice BanexCapital",
            "Chirag Bhatt",
            "Farooq Sarwar Janjua",
            "FATIMA NOUSHEEN",
            "Future Trading",
            "Gaurav Bahri Rajan Bahri",
            "Hiteshkumar Challa Arvindbhai",
            "JAYDEEP RAMESHBHAI PAREKH",
            "Karim Sheikh zeeshan",
            "Kasif Yousuf Muhammad",
            "Manish Harishbhai Mistry",
            "Mudassir Guftar",
            "Muhammad Ali Abdul Ghaffar",
            "Muhammad Danish Chughati Sajid Chughtai",
            "Muhammad Nawaz",
            "Muhammad Sahid Muhammad Hanif",
            "Nasir Ali Liaquat Ali",
            "Rameez Saeed",
            "Rana_PKR ONLY_PKR_CLIENT",
//            "Riaz Kasif Muhammad Alauddin",
            "Ruksarbanu Dhobi fakirmamad",
            "RWK Gold",
            "RWK Live 50-50",
            "Saddam Hussain",
            "Saima Naz",
            "Shafeeq Muhammad",
            "Sulaiman Abdul Rahman",
            "Syed Farukh Bukhari Abbas",
            "Usman Muhammad"
        ];

        // Users who should have last_name set to 'level-1'
        $level1Users = [
            "Afraz Ul Haq UAE Live Rate",
            "Amit Panara Bipinchandra",
            "Asif Iqbal Malik Qadir Dad",
            "BanexCapital IB",
            "FATIMA NOUSHEEN",
            "Hamida Khan Iqbal Khan",
            "JAYDEEP RAMESHBHAI PAREKH",
            "Kamlesh lakhamshi Vora",
            "Muhammad Shahid Razaq Abdul",
            "Muhammad Sohil Razzaq Abdul",
            "Riaz Kasif Muhammad Alauddin",
            "RITU KAPOOR BHAGWAN PATIDAR",
            "Ruksarbanu Dhobi fakirmamad"
        ];

        // Fetch ranking ID to assign to all users
        $rank = Ranking::find(1);
        if (!$rank) {
            $this->error('Ranking with ID 1 not found. Aborting.');
            return;
        }

        $createdUsersCount = 0;
        foreach ($level1Users as $name) {
            // Process name to generate email
            $email = strtolower(str_replace(' ', '', $name)) . '@banexcapital.com';

            // Check if user already exists to prevent duplicates
            if (User::where('email', $email)->exists()) {
                $this->info("User with email {$email} already exists.");
                Log::info("Duplicate email: {$email}");
                continue;
            }

            // Split name for first and last name if applicable
            $nameParts = explode(' ', $name);
            $firstName = array_shift($nameParts);

            // Assign 'level-1' or 'level-2' as last name based on the name list
            $lastName = in_array($name, $level1Users) ? 'level-1' : 'level-2';

            // Hardcoded KYC level as Level2
            $kyc = KYCStatus::Level2->value;

            // Default country and phone setup
            $countryName = 'United Arab Emirates';
            $phone = '+971';
            $country = Country::where('name', 'United Arab Emirates')->first();
            if ($country) {
                $countryName = $country->name;
                $phone = '+' . $country->country_code;
            }

            // Generate unique username based on first name and random number
            $usernameBase = strtolower($firstName);
            $username = $usernameBase . rand(1000, 9999);
            while (User::where('username', $username)->exists()) {
                $username = $usernameBase . rand(1000, 9999);
            }

            // Create new user with additional columns
            $user = new User();
            $user->ranking_id = $rank->id;
            $user->rankings = json_encode([$rank->id]);
            $user->first_name = $name;
            $user->last_name = $lastName;
            $user->city = 'Unknown';
            $user->country = $countryName;
            $user->phone = $phone;
            $user->username = $username;
            $user->email = $email;
            $user->email_verified_at = Carbon::now();
            $user->gender = 'other'; // Default gender
            $user->status = 1; // Active status
            $user->kyc = $kyc;
            $user->ib_status = IBStatus::APPROVED;
            $user->kyc_credential = null; // Assuming null JSON field
            $user->created_at = Carbon::now();
            $user->updated_at = Carbon::now();
            $user->password = Hash::make('banex12345'); // Default password

            try {
                $user->save();
                $createdUsersCount++;
                $this->info("Created user: {$email}");
            } catch (\Exception $e) {
                $this->error("Failed to create user for {$name}: " . $e->getMessage());
                Log::error("Failed to create user {$email}: " . $e->getMessage());
            }
        }
        foreach ($names as $name) {
            // Process name to generate email
            $email = strtolower(str_replace(' ', '', $name)) . '@banexcapital.com';

            // Check if user already exists to prevent duplicates
            if (User::where('email', $email)->exists()) {
                $this->info("User with email {$email} already exists.");
                Log::info("Duplicate email: {$email}");
                continue;
            }

            // Split name for first and last name if applicable
            $nameParts = explode(' ', $name);
            $firstName = array_shift($nameParts);

            // Assign 'level-1' or 'level-2' as last name based on the name list
            $lastName = in_array($name, $level1Users) ? 'level-1' : 'level-2';

            // Hardcoded KYC level as Level2
            $kyc = KYCStatus::Level2->value;

            // Default country and phone setup
            $countryName = 'United Arab Emirates';
            $phone = '+971';
            $country = Country::where('name', 'United Arab Emirates')->first();
            if ($country) {
                $countryName = $country->name;
                $phone = '+' . $country->country_code;
            }

            // Generate unique username based on first name and random number
            $usernameBase = strtolower($firstName);
            $username = $usernameBase . rand(1000, 9999);
            while (User::where('username', $username)->exists()) {
                $username = $usernameBase . rand(1000, 9999);
            }

            // Create new user with additional columns
            $user = new User();
            $user->ranking_id = $rank->id;
            $user->rankings = json_encode([$rank->id]);
            $user->first_name = $name;
            $user->last_name = $lastName;
            $user->city = 'Unknown';
            $user->country = $countryName;
            $user->phone = $phone;
            $user->username = $username;
            $user->email = $email;
            $user->email_verified_at = Carbon::now();
            $user->gender = 'other'; // Default gender
            $user->status = 1; // Active status
            $user->kyc = $kyc;
            $user->ib_status = IBStatus::APPROVED;
            $user->kyc_credential = null; // Assuming null JSON field
            $user->created_at = Carbon::now();
            $user->updated_at = Carbon::now();
            $user->password = Hash::make('banex12345'); // Default password

            try {
                $user->save();
                $createdUsersCount++;
                $this->info("Created user: {$email}");
            } catch (\Exception $e) {
                $this->error("Failed to create user for {$name}: " . $e->getMessage());
                Log::error("Failed to create user {$email}: " . $e->getMessage());
            }
        }


        // Summary
        $this->info("Total users created: {$createdUsersCount}");
        Log::info("Total users created: {$createdUsersCount}");
    }
}
