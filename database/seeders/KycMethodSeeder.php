<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KycMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kyc_methods')->truncate();

        $data= [ 
            // Manual
            [
                'slug' => 'manual',
                'icon' => '',
                'name' => 'Manual KYC',
                'description' => 'Allows the user to manually submit details.',
                'data' => '[]',
                'status' => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            // SumSub 
            [
                'slug' => 'sumsub',
                'icon' => 'global/plugin/sumsub.webp',
                'name' => 'Sumsub KYC (Automated)',
                'description' => 'KYC verification will be done automatically via Sumsub.',
                'data' => '[]',
                'status' => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],


        ];

        DB::table('kyc_methods')->insert($data);
    }
}
