<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class DepositMethodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            [
                'type'              => 'manual',
                'name'              => 'Deposit Voucher',
                'gateway_code'      => 'voucher',
                'logo'              => 'global/images/voucher.png',
                'charge'            => 0,
                'charge_type'       => 'fixed',
                'minimum_deposit'   => 1,
                'maximum_deposit'   => 10000,
                'processing_time'   => 0,
                'rate'              => 1,
                'currency'          => 'USD',
                'currency_symbol'   => '$',
                'status'            => 1,
                'field_options'     => json_encode([
                    "1" => [
                        "name"       => "voucher code",
                        "type"       => "text",
                        "validation" => "required"
                    ]
                ]),
                'payment_details'   => 'Redeem Voucher using your code.',
                'country'           => json_encode(["All"]),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]
        ];

        foreach ($methods as $method) {
            $exists = DB::table('deposit_methods')
                ->where('gateway_code', $method['gateway_code'])
                ->exists();

            if (!$exists) {
                DB::table('deposit_methods')->insert(array_merge($method, [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]));

                $this->command->info("Inserted deposit method: {$method['name']}");
            } else {
                $this->command->warn("Skipped (already exists): {$method['name']}");
            }
        }
    }
}
