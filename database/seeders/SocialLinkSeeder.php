<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        DB::table('social_links')->truncate();
        DB::table('social_links')->insert([
            [
                'title' => 'Facebook',
                'link' => 'https://facebook.com/',
                'slug' => 'facebook_link',
                'status' => 1,
            ],
            [
                'title' => 'X - Twitter',
                'link' => 'https://twitter.com/',
                'slug' => 'twitter_link',
                'status' => 1,
            ],
            [
                'title' => 'Instagram',
                'link' => 'https://instagram.com/',
                'slug' => 'instagram_link',
                'status' => 1,
            ],
            [
                'title' => 'Linkedin',
                'link' => 'https://linkedin.com/',
                'slug' => 'linkedin_link',
                'status' => 1,
            ],
            [
                'title' => 'Telegram',
                'link' => 'https://telegram.org/',
                'slug' => 'telegram_link',
                'status' => 1,
            ],
            [
                'title' => 'Discord',
                'link' => 'https://discord.com/',
                'slug' => 'discord_link',
                'status' => 1,
            ],
            [
                'title' => 'Skype',
                'link' => 'https://www.skype.com/en/',
                'slug' => 'skype_link',
                'status' => 1,
            ],
            [
                'title' => 'Whatsapp',
                'link' => 'https://www.whatsapp.com/',
                'slug' => 'whatsapp_link',
                'status' => 1,
            ],
        ]);
    }
}
