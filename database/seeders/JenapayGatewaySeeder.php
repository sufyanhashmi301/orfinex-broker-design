<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenapayGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jenapayCredentials = [
            'merchant_key' => '5b637114-889b-11f0-8307-5ef95a1588b91',
            'merchant_pass' => 'db0a76026e927c1f43eeb5e24043f7a5',
            'api_url' => 'https://checkout.jenapay.com',
        ];

        $jenapayCurrencies = [
            'USD',
            'EUR', 
            'GBP',
            'CAD',
            'AUD'
        ];

        $jenapay = DB::table('gateways')->where('gateway_code','jenapay')->first();
        
        if(!$jenapay) {
            DB::table('gateways')->insert([
                'gateway_code' => 'jenapay',
                'name' => 'JenaPay',
                'logo' => 'https://jenapay.com/wp-content/uploads/2023/09/Jena-Pay-Logo-1.png',
                'status' => false, // Disabled by default
                'credentials' => json_encode($jenapayCredentials),
                'supported_currencies' => json_encode($jenapayCurrencies),
            ]);
        } else {
            DB::table('gateways')->where('gateway_code','jenapay')->update([
                'credentials' => json_encode($jenapayCredentials),
                'supported_currencies' => json_encode($jenapayCurrencies),
            ]);
        }
    }
}
