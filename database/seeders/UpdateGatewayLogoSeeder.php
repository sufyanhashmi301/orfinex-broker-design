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
            [
                'gateway_code' => 'paypal',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/paypal.webp'
            ],
            [
                'gateway_code' => 'stripe',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/stripe.webp'
            ],
            [
                'gateway_code' => 'mollie',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/mollie.webp'
            ],
            [
                'gateway_code' => 'perfectmoney',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/perfectmoney.webp'
            ],
            [
                'gateway_code' => 'coinbase',
                'logo' => 'Coinbase: https://cdn.brokeret.com/crm-assets/admin/pg/coinbase.webp'
            ],
            [
                'gateway_code' => 'paystack',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/paystack.webp'
            ],
            [
                'gateway_code' => 'voguepay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/voguepay.webp'
            ],
            [
                'gateway_code' => 'flutterwave',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/flutterwave.webp'
            ],
            [
                'gateway_code' => 'coingate',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/coingate.webp'
            ],
            [
                'gateway_code' => 'monnify',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/monnify.webp'
            ],
            [
                'gateway_code' => 'securionpay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/securionpay.webp'
            ],
            [
                'gateway_code' => 'coinpayments',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/coinpayments.webp'
            ],
            [
                'gateway_code' => 'nowpayments',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/nowpayments.webp'
            ],
            [
                'gateway_code' => 'coinremitter',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/coinremitter.webp'
            ],
            [
                'gateway_code' => 'cryptomus',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/cryptomus.webp'
            ],
            [
                'gateway_code' => 'paymongo',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/paymongo.webp'
            ],
            [
                'gateway_code' => 'btcpayserver',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/btcpay.webp'
            ],
            [
                'gateway_code' => 'binance',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/binancepay.webp'
            ],
            [
                'gateway_code' => 'cashmaal',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/cashmall.webp'
            ],
            [
                'gateway_code' => 'blockio',
                'logo' => 'https://cdn.brokeret.com/crm-assets/integration-logo/svg/blockio.svg'
            ],
            [
                'gateway_code' => 'blockchain',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/blockchain.webp'
            ],
            [
                'gateway_code' => 'instamojo',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/instamojo.webp'
            ],
            [
                'gateway_code' => 'paytm',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/paytm.webp'
            ],
            [
                'gateway_code' => 'razorpay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/razorpay.webp'
            ],
            [
                'gateway_code' => 'twocheckout',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/2checkout.webp'
            ],
            [
                'gateway_code' => 'bridgerpay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/bridgerpay.webp'
            ],
            [
                'gateway_code' => 'match2pay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/match2pay.webp'
            ],
        ];

        foreach ($updates as $update) {
            Gateway::where('gateway_code', $update['gateway_code'])
                ->update(['logo' => $update['logo']]);
        }
    }
}
