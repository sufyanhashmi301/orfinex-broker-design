<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddonSeeder extends Seeder
{
    public static $TOTAL_ADDONS = 2; // number of fields in the $data array

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addons')->truncate();

        $data= [ 
            // Lifetime Payout
            [
                "name" => 'Lifetime Payout',
                "slug" => 'lifetime_payout',
                "description" => 'Enjoy lifetime payouts.',
                "amount_type" => "percentage",
                "amount" => 30.00,
                "status" => 1,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],

            // Swap Free Account
            [
                "name" => 'Swap Free Account',
                "slug" => 'swap_free_account',
                "description" => 'Easy withdrawals via Swap Free accounts.',
                "amount_type" => "fixed",
                "amount" => 15.00,
                "status" => 0,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ],
        ];

        DB::table('addons')->insert($data);
    }
}
