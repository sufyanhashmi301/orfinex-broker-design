<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StorageSeeder extends Seeder
{
    public static $TOTAL_STORAGE_METHODS = 2;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('storages')->truncate();

        $data= [ 
            // Filesystem
            [
                'icon' => '',
                'method' => 'filesystem',
                'details' => '{}',
                'description' => 'All the files will be stored in filsystem of the website.',
                'status' => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            // Amazon S3 
            [
                'icon' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Amazon-S3-Logo.svg/1712px-Amazon-S3-Logo.svg.png',
                'method' => 'aws_amazon_s3',
                'details' => '{}',
                'description' => '(Recommended) All the files will be stored in AWS Amazon S3 buckets.',
                'status' => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],


        ];

        DB::table('storages')->insert($data);
    }
}
