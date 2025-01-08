<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class CertificateSeeder extends Seeder
{

    public static $TOTAL_CERTIFICATES = 4; // number of fields in the $data array

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('certificates')->truncate();

        $config_data = [
            'coordinate_x_name' => 0.00,
            'coordinate_y_name' => 0.00,
            'name_mention' => 'full_name',
            'name_font_size' => '40',
            'name_font_color' => '#fed490',

            'coordinate_x_date' => 0.00,
            'coordinate_y_date' => 0.00,
            'date_font_size' => '18',
            'date_font_color' => '#ffffff',
            
        ];

        $data= [ 
            // Evaluation Success
            [
                "title" => 'Evaluation Process Completed',
                "hook" => 'on_phase_one_success',
                "image" => '',
                "config" => json_encode($config_data),
                "is_enabled" => 0,
                "date_info" => 'on_success',
                "nickname_allowed" => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            // Verification Success
            [
                "title" => 'Verification Process Completed',
                "hook" => 'on_phase_two_success',
                "image" => '',
                "config" => json_encode($config_data),
                "is_enabled" => 0,
                "date_info" => 'on_success',
                "nickname_allowed" => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            // Affiliate Payout
            [
                "title" => 'Successful Affiliate Payout',
                "hook" => 'on_affiliate_payout_success',
                "image" => '',
                "config" => json_encode($config_data),
                "is_enabled" => 0,
                "date_info" => 'on_success',
                "nickname_allowed" => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            // Max Allocation
            [
                "title" => 'Max Allocation Reward',
                "hook" => 'on_max_allocation_success',
                "image" => '',
                "config" => json_encode($config_data),
                "is_enabled" => 0,
                "date_info" => 'on_success',
                "nickname_allowed" => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            // 

        ];
        DB::table('certificates')->insert($data);
    }
}
