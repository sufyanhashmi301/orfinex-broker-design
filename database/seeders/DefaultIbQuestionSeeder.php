<?php

namespace Database\Seeders;

use App\Models\IbQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultIbQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if default IB form already exists
        $existingForm = IbQuestion::where('name', 'Broker Application Form')->first();
        
        if ($existingForm) {
            $this->command->info('Default IB form already exists. Skipping...');
            return;
        }

        // Define default fields for broker application
        $fields = [
            [
                'name' => 'Full Name',
                'type' => 'text',
                'validation' => 'required',
                'options' => []
            ],
            [
                'name' => 'Phone Number',
                'type' => 'text',
                'validation' => 'required',
                'options' => []
            ],
            [
                'name' => 'Email Address',
                'type' => 'text',
                'validation' => 'required',
                'options' => []
            ],
            [
                'name' => 'Company Name',
                'type' => 'text',
                'validation' => 'nullable',
                'options' => []
            ],
            [
                'name' => 'Country',
                'type' => 'text',
                'validation' => 'required',
                'options' => []
            ],
            [
                'name' => 'Address',
                'type' => 'text',
                'validation' => 'nullable',
                'options' => []
            ],
            [
                'name' => 'Years of Experience',
                'type' => 'dropdown',
                'validation' => 'required',
                'options' => [
                    'Less than 1 year',
                    '1-2 years',
                    '3-5 years',
                    '6-10 years',
                    'More than 10 years'
                ]
            ],
            [
                'name' => 'Expected Monthly Trading Volume',
                'type' => 'dropdown',
                'validation' => 'required',
                'options' => [
                    'Less than $100,000',
                    '$100,000 - $500,000',
                    '$500,000 - $1,000,000',
                    '$1,000,000 - $5,000,000',
                    'More than $5,000,000'
                ]
            ],
            [
                'name' => 'Trading Platforms',
                'type' => 'checkbox',
                'validation' => 'nullable',
                'options' => [
                    'MT4',
                    'MT5',
                    'cTrader',
                    'WebTrader',
                    'Mobile App'
                ]
            ],
            [
                'name' => 'Marketing Channels',
                'type' => 'checkbox',
                'validation' => 'nullable',
                'options' => [
                    'Website',
                    'Social Media',
                    'Email Marketing',
                    'Content Marketing',
                    'Paid Advertising',
                    'Referral Program'
                ]
            ],
            [
                'name' => 'How did you hear about us?',
                'type' => 'dropdown',
                'validation' => 'nullable',
                'options' => [
                    'Search Engine',
                    'Social Media',
                    'Friend/Colleague',
                    'Advertisement',
                    'Other'
                ]
            ],
            [
                'name' => 'Additional Information',
                'type' => 'text',
                'validation' => 'nullable',
                'options' => []
            ]
        ];

        // Create the IB form
        IbQuestion::create([
            'name' => 'Broker Application Form',
            'fields' => json_encode($fields),
            'status' => 1, // Active
        ]);

        $this->command->info('Default IB Broker Application Form created successfully!');
    }
}

