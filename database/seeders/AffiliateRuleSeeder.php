<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AffiliateRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('affiliate_rules')->truncate();
        DB::table('affiliate_rule_configurations')->truncate();
        DB::table('affiliate_rule_levels')->truncate();

        // Affiliate Rules Seeding
        $data= [ 
            [
                "name" => 'Affiliate Rule',
                "for_account_type_ids" => '["all"]',
                "count_mode" => 'active_account',
                "balance_retention_period" => 0,
                "description" => '',
                "has_levels" => 1,
                "is_active" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]
        ];
        DB::table('affiliate_rules')->insert($data);


        // Affiliate Rules Configuration
        $data2 = [
            [
                "affiliate_rule_id" => 1,
                "count_start" => '1',
                "count_end" => '9999',
                "commission_percentage" => 10,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]
        ];
        DB::table('affiliate_rule_configurations')->insert($data2);


        // Affiliate Rules Level
        $data3 = [
            [
                "affiliate_rule_id" => 1,
                "level" => '1',
                "commission_percentage" => 100,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ]
        ];
        DB::table('affiliate_rule_levels')->insert($data3);
    }
}
