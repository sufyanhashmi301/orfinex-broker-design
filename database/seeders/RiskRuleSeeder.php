<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RiskRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('risk_rules')->truncate();

        // Quick Trades Seeder
        $data= [ 
            // Quick Trades
            [
                "title" => 'Quick Trades Analysis',
                "slug" => 'quick_trades',
                "api_endpoint" => 'reports/fastdeals',
                "data" => '[]',
                "criteria" => '{"timeInSeconds": {"value": "120", "parameter_name": "Trade(s) Age (Seconds)"}}',
                "data_from" => Carbon::today(),
                "data_to" => Carbon::today(),
                "created_at" => Carbon::now()->subHours(2),
                "updated_at" => Carbon::now()->subHours(2),
            ],

            // Scalper Detection
            [
                "title" => 'Scalper Detection',
                "slug" => 'scalper_detection',
                "api_endpoint" => 'reports/scalper',
                "data" => '[]',
                "criteria" => '{"timeInSeconds": {"value": "120", "parameter_name": "Trade(s) Age (Seconds)"}}',
                "data_from" => Carbon::today(),
                "data_to" => Carbon::today(),
                "created_at" => Carbon::now()->subHours(2),
                "updated_at" => Carbon::now()->subHours(2),
            ],

            // Most Trades
            [
                "title" => 'Most Trades Analysis',
                "slug" => 'most_trades',
                "api_endpoint" => 'reports/total/trades',
                "data" => '[]',
                "criteria" => '[]',
                "data_from" => Carbon::today(),
                "data_to" => Carbon::today(),
                "created_at" => Carbon::now()->subHours(2),
                "updated_at" => Carbon::now()->subHours(2),
            ],

            // Trade Age
            [
                "title" => 'Trade Age Analysis',
                "slug" => 'trade_age',
                "api_endpoint" => 'Position/list/positionOldDays',
                "data" => '[]',
                "criteria" => '{"Days": {"value": "10", "parameter_name": "Trade(s) Age (Days)"}}',
                "data_from" => Carbon::today(),
                "data_to" => Carbon::today(),
                "created_at" => Carbon::now()->subHours(2),
                "updated_at" => Carbon::now()->subHours(2),
            ],

            // IP Address
            [
                "title" => 'IP Address Analysis',
                "slug" => 'ip_address',
                "api_endpoint" => 'reports/ip/location',
                "data" => '[]',
                "criteria" => '[]',
                "data_from" => Carbon::today(),
                "data_to" => Carbon::today(),
                "created_at" => Carbon::now()->subHours(2),
                "updated_at" => Carbon::now()->subHours(2),
            ],
        ];
        DB::table('risk_rules')->insert($data);
    }
}
