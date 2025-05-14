<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ForexSchemasTableSeeder extends Seeder
{
    public function run()
    {
        try {

            DB::beginTransaction();
            // Disable foreign key checks
       DB::statement('SET FOREIGN_KEY_CHECKS=0;');

       // Truncate the permissions table
            DB::table('forex_schemas')->truncate();

       // Re-enable foreign key checks
       DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::table('forex_schemas')->insert([
                [
                    'trader_type' => 'mt5',
                    'icon' => 'global/images/boEepfiHH4FGDiFOKs2e.png',
                    'title' => 'Standard',
                    'desc' => 'Most popular! A great account for all types of traders2',
                    'badge' => 'Recommended',
                    'spread' => 'Standard,Low Spread,Testing Module,Double Bonus',
                    'commission' => 'No Commision',
                    'leverage' => '100,200,300,400,500,1000',
                    'first_min_deposit' => null,
                    'min_amount' => 0,
                    'account_limit' => 50,
                    'real_swap_free' => 'real\\test\\1',
                    'is_real_islamic' => 1,
                    'real_islamic' => 'real\\test\\1',
                    'demo_swap_free' => '',
                    'is_demo_islamic' => 0,
                    'demo_islamic' => '',
                    'is_withdraw' => 1,
                    'is_ib_partner' => 0,
                    'ib_group_id' => null,
                    'is_internal_transfer' => 1,
                    'is_external_transfer' => 1,
                    'is_bonus' => 0,
                    'is_cent_account' => 0,
                    'start_range' => null,
                    'end_range' => null,
                    'status' => 1,
                    'country' => '["All"]',
                    'tags' => null,
                    'priority' => 1,
                    'created_at' => '2023-11-09 08:58:11',
                    'updated_at' => '2025-05-14 07:00:41',
                ],
                [
                    'trader_type' => 'mt5',
                    'icon' => 'global/images/hmEmO8UTQQJB2qYIAyks.png',
                    'title' => 'Promo Account',
                    'desc' => 'Receive up to 30% Tradeable Bonus (Loss-able Credit) on every deposit. Invest less, trade more.',
                    'badge' => '30% Bonus',
                    'spread' => 'Standard',
                    'commission' => 'Standard',
                    'leverage' => '100,200,300',
                    'first_min_deposit' => 500,
                    'min_amount' => 0,
                    'account_limit' => 1,
                    'real_swap_free' => 'real\\test\\1',
                    'is_real_islamic' => 0,
                    'real_islamic' => 'real\\test\\1',
                    'demo_swap_free' => 'real\\test\\1',
                    'is_demo_islamic' => 0,
                    'demo_islamic' => 'real\\test\\1',
                    'is_withdraw' => 1,
                    'is_ib_partner' => 1,
                    'ib_group_id' => null,
                    'is_internal_transfer' => 1,
                    'is_external_transfer' => 1,
                    'is_bonus' => 1,
                    'is_cent_account' => 0,
                    'start_range' => null,
                    'end_range' => null,
                    'status' => 0,
                    'country' => '["All"]',
                    'tags' => null,
                    'priority' => 2,
                    'created_at' => '2023-11-09 11:38:44',
                    'updated_at' => '2024-04-12 16:03:45',
                ],
                [
                    'trader_type' => 'mt5',
                    'icon' => 'global/images/cent_account_icon.png',
                    'title' => 'Cent Account',
                    'desc' => 'Best for micro-trading and cent lot sizes.',
                    'badge' => 'Cent Special',
                    'spread' => 'Low Spread',
                    'commission' => 'No Commission',
                    'leverage' => '10,50,100',
                    'first_min_deposit' => 10,
                    'min_amount' => 1,
                    'account_limit' => 5,
                    'real_swap_free' => 'real\\test\\1',
                    'is_real_islamic' => 1,
                    'real_islamic' => 'real\\test\\1',
                    'demo_swap_free' => 'real\\test\\1',
                    'is_demo_islamic' => 1,
                    'demo_islamic' => 'real\\test\\1',
                    'is_withdraw' => 1,
                    'is_ib_partner' => 1,
                    'ib_group_id' => null,
                    'is_internal_transfer' => 1,
                    'is_external_transfer' => 1,
                    'is_bonus' => 1,
                    'is_cent_account' => 1,
                    'start_range' => null,
                    'end_range' => null,
                    'status' => 1,
                    'country' => '["All"]',
                    'tags' => '["Cent"]',
                    'priority' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ForexSchemasTableSeeder failed: ' . $e->getMessage());
        }
    }
}
