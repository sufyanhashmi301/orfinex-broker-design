<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('languages')->delete();
        
        \DB::table('languages')->insert(array (
            0 => 
            array (
                'created_at' => NULL,
                'flag' => NULL,
                'id' => 1,
                'is_default' => 1,
                'locale' => 'en',
                'name' => 'English',
                'status' => 1,
                'updated_at' => '2024-01-21 15:26:22',
            ),
            1 => 
            array (
                'created_at' => '2022-10-06 00:17:20',
                'flag' => NULL,
                'id' => 8,
                'is_default' => 0,
                'locale' => 'es',
                'name' => 'Spanish',
                'status' => 1,
                'updated_at' => '2024-01-21 15:26:27',
            ),
            2 => 
            array (
                'created_at' => '2022-11-07 06:23:27',
                'flag' => NULL,
                'id' => 10,
                'is_default' => 0,
                'locale' => 'fr',
                'name' => 'Franch',
                'status' => 0,
                'updated_at' => '2024-01-21 15:26:32',
            ),
            3 => 
            array (
                'created_at' => '2024-10-12 07:59:14',
                'flag' => NULL,
                'id' => 12,
                'is_default' => 0,
                'locale' => 'zh',
                'name' => 'Chines',
                'status' => 1,
                'updated_at' => '2024-10-12 07:59:14',
            ),
            4 => 
            array (
                'created_at' => '2024-10-12 08:09:25',
                'flag' => NULL,
                'id' => 13,
                'is_default' => 0,
                'locale' => 'ar',
                'name' => 'Arabic',
                'status' => 1,
                'updated_at' => '2024-10-12 08:09:25',
            ),
        ));
        
        
    }
}