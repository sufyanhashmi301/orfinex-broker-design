<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the table before inserting new data
        DB::table('social_links')->truncate();

        // Insert social links
//        DB::table('social_links')->insert([
//            [
//                'title' => 'Google',
//                'slug' => 'google_link',
//                'driver' => 'google',
//                'client_id' => 'your_google_client_id',
//                'client_secret' => 'your_google_client_secret',
//                'redirect' => 'https://yourapp.com/auth/google/callback',
//                'status' => 1,
//            ],
//            [
//                'title' => 'Facebook',
//                'slug' => 'facebook_link',
//                'driver' => 'facebook',
//                'client_id' => 'your_facebook_client_id',
//                'client_secret' => 'your_facebook_client_secret',
//                'redirect' => 'https://yourapp.com/auth/facebook/callback',
//                'status' => 1,
//            ],
//            [
//                'title' => 'X - Twitter',
//                'slug' => 'twitter_link',
//                'driver' => 'twitter',
//                'client_id' => 'your_twitter_client_id',
//                'client_secret' => 'your_twitter_client_secret',
//                'redirect' => 'https://yourapp.com/auth/twitter/callback',
//                'status' => 1,
//            ],
//            [
//                'title' => 'Instagram',
//                'slug' => 'instagram_link',
//                'driver' => 'instagram',
//                'client_id' => 'your_instagram_client_id',
//                'client_secret' => 'your_instagram_client_secret',
//                'redirect' => 'https://yourapp.com/auth/instagram/callback',
//                'status' => 1,
//            ],
//            [
//                'title' => 'Linkedin',
//                'slug' => 'linkedin_link',
//                'driver' => 'linkedin',
//                'client_id' => 'your_linkedin_client_id',
//                'client_secret' => 'your_linkedin_client_secret',
//                'redirect' => 'https://yourapp.com/auth/linkedin/callback',
//                'status' => 1,
//            ],
////            [
////                'title' => 'Telegram',
////                'slug' => 'telegram_link',
////                'driver' => null, // Telegram might not use OAuth2
////                'client_id' => null,
////                'client_secret' => null,
////                'redirect' => null,
////                'status' => 1,
////            ],
//            [
//                'title' => 'Discord',
//                'slug' => 'discord_link',
//                'driver' => 'discord',
//                'client_id' => 'your_discord_client_id',
//                'client_secret' => 'your_discord_client_secret',
//                'redirect' => 'https://yourapp.com/auth/discord/callback',
//                'status' => 1,
//            ],
////            [
////                'title' => 'Skype',
////                'slug' => 'skype_link',
////                'driver' => null, // Skype might not use OAuth2
////                'client_id' => null,
////                'client_secret' => null,
////                'redirect' => null,
////                'status' => 1,
////            ],
////            [
////                'title' => 'Whatsapp',
////                'slug' => 'whatsapp_link',
////                'driver' => null, // WhatsApp might not use OAuth2
////                'client_id' => null,
////                'client_secret' => null,
////                'redirect' => null,
////                'status' => 1,
////            ],
//        ]);
    }
}
