<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Gateway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateGatewayLogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $updates = [
            ['gateway_code' => 'paypal', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/paypal.svg'],
            ['gateway_code' => 'stripe', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/stripe.svg'],
            ['gateway_code' => 'mollie', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/mollie.svg'],
            ['gateway_code' => 'perfectmoney', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/perfectmoney.svg'],
            ['gateway_code' => 'coinbase', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/coinbase.svg'],
            ['gateway_code' => 'paystack', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/paystack.svg'],
            ['gateway_code' => 'voguepay', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/voguepay.svg'],
            ['gateway_code' => 'flutterwave', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/flutterwave.svg'],
            ['gateway_code' => 'coingate', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/coingate.svg'],
            ['gateway_code' => 'monnify', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/monnify.svg'],
            ['gateway_code' => 'securionpay', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/securionpay.svg'],
            ['gateway_code' => 'coinpayments', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/coinpayments.svg'],
            ['gateway_code' => 'nowpayments', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/nowpayments.svg'],
            ['gateway_code' => 'coinremitter', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/coinremitter.svg'],
            ['gateway_code' => 'cryptomus', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/cryptomus.svg'],
            ['gateway_code' => 'paymongo', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/paymongo.svg'],
            ['gateway_code' => 'btcpayserver', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/btcpay.svg'],
            ['gateway_code' => 'binance', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/binancepay.svg'],
            ['gateway_code' => 'cashmaal', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/cashmall.svg'],
            ['gateway_code' => 'blockio', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/blockio.svg'],
            ['gateway_code' => 'blockchain', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/blockchain.svg'],
            ['gateway_code' => 'instamojo', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/instamojo.svg'],
            ['gateway_code' => 'paytm', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/paytm.svg'],
            ['gateway_code' => 'razorpay', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/razorpay.svg'],
            ['gateway_code' => 'twocheckout', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/2checkout.svg'],
            ['gateway_code' => 'bridgerpay', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/bridgerpay.svg'],
            ['gateway_code' => 'match2pay', 'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/match2pay.svg'],
        ];

        foreach ($updates as $update) {
            Gateway::where('gateway_code', $update['gateway_code'])
                ->update(['logo' => $update['logo']]);
        }
    }
}
