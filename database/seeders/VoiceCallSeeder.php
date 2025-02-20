<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VoiceCallSeeder extends Seeder
{

    public static $TOTAL_VOICE_CALL_METHODS = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('voice_calls')->truncate();

        $data= [ 

            // Amazon S3 
            [
                'icon' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_pYLkt1HTTGyT49izVaUFCfVnv4YL9PZmTw&s',
                'method' => 'twilio',
                'details' => '{}',
                'description' => 'Twilio is a cloud-based communications platform that enables businesses to make, receive, and manage voice calls.',
                'status' => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],


        ];

        DB::table('voice_calls')->insert($data);
    }
}
