<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UniwireGatewaySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gateways')->insert([
            'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/uniwire.png',
            'name' => 'Uniwire',
            'gateway_code' => 'uniwire',
            'supported_currencies' => json_encode(["BTC","ETH_USDT","BNB","USDT-TRX","USDT-BSC","USDC-BSC","ETH_BUSD","USDT-POLYGON"]),
            'credentials' => json_encode([
                'API_KEY' => '5048df86d0cf44dfb9a8eed1c9b4ed7a-11',
                'API_SECRET' => 'RG9wKWGC25dqvzO0u8sSqx6HKpaVW6uXhIdSh1D7GmaX-11',
                'PROFILE_ID' => '26199977-25ac-4535-b1bc-a46d000a50f6',
                'ENVIRONMENT' => 'mainnet'
            ]),
            'is_withdraw' => '1',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
