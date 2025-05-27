<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_type_categories')->truncate();
        DB::table('account_type_categories')->insert([
            [
                'title' => 'Global Account',
                'slug' => 'global_account',
                'description' => 'Visible to all users regardless of their country or risk profile tags.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Country & Tags',
                'slug' => 'country_and_tags',
                'description' => 'Visible only to users matching specific country and risk profile tag filters.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'IB Rebate Rules',
                'slug' => 'ib_rebate_rules',
                'description' => 'Visible to users based on IB Group rebate rule configuration.',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
