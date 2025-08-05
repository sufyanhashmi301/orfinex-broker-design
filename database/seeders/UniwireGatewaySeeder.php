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
            'supported_currencies' => json_encode([
                "BTC", "BTC_P2SH", "BTC_BECH32", "BTC_LIGHTNING", "LTC", "XRP", "DOGE", "TON", "SOL", "ETH",
                "ETH-BASE", "ETH-ARBITRUM", "POL", "BNB", "TRX", "CELO", "ETH_USDT", "ETH_USDC", "ETH_TUSD",
                "ETH_PAX", "ETH_GUSD", "ETH_SAND", "ETH_SHIB", "ETH_BUSD", "ETH_SHFL", "ETH_cbBTC", "ETH_USD1",
                "USDT-POLYGON", "USDC-POLYGON", "USDCE-POLYGON", "USDC-BASE", "cbBTC-BASE", "USDT-ARBITRUM",
                "USDC-ARBITRUM", "USDCE-ARBITRUM", "CELO-CELO", "CUSD-CELO", "USDT-CELO", "USDC-CELO",
                "USDT-TRX", "USDC-TRX", "USDT-SOL", "USDC-SOL", "WSOL-SOL", "BONK-SOL", "TRUMP-SOL", "JAMBO-SOL",
                "USDT-BSC", "USDC-BSC", "ETH-BSC", "DAI-BSC", "SHIB-BSC", "BUSD", "WBNB", "USD1-BSC", "L-BTC",
                "L-USDT", "USDT-TON", "NOT-TON"
            ]),
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
