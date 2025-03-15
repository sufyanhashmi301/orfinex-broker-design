<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadPipelineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lead_pipelines')->truncate();
        DB::table('pipeline_stages')->truncate();

        // Create the default pipeline
        $pipelineId = DB::table('lead_pipelines')->insertGetId([
            'name' => 'Sales Pipeline',
            'label_color' => '#009EFF',
            'added_by' => 1,
            'slug' => Str::slug('Sales Pipeline', '-'),
            'default' => true,
        ]);

        // Insert pipeline stages for the created pipeline
        DB::table('pipeline_stages')->insert([
            [
                'name' => 'Generated',
                'slug' => 'generated',
                'lead_pipeline_id' => $pipelineId,
                'priority' => 1,
                'default' => 1,
                'label_color' => '#FFE700',
            ],
            [
                'name' => 'On going',
                'slug' => 'on-going',
                'lead_pipeline_id' => $pipelineId,
                'priority' => 2,
                'default' => 0,
                'label_color' => '#009EFF',
            ],
            [
                'name' => 'Win',
                'slug' => 'win',
                'lead_pipeline_id' => $pipelineId,
                'priority' => 3,
                'default' => 0,
                'label_color' => '#1FAE07',
            ],
            [
                'name' => 'Lost',
                'slug' => 'lost',
                'lead_pipeline_id' => $pipelineId,
                'priority' => 4,
                'default' => 0,
                'label_color' => '#DB1313',
            ],
        ]);
    }
}
