<?php

namespace Database\Seeders;

use App\Enums\GatewayType;
use App\Models\DepositMethod;
use App\Models\Gateway;
use Illuminate\Database\Seeder;

class DepositMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing data
        DepositMethod::truncate();

        // Get available gateways
        $gateways = Gateway::where('status', true)->get();

        // Create automatic deposit methods - only BridgerPay
        $this->createAutomaticMethods($gateways);

        // Create manual deposit methods - only Bank Transfer
        $this->createManualMethods();
    }

    protected function createAutomaticMethods($gateways)
    {
        foreach ($gateways as $gateway) {
            // Only create for BridgerPay
            if ($gateway->gateway_code !== 'bridgerpay') {
                continue;
            }

            // Get the first supported currency for this gateway
            $supportedCurrencies = json_decode($gateway->supported_currencies, true);
            $currency = $supportedCurrencies[0] ?? 'USD';

            DepositMethod::create([
                'logo' => null, // Will use gateway logo
                'name' => $gateway->name . ' ' . $currency,
                'type' => GatewayType::Automatic->value,
                'gateway_id' => $gateway->id,
                'gateway_code' => $gateway->gateway_code . '-' . strtolower($currency),
                'currency' => $currency,
                'currency_symbol' => $this->getCurrencySymbol($currency),
                'charge' => 2.5,
                'charge_type' => 'percentage',
                'rate' => 1.0,
                'minimum_deposit' => 10,
                'maximum_deposit' => 5000,
                'processing_time' => 'Instant',
                'country' => ['All'],
                'status' => true,
                'field_options' => null,
                'payment_details' => null,
            ]);
        }
    }

    protected function createManualMethods()
    {
        $manualMethods = [
            [
                'name' => 'Bank Transfer',
                'method_code' => 'bank_transfer',
                'currency' => 'USD',
                'logo' => 'global/materials/bank-transfer.png',
                'processing_time' => '1-3 Business Days',
                'minimum_deposit' => 50,
                'maximum_deposit' => 10000,
            ],
            /*
            // Keeping these commented for future reference
            [
                'name' => 'Cash Deposit',
                'method_code' => 'cash_deposit',
                'currency' => 'USD',
                'logo' => 'global/materials/cash-deposit.png',
                'processing_time' => 'Instant',
                'minimum_deposit' => 20,
                'maximum_deposit' => 5000,
            ],
            [
                'name' => 'Voucher',
                'method_code' => 'voucher',
                'currency' => 'USD',
                'logo' => 'global/materials/voucher.png',
                'processing_time' => 'Instant',
                'minimum_deposit' => 5,
                'maximum_deposit' => 1000,
            ],
            */
        ];

        foreach ($manualMethods as $method) {
            DepositMethod::create([
                'logo' => $method['logo'],
                'name' => $method['name'],
                'type' => GatewayType::Manual->value,
                'gateway_id' => null,
                'gateway_code' => $method['method_code'],
                'currency' => $method['currency'],
                'currency_symbol' => $this->getCurrencySymbol($method['currency']),
                'charge' => 1.0,
                'charge_type' => 'fixed',
                'rate' => 1.0,
                'minimum_deposit' => $method['minimum_deposit'],
                'maximum_deposit' => $method['maximum_deposit'],
                'processing_time' => $method['processing_time'],
                'country' => ['All'],
                'status' => true,
                'field_options' => $this->getFieldOptions($method['method_code']),
                'payment_details' => $this->getPaymentDetails($method['method_code']),
            ]);
        }
    }

    protected function getCurrencySymbol($currency)
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'CAD' => 'CA$',
            'AUD' => 'A$',
        ];

        return $symbols[$currency] ?? '$';
    }

    protected function getFieldOptions($methodCode)
    {
        $options = [
            'bank_transfer' => [
                [
                    'name' => 'Bank Name',
                    'type' => 'text',
                    'validation' => 'required'
                ],
                [
                    'name' => 'Account Number',
                    'type' => 'text',
                    'validation' => 'required'
                ],
                [
                    'name' => 'Account Name',
                    'type' => 'text',
                    'validation' => 'required'
                ],
                [
                    'name' => 'Deposit Slip',
                    'type' => 'file',
                    'validation' => 'required'
                ]
            ],
            /*
            // Keeping these commented for future reference
            'cash_deposit' => [
                [
                    'name' => 'Deposit Location',
                    'type' => 'text',
                    'validation' => 'required'
                ],
                [
                    'name' => 'Transaction Receipt',
                    'type' => 'file',
                    'validation' => 'required'
                ]
            ],
            'voucher' => [
                [
                    'name' => 'Voucher Code',
                    'type' => 'text',
                    'validation' => 'required'
                ]
            ]
            */
        ];

        return json_encode($options[$methodCode] ?? []);
    }

    protected function getPaymentDetails($methodCode)
    {
        $details = [
            'bank_transfer' => '<p>Please make your payment directly into our bank account. Please use your Transaction ID as the payment reference. Your deposit won\'t be processed until the funds have cleared in our account.</p>',
            /*
            // Keeping these commented for future reference
            'cash_deposit' => '<p>Visit any of our authorized cash deposit locations to make your payment. Please bring your Transaction ID with you.</p>',
            'voucher' => '<p>Enter your voucher code to redeem the deposit amount. Vouchers can only be used once.</p>'
            */
        ];

        return $details[$methodCode] ?? '<p>Please follow the instructions for this payment method.</p>';
    }
}