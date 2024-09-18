<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KycsTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('kycs')->truncate();
        // DB::table('kyc_level_settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Retrieve the ID of the 'Manual' and 'Level3' sub-levels from kyc_sub_levels table
        $manualSubLevel = DB::table('kyc_sub_levels')->where('name', \App\Enums\KycType::MANUAL)->first();
        $level3SubLevel = DB::table('kyc_sub_levels')->where('name', 'Proof of Documents')->first();

        if (!$manualSubLevel || !$level3SubLevel) {
            throw new \Exception("The required KYC sub-levels 'Manual' or 'Level3' are missing.");
        }

        // Use the id property
        $manualSubLevelId = $manualSubLevel->id;
        $level3SubLevelId = $level3SubLevel->id;

        // Default values for 'Manual' sub-level
        $defaultValuesManual = [
            [
                'kyc_sub_level_id' => $manualSubLevelId,
                'name' => 'Passport',
                'fields' => json_encode([
                    '1' => ['name' => 'Passport', 'type' => 'file', 'validation' => 'required'],
                ]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kyc_sub_level_id' => $manualSubLevelId,
                'name' => 'ID card',
                'fields' => json_encode([
                    '1' => ['name' => 'ID card front side', 'type' => 'file', 'validation' => 'required'],
                    '2' => ['name' => 'ID card back side', 'type' => 'file', 'validation' => 'required']
                ]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Default values for 'Level3' sub-level
        $defaultValuesLevel3 = [
            [
                'kyc_sub_level_id' => $level3SubLevelId,
                'name' => 'Proof of Address',
                'fields' => json_encode([
                    '1' => ['name' => 'Proof of Address Document', 'type' => 'file', 'validation' => 'required']
                ]),
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data into kycs table
        DB::table('kycs')->insert(array_merge($defaultValuesManual, $defaultValuesLevel3));
    }
}
