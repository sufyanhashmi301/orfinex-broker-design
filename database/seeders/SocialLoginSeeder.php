<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('socials')->truncate();

        // Insert social links
        DB::table('socials')->insert([
            [
                'title' => 'Google',
                'description' => 'Allow users to connect with their Google accounts, ensuring easy and efficient account access.',
                'driver' => 'google',
                'client_id' => 'your_google_client_id',
                'client_secret' => 'your_google_client_secret',
                'redirect' => 'https://yourapp.com/auth/google/callback',
                'status' => false,
            ],
            [
                'title' => 'Facebook',
                'description' => 'Facilitate user login via Facebook, leveraging one of the most popular social platforms for enhanced accessibility.',
                'driver' => 'facebook',
                'client_id' => 'your_facebook_client_id',
                'client_secret' => 'your_facebook_client_secret',
                'redirect' => 'https://yourapp.com/auth/facebook/callback',
                'status' => false,
            ],
            [
                'title' => 'X - Twitter',
                'description' => 'Allow users to log in with their X accounts, providing a secure and modern authentication method.',
                'driver' => 'twitter',
                'client_id' => 'your_twitter_client_id',
                'client_secret' => 'your_twitter_client_secret',
                'redirect' => 'https://yourapp.com/auth/twitter/callback',
                'status' => false,
            ],
            [
                'title' => 'Instagram',
                'description' => 'Offer Instagram login for users who prefer connecting through their social media accounts.',
                'driver' => 'instagram',
                'client_id' => 'your_instagram_client_id',
                'client_secret' => 'your_instagram_client_secret',
                'redirect' => 'https://yourapp.com/auth/instagram/callback',
                'status' => false,
            ],
            [
                'title' => 'Linkedin',
                'description' => 'Integrate LinkedIn login to provide professional users a streamlined access option to your platform.',
                'driver' => 'linkedin',
                'client_id' => 'your_linkedin_client_id',
                'client_secret' => 'your_linkedin_client_secret',
                'redirect' => 'https://yourapp.com/auth/linkedin/callback',
                'status' => false,
            ],
            [
                'title' => 'Discord',
                'description' => 'Enable your users to log in with their Discord accounts for seamless integration and a secure login experience.',
                'driver' => 'discord',
                'client_id' => 'your_discord_client_id',
                'client_secret' => 'your_discord_client_secret',
                'redirect' => 'https://yourapp.com/auth/discord/callback',
                'status' => false,
            ],
        ]);
    }
}
