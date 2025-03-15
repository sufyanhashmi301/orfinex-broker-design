<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lead_sources')->truncate();
        DB::table('lead_sources')->insert([
            [
                'name' => 'Email',
            ],
            [
                'name' => 'Google',
            ],
            [
                'name' => 'Facebook',
            ],
            [
                'name' => 'Direct',
            ],
            [
                'name' => 'Tv',
            ],
            [
                'name' => 'Friend',
            ],
        ]);
    }
}
