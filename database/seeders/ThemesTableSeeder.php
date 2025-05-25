<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ThemesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('themes')->delete();
        
        \DB::table('themes')->insert(array (
            0 => 
            array (
                'created_at' => '2023-07-04 23:47:28',
                'id' => 1,
                'name' => 'prime_x',
                'status' => 1,
                'type' => 'site',
                'updated_at' => '2024-06-20 14:10:50',
            ),
        ));
        
        
    }
}