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

        // Retrieve all KYC levels and their IDs
        $kycLevels = Kyclevel::pluck('id')->toArray();

        // Retrieve all KYC forms that match the KYC level IDs
        $kycForms = Kyc::whereIn('kyc_level_id', $kycLevels)->get();

        foreach ($kycForms as $form) {
            // Use updateOrCreate to add or update the KYC level setting for the current form
            Kyclevelsetting::updateOrCreate(
                [
                    'kyc_level_id' => $form->kyc_level_id,
                    'title' => $form->name,
                ],
                [
                    'unique_code' => 'manual',
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // If the KYC level ID is 2 and the 'samsub' record does not already exist, add it
            if ($form->kyc_level_id == 2) {
                Kyclevelsetting::updateOrCreate(
                    [
                        'kyc_level_id' => $kycLevelIdLevel2,
                        'title' => 'samsub',
                    ],
                    [
                        'unique_code' => 'samsub',
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
