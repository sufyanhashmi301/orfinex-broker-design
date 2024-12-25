<?php

namespace App\Console\Commands;

use App\Models\Ranking;
use App\Models\ReferralLink;
use App\Models\ReferralRelationship;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HandleReferrals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handle:referrals {parentEmail} {childEmails*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle referral relationships for parent and child emails';

    public function handle()
    {
        //php artisan handle:referrals parent@example.com child1@example.com child2@example.com
        //php artisan handle:referrals  spatidar2008.2008@gmail.com "{\"email\":\"birdlover1260@gmail.com\",\"name\":\"John Kiminza Ndinga\",\"phone\":\"+254 8452636254\"}" "{\"email\":\"mansoorarrain@gmail.com\",\"name\":\"mansoor\",\"phone\":\"+92 3007888444\"}" "{\"email\":\"tennistable807@gmail.com\",\"name\":\"Ekalinga Ekapad\",\"phone\":\"+91 7555212029\"}" "{\"email\":\"maslamirch6@gmail.com\",\"name\":\"Alok Verma\",\"phone\":\"+91 7555220281\"}" "{\"email\":\"bluewater1260@gmail.com\",\"name\":\"Aman Gazdar\",\"phone\":\"+91 7555220282\"}" "{\"email\":\"miliontrilion07@gmail.com\",\"name\":\"Rabindra Jadeja\",\"phone\":\"+91 7555220833\"}" "{\"email\":\"monalishindefx01@gmail.com\",\"name\":\"Monali shinde\",\"phone\":\"+91 7303333321\"}" "{\"email\":\"hassnain.zahid@gmail.com\",\"name\":\"Hassnain\",\"phone\":\"+92 3217233927\"}" "{\"email\":\"hs7302384@gmail.com\",\"name\":\"Sajid Shaikh\",\"phone\":\"+971 5472840489\"}" "{\"email\":\"chachug643@gmail.com\",\"name\":\"Usman Zafar\",\"phone\":\"+92 03443389403\"}"


        $parentEmail = $this->argument('parentEmail');
        $childEmails = $this->argument('childEmails');

        $parentUser = User::where('email', $parentEmail)->first();

        if (!$parentUser) {
            $this->error("Parent user with email {$parentEmail} does not exist.");
            return;
        }

        $referral = ReferralLink::where('user_id', $parentUser->id)->first();

        if (is_null($referral)) {
            $this->error("Referral link not found for parent email: {$parentEmail}");
            return;
        }
        $rank = Ranking::find(1);

        if (!$rank) {
            $this->error("Default ranking not found. Migration aborted.");
            return;
        }
        foreach ($childEmails as $childData) {
            $child = json_decode($childData, true);
            $childEmail = $child['email'] ?? null;
            $childName = $child['name'] ?? 'Child User';
            $childPhone = $child['phone'] ?? '+971';
            $countryName =  'United Arab Emirates';

            if (!$childEmail) {
                $this->error("Invalid child data: email is required.");
                continue;
            }

            $this->info("Processing child email: {$childEmail}");

            $childUser = User::where('email', $childEmail)->first();

            if (!$childUser) {
                $this->info("Child email not found. Creating user: {$childEmail}");

                // Split name into first and last name
                $nameParts = explode(' ', $childName);
                $firstName = $nameParts[0] ?? 'Child';
                $lastName = $nameParts[1] ?? 'User';
                // Generate unique username
                $usernameBase = strtolower(preg_replace('/\s+/', '', $firstName));
                $username = $usernameBase . rand(1000, 9999);
                while (User::where('username', $username)->exists()) {
                    $username = $usernameBase . rand(1000, 9999);
                }

                // Create new user
                $childUser = new User();
                $childUser->ranking_id = $rank->id;
                $childUser->first_name = $firstName;
                $childUser->last_name = $lastName;
                $childUser->username = $username;
                $childUser->email = $childEmail;
                $childUser->phone = $childPhone;
                $childUser->gender = 'other';
                $childUser->country = $countryName;
                $childUser->password = Hash::make('password123');
                $childUser->email_verified_at = Carbon::now();
                $childUser->created_at = Carbon::now();
                $childUser->updated_at = Carbon::now();

                try {
                    $childUser->save();
                    $this->info("Child user created: {$childEmail}");
                } catch (\Exception $e) {
                    $this->error("Failed to create child user: {$childEmail}. Error: " . $e->getMessage());
                    continue;
                }
            }

            try {
                ReferralRelationship::create([
                    'referral_link_id' => $referral->id,
                    'user_id' => $childUser->id,
                ]);

                $childUser->update(['ref_id' => $referral->user_id]);

                $this->info("Referral relationship established for child email: {$childEmail}");
            } catch (\Exception $e) {
                $this->error("Failed to establish referral for child email: {$childEmail}. Error: " . $e->getMessage());
                continue;
            }
        }

        $this->info("Referral processing completed.");
    }
}
