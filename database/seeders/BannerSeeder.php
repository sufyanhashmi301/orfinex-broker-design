<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banners')->insert([
            [
                'title' => 'Start your free trail today.',
                'subtitle' => 'Ready to dive in?',
                'primary_link' => null,
                'button_text' => null,
                'button_link' => null,
                'status' => 1,
            ],
            [
                'title' => 'Start your free trail today.',
                'subtitle' => 'Ready to dive in?',
                'primary_link' => null,
                'button_text' => null,
                'button_link' => null,
                'status' => 1,
            ],
            [
                'title' => 'Start your free trail today.',
                'subtitle' => 'Ready to dive in?',
                'primary_link' => null,
                'button_text' => null,
                'button_link' => null,
                'status' => 1,
            ],
        ]);
    }
}
