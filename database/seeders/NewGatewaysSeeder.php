<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewGatewaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gateways = [
            [
                'gateway_code' => 'alipay',
                'name' => 'Alipay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/alipay.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR', 'AUD']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'b2binPay',
                'name' => 'B2BinPay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/b2binpay.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'cheezePay',
                'name' => 'CheezePay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/cheezeepay.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'dpopay',
                'name' => 'Dpopay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/dpopay.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR', 'AUD']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'jeton',
                'name' => 'Jeton',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/jeton.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR', 'AUD', 'BRL', 'CAD']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'peachpayments',
                'name' => 'Peach Payments',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/peachpayments.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'praxis',
                'name' => 'Praxis',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/praxis.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'tazapay',
                'name' => 'Tazapay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/tazapay.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR', 'AUD', 'BRL', 'CAD', 'JPY']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'unlimit',
                'name' => 'Unlimit',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/unlimit.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'wirebit',
                'name' => 'Wirebit',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/wirebit.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR', 'AUD', 'BRL']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'zaaspay',
                'name' => 'ZaasPay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/zaaspay.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR', 'AUD']),
                'is_withdraw' => 0,
            ],
            [
                'gateway_code' => 'zoodpay',
                'name' => 'ZoodPay',
                'logo' => 'https://cdn.brokeret.com/crm-assets/admin/pg/zoodpay.webp',
                'status' => false,
                'credentials' => '',
                'supported_currencies' => json_encode(['USD', 'EUR']),
                'is_withdraw' => 0,
            ],
        ];

        foreach ($gateways as $gateway) {
            DB::table('gateways')->updateOrInsert(
                ['gateway_code' => $gateway['gateway_code']],
                $gateway
            );
        }
    }
}
