<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CoinPaymentsCurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Updates the supported_currencies for CoinPayments gateway with official supported cryptocurrencies
     * Based on official CoinPayments v2 supported coins list: https://www.coinpayments.net/v2-supported-coins
     * Uses exact currency codes as listed in their documentation
     *
     * @return void
     */
    public function run()
    {
        try {
            // Begin database transaction for data integrity
            DB::beginTransaction();

            // Official CoinPayments v2 supported cryptocurrencies
            // Based on https://www.coinpayments.net/v2-supported-coins
            // Using exact currency codes as listed in their documentation
            $coinPaymentsCurrencies = [
                // Native Cryptocurrencies (Main Networks)
                'BTC',      // Bitcoin
                'ETH',      // Ethereum
                'XRP',      // Ripple
                'BCH',      // Bitcoin Cash
                'LTC',      // Litecoin
                'LTCT',     // Litecoin Test
                'TRX',      // Tron
                'DASH',     // Dash
                'POL',      // Polygon
                'TON',      // Toncoin
                'DOGE',     // Dogecoin
                'ETC',      // Ethereum Classic
                'SOL',      // Solana
                'QTUM',     // Qtum
                'XVG',      // Verge
                'FIRO',     // Firocoin
                
                // ERC-20 Tokens (Ethereum Network)
                'TUSD.ERC20',    // True USD
                'CAKE.ERC20',    // PancakeSwap
                'WLD.ERC20',     // Worldcoin
                'TON.ERC20',     // Toncoin
                'DAI.ERC20',     // DAI
                'SHIB.ERC20',    // Shiba Inu
                'EVERY.ERC20',   // Everyworld
                'USDC.ERC20',    // USD Coin
                'ARB.ERC20',     // Arbitrum
                'WETH.ERC20',    // Wrapped Ether
                'FDUSD.ERC20',   // First Digital USD
                'GALA.ERC20',    // Gala
                'USDT.ERC20',    // Tether USD
                'INJ.ERC20',     // Injective
                
                // BEP-20 Tokens (BSC Network)
                'BNB.BSC',       // Binance Coin
                'CAKE.BEP20',    // PancakeSwap
                'DAI.BEP20',     // DAI
                'AVAX.BEP20',    // Avalanche
                'ETH.BEP20',     // Ethereum Token
                'SHIB.BEP20',    // Shiba Inu
                'TUSD.BEP20',    // True USD
                'WETH.BEP20',    // Wrapped Ether
                'USDT.BEP20',    // Tether USD
                'BTC.BEP20',     // Bitcoin/BTCB
                'TON.BEP20',     // Toncoin
                'USDC.BEP20',    // USD Coin
                'INJ.BEP20',     // Injective
                'AI.BEP20',      // Sleepless AI
                'FDUSD.BEP20',   // First Digital USD
                
                // TRC-20 Tokens (Tron Network)
                'USDT.TRC20',    // Tether USD
                'TON.TRC20',     // Toncoin
                'TUSD.TRC20',    // True USD
                
                // BASE Network Tokens
                'ETH.BASE',      // Ethereum BASE
                'WETH.BASE',     // Wrapped Ether
                'EVERY.BASE',    // Everyworld
                'USDC.BASE',     // USD Coin
                
                // Polygon Network Tokens
                'USDC.POL',      // USD Coin
                'SHIB.POL',      // SHIBA INU
                'WETH.POL',      // Wrapped Ether
                'USDT.POL',      // Tether USD
                
                // Solana Network Tokens
                'USDC.SOL',      // USD Coin
                'USDT.SOL',      // Tether USD
            ];

            // Remove duplicates and sort alphabetically
            $coinPaymentsCurrencies = array_unique($coinPaymentsCurrencies);
            sort($coinPaymentsCurrencies);

            // CoinPayments gateway credentials (default/example values)
            $coinPaymentsCredentials = [
                'merchant_id' => '',
                'public_key' => '',
                'private_key' => '',
                'secret' => '',
                'environment' => 'sandbox', // sandbox or live
            ];

            // Check if CoinPayments gateway already exists
            $existingGateway = DB::table('gateways')
                ->where('gateway_code', 'coinpayments')
                ->first();

            if ($existingGateway) {
                // Update existing CoinPayments gateway with new supported currencies
                DB::table('gateways')
                    ->where('gateway_code', 'coinpayments')
                    ->update([
                        'supported_currencies' => json_encode($coinPaymentsCurrencies),
                        'updated_at' => Carbon::now(),
                    ]);

                echo "Updated existing CoinPayments gateway with " . count($coinPaymentsCurrencies) . " officially supported currencies.\n";
            } else {
                // Insert new CoinPayments gateway
                DB::table('gateways')->insert([
                    'gateway_code' => 'coinpayments',
                    'name' => 'CoinPayments',
                    'logo' => 'global/gateway/coinpayments.png',
                    'type' => 'auto',
                    'charge' => 0,
                    'charge_type' => 'fixed',
                    'minimum_deposit' => 0,
                    'maximum_deposit' => 0,
                    'rate' => 1,
                    'status' => false, // Disabled by default, admin needs to configure
                    'credentials' => json_encode($coinPaymentsCredentials),
                    'supported_currencies' => json_encode($coinPaymentsCurrencies),
                    'currency' => 'USD',
                    'currency_symbol' => '$',
                    'is_withdraw' => '0',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                echo "Created new CoinPayments gateway with " . count($coinPaymentsCurrencies) . " officially supported currencies.\n";
            }

            // Commit the transaction
            DB::commit();

            echo "CoinPayments currencies seeder completed successfully.\n";
            echo "Total officially supported currencies: " . count($coinPaymentsCurrencies) . "\n";
            echo "Source: https://www.coinpayments.net/v2-supported-coins\n";
            echo "Gateway status: " . ($existingGateway ? 'Updated' : 'Created') . "\n";
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            echo "Error occurred while seeding CoinPayments currencies: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Get the list of supported currencies for CoinPayments
     * This method can be used to fetch live data from CoinPayments API if needed
     *
     * @return array
     */
    protected function fetchLiveCurrencies()
    {
        // This method can be implemented to fetch live currencies from CoinPayments API
        // For now, we use the static list above
        
        try {
            // CoinPayments API endpoint for supported coins
            $apiUrl = 'https://www.coinpayments.net/api.php';
            $params = [
                'cmd' => 'rates',
                'format' => 'json',
                'version' => '1',
                'key' => 'your_api_key_here', // Replace with actual API key
                'hmac' => '', // HMAC signature required
            ];
            
            // Note: CoinPayments requires HMAC authentication
            // Implementation would need proper API credentials and HMAC signing
            
        } catch (\Exception $e) {
            // Fall back to static list if API call fails
            echo "Warning: Could not fetch live currencies from CoinPayments API. Using static list.\n";
        }
        
        return [];
    }
}