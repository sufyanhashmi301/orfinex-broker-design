<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KycMethodSeeder extends Seeder
{
    public static $TOTAL_KYC_METHODS = 2;

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
                'data' => '[{"name":"Passport","status":"1","fields":{"1":{"name":"Picture","type":"file","validation":"required"},"2":{"name":"Passport Address","type":"textarea","validation":"required"}}},{"name":"ID Card","status":"1","fields":{"1":{"name":"Front Side","type":"file","validation":"required"},"2":{"name":"Back Side","type":"file","validation":"required"}}}]',
                'status' => 1,
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
