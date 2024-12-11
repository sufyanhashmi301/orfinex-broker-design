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
                'driver' => 'google',
                'client_id' => 'your_google_client_id',
                'client_secret' => 'your_google_client_secret',
                'redirect' => 'https://yourapp.com/auth/google/callback',
                'status' => 1,
            ],
            [
                'title' => 'Facebook',
                'driver' => 'facebook',
                'client_id' => 'your_facebook_client_id',
                'client_secret' => 'your_facebook_client_secret',
                'redirect' => 'https://yourapp.com/auth/facebook/callback',
                'status' => 1,
            ],
            [
                'title' => 'X - Twitter',
                'driver' => 'twitter',
                'client_id' => 'your_twitter_client_id',
                'client_secret' => 'your_twitter_client_secret',
                'redirect' => 'https://yourapp.com/auth/twitter/callback',
                'status' => 1,
            ],
            [
                'title' => 'Instagram',
                'driver' => 'instagram',
                'client_id' => 'your_instagram_client_id',
                'client_secret' => 'your_instagram_client_secret',
                'redirect' => 'https://yourapp.com/auth/instagram/callback',
                'status' => 1,
            ],
            [
                'title' => 'Linkedin',
                'driver' => 'linkedin',
                'client_id' => 'your_linkedin_client_id',
                'client_secret' => 'your_linkedin_client_secret',
                'redirect' => 'https://yourapp.com/auth/linkedin/callback',
                'status' => 1,
            ],
            [
                'title' => 'Discord',
                'driver' => 'discord',
                'client_id' => 'your_discord_client_id',
                'client_secret' => 'your_discord_client_secret',
                'redirect' => 'https://yourapp.com/auth/discord/callback',
                'status' => 1,
            ],
        ]);
    }
}
