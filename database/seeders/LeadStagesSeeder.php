<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeadStagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lead_stages')->truncate();
        DB::table('lead_stages')->insert([
            [
                'name' => 'Generated',
                'label_color' => '#F1C40F'
            ],
            [
                'name' => 'Initial Contact',
                'label_color' => '#3498DB'
            ],
            [
                'name' => 'Schedule Appointment',
                'label_color' => '#2ECC71'
            ],
            [
                'name' => 'Proposal Sent',
                'label_color' => '#E74C3C'
            ],
            [
                'name' => 'Final',
                'label_color' => '#9B59B6'
            ],
            [
                'name' => 'Win',
                'label_color' => '#2ECC71'
            ],
            [
                'name' => 'Lose',
                'label_color' => '#E74C3C'
            ],
        ]);
    }
}
