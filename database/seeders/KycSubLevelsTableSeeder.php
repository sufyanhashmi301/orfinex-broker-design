<?php

namespace Database\Seeders;

use App\Enums\KycLevelSlug;
use App\Enums\KycType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KycSubLevelsTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kyc_sub_levels')->truncate();
        // DB::table('kyc_level_settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // Retrieve the ID of Level 1, Level 2, and Level 3 from the kyc_levels table
        $level1 = DB::table('kyc_levels')->where('slug', KycLevelSlug::LEVEL1)->first()->id;
        $level2 = DB::table('kyc_levels')->where('slug', KycLevelSlug::LEVEL2)->first()->id;
        $level3 = DB::table('kyc_levels')->where('slug', KycLevelSlug::LEVEL3)->first()->id;

        // Default values for Level 1
        $defaultValuesLevel1 = [
            ['name' => KycType::PHONE, 'description' => 'Phone verification sub-level'],
            ['name' => KycType::EMAIL, 'description' => 'Email verification sub-level'],
        ];

        // Default values for Level 2
        $defaultValuesLevel2 = [
            ['name' => \App\Enums\KycType::MANUAL, 'description' => 'Manual verification sub-level', 'status' => true],
            ['name' => \App\Enums\KycType::AUTOMATIC, 'description' => 'Automatic verification sub-level', 'status' => false],
        ];

        // Default values for Level 3
        $defaultValuesLevel3 = [
            ['name' => 'Proof of Documents', 'description' => 'Proof of documents verification sub-level'],
            ['name' => 'Address', 'description' => 'Address verification sub-level'],
        ];

        // Insert Level 1 sub-levels into kyc_sub_levels table
        foreach ($defaultValuesLevel1 as $subLevel) {
            DB::table('kyc_sub_levels')->insert([
                'kyc_level_id' => $level1,
                'name' => $subLevel['name'],
                'description' => $subLevel['description'],
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Level 2 sub-levels into kyc_sub_levels table
        foreach ($defaultValuesLevel2 as $subLevel) {
            DB::table('kyc_sub_levels')->insert([
                'kyc_level_id' => $level2,
                'name' => $subLevel['name'],
                'description' => $subLevel['description'],
                'status' => $subLevel['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Level 3 sub-levels into kyc_sub_levels table
        foreach ($defaultValuesLevel3 as $subLevel) {
            DB::table('kyc_sub_levels')->insert([
                'kyc_level_id' => $level3,
                'name' => $subLevel['name'],
                'description' => $subLevel['description'],
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
