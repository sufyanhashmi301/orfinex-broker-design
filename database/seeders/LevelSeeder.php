<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('levels')->insert([
            ['title' => 'Level 1', 'level_order' => 1],
//            ['title' => 'Level 2', 'level_order' => 2],
//            ['title' => 'Level 3', 'level_order' => 3],
//            ['title' => 'Level 4', 'level_order' => 4],
//            ['title' => 'Level 5', 'level_order' => 5],
        ]);    }
}
