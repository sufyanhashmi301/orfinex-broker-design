<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CustomCssTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('custom_css')->delete();
        
        \DB::table('custom_css')->insert(array (
            0 => 
            array (
                'created_at' => NULL,
                'css' => '//The Custom CSS will be added on the site head tag 
.site-head-tag {
margin: 0;
padding: 0;
}',
                'id' => 1,
                'updated_at' => '2022-11-17 16:44:06',
            ),
        ));
        
        
    }
}