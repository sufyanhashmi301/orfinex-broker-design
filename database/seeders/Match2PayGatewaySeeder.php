<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Match2PayGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $match2payCredentials = [
            'secret_key' => '',
            'api_token' => '',
            'base_url' => '',
        ];
        $match2payCurrencies = [
            "BTC", "ETH", "UST", "UCC", "TRX", "USX", "UCX", "BNB", "USB",
            "MAT", "USP", "UCP", "XRP", "DOG", "LTC", "SOL", "USS", "UCS",
            "TON", "UTT"
        ];

        $match2pay = DB::table('gateways')->where('gateway_code','match2pay')->first();
       if(!$match2pay) {
           DB::table('gateways')->insert([
                   'gateway_code' => 'match2pay',
                   'name' => 'Match2Pay',
                   'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/match2pay.svg',
                   'status' => true,
                   'credentials' => json_encode($match2payCredentials),
                   'supported_currencies' => json_encode($match2payCurrencies),

           ]);
       }else{
           DB::table('gateways')->where('gateway_code','match2pay')->update([

                   'credentials' => json_encode($match2payCredentials),
                   'supported_currencies' => json_encode($match2payCurrencies),


           ]);
       }
    }
}
