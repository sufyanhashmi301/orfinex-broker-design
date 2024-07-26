<?php

namespace Database\Seeders;

use App\Models\Kyc;
use App\Models\Kyclevel;
use App\Models\Kyclevelsetting;
use Illuminate\Database\Seeder;

class KYCLevelSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Retrieve the KYC level ID for 'Level 2'
        $kycLevelIdLevel2 = Kyclevel::where('slug', 'level-2')->value('id');

        // Retrieve the KYC level ID for 'Level 1'
        $kycLevelIdLevel1 = Kyclevel::where('slug', 'level-1')->value('id');

        // Add default KYC records for Level 1
        $defaultValuesLevel1 = [
            ['name' => 'Email', 'fields' => json_encode([
                '1' => ['name' => 'Email Address', 'type' => 'text', 'validation' => 'required']
            ])],
            ['name' => 'Phone', 'fields' => json_encode([
                '1' => ['name' => 'Phone Number', 'type' => 'text', 'validation' => 'required']
            ])],
        ];
        $defaultValuesLevel2 = [
            ['name' => 'Passport', 'fields' => json_encode([
                '1' => ['name' => 'Passport front side', 'type' => 'file', 'validation' => 'required'],
                '2' => ['name' => 'Passport back side', 'type' => 'file', 'validation' => 'required']
            ])],
            ['name' => 'ID card', 'fields' => json_encode([
                '1' => ['name' => 'ID card front side', 'type' => 'file', 'validation' => 'required'],
                '2' => ['name' => 'ID card back side', 'type' => 'file', 'validation' => 'required']
            ])],
        ];
        foreach ($defaultValuesLevel1 as $defaultValue) {
            $kyc = Kyc::updateOrCreate(
                [
                    'kyc_level_id' => $kycLevelIdLevel1,
                    'name' => $defaultValue['name'],
                ],
                [
                    'status' => true,
                    'fields' => $defaultValue['fields'],
                    
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            Kyclevelsetting::updateOrCreate(
                [
                    'kyc_level_id' => $kycLevelIdLevel1,
                    'title' => $defaultValue['name'],
                ],
                [
                    'unique_code' => strtolower($defaultValue['name']),
                    'status' => true,
                    'kyc_id' => $kyc->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        foreach ($defaultValuesLevel2 as $defaultValueLevel2) {
            $kycLevel2 = Kyc::updateOrCreate(
                [
                    'kyc_level_id' => $kycLevelIdLevel2,
                    'name' => $defaultValueLevel2['name'],
                ],
                [
                    'status' => true,
                    'fields' => $defaultValueLevel2['fields'],
                    
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            Kyclevelsetting::updateOrCreate(
                [
                    'kyc_level_id' => $kycLevelIdLevel2,
                    'title' => $defaultValueLevel2['name'],
                ],
                [
                    'unique_code' => 'manual',
                    'status' => true,
                    'kyc_id' => $kycLevel2->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
        

        $kycLevels = Kyclevel::pluck('id')->toArray();
        Kyclevelsetting::updateOrCreate(
            [
                'kyc_level_id' => $kycLevelIdLevel2,
                'title' => 'samsub',
            ],
            [
                'unique_code' => 'samsub',
                'status' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
            
       
}
}
