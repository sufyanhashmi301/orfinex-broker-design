<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KYCLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kyc_levels')->insert([
            ['name' => 'Level 1', 'status' => true, 'created_at' => now(), 'updated_at' => now(),'slug'=>'level-1'],
            ['name' => 'Level 2', 'status' => true, 'created_at' => now(), 'updated_at' => now(),'slug'=>'level-2'],
            ['name' => 'Level 3', 'status' => true, 'created_at' => now(), 'updated_at' => now(),'slug'=>'level-3'],
        ]);
    }
}
