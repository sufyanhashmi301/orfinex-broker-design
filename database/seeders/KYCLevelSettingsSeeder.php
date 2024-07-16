<?php

namespace Database\Seeders;

use App\Models\Kyc;
use App\Models\Kyclevel;
use App\Models\Kyclevelsetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // Retrieve all KYC levels and their IDs
        $kycLevels = Kyclevel::pluck('id')->toArray();
    
        // Retrieve all KYC forms that match the KYC level IDs
        $kycForms = Kyc::whereIn('kyc_level_id', $kycLevels)->get();
    
        // Retrieve the KYC level ID for 'Level 2'
        $kycLevelId = Kyclevel::where('name', 'Level 2')->value('id');
    
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
                        'kyc_level_id' => $kycLevelId,
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
