<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class NowPaymentsCurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Updates the supported_currencies for NOWPayments gateway with the latest supported cryptocurrencies
     *
     * @return void
     */
    public function run()
    {
        try {
            // Begin database transaction for data integrity
            DB::beginTransaction();

            // Comprehensive list of NOWPayments supported cryptocurrencies as of November 2024
            // Source: https://nowpayments.io/supported-coins
            // Updated with all currencies from official NOWPayments documentation
            $nowPaymentsCurrencies = [
                // Popular Coins
                'BTC', 'ETH', 'XRP', 'USDTOMNI', 'LTC', 'BCH', 'DOGE', 'XNO', 'XMR', 'DASH',
                'VET', 'UNI', 'SOL', 'ADA', 'SHIB', 'DGB', 'TRX',
                
                // Stable Coins
                'TUSD', 'TUSDTRC20', 'DAI', 'USDTTRC20', 'USDTBSC', 'USDTERC20', 'USDTSOL',
                'USDP', 'BUSD', 'USDC', 'USDCMATIC', 'USDDTRC20', 'GUSD', 'USDTMATIC',
                'USDCSOL', 'PYUSD', 'EURT', 'USDE',
                
                // Fiat Currencies
                'USD', 'EUR', 'NGN', 'CAD', 'AUD', 'GBP', 'KRW', 'ILS', 'RON', 'ARS',
                'INR', 'IDR', 'MXN', 'MYR', 'TRY', 'CLP', 'PEN', 'PHP', 'THB', 'VND',
                'PLN', 'BRL',
                
                // Exchange Tokens
                'FTT', 'BNBBSC', 'BNBMAINNET', 'HT', 'OKB', 'CRO', 'MX',
                
                // Layer 1 & Layer 2 Blockchains
                'ARB', 'AVAX', 'DOT', 'ATOM', 'NEAR', 'FTM', 'EGLD', 'MATIC', 'MATICMAINNET',
                'ONE', 'HBAR', 'XTZ', 'ALGO', 'WAVES', 'NEO', 'EOS', 'ICX', 'STRAX', 'ONT',
                'ZIL', 'QTUM', 'LSK', 'XLM', 'AE', 'XYM', 'KLAY', 'APT', 'SEI', 'TON',
                'FILMAINNET', 'ARK', 'CSPR', 'PLS', 'MATICUSDCE',
                
                // DeFi Tokens
                'AAVE', 'UNI', 'LINK', 'YFI', 'OCEAN', 'KNC', 'BAL', 'CRV', 'CAKE', 'GRT',
                'COTI', 'DAO', 'CTSI', 'SXP', 'BEL', 'STPT', 'CHR', 'FRONT', 'NOW', 'OM',
                'SCRT', 'SUPER', 'POOLZ', 'KLV', 'TFUEL', 'DYDX', 'VELO', 'POOLX', 'RUNE',
                'VERSE',
                
                // Gaming & NFT Tokens
                'AXS', 'SAND', 'MANA', 'ENJ', 'CHZ', 'GALA', 'APE', 'ILV', 'ALICE', 'TLM',
                'PYR', 'GHST', 'REVV', 'TOWER', 'TKO', 'SLP', 'FLOW', 'IMX', 'C98', 'FUN',
                'SFUND', 'NTVRK', 'NFTB', 'HOTCROSS', 'GALBSC', 'TTC', 'ZBCSOL', 'GUARD',
                'MARSH', 'DGI', 'GHC', 'JETTON', 'AITECH', 'BEFI', 'VPS', 'LBP', 'CATI',
                'BAZED', 'SIDUS',
                
                // Meme Coins
                'SHIB', 'DOGE', 'FLOKI', 'FLOKIBSC', 'BABYDOGE', 'KISHU', 'KEANU', 'LEASH',
                'PEPE', 'SHIBBSC', 'PIT', 'POODL', 'HOGE', 'KIBABSC', 'KIBA', 'VOLT',
                'BUFF', 'DOGECOIN', 'RBIF', 'QUACK', 'TENSHI', 'PEIPEI', 'SCRAT', 'PONKE',
                'DINO', 'GRAPE', 'MYRO', 'BRETT', 'MEW', 'PENG', 'BANANA', 'DOGS', 'CATS',
                'SUNDOG', 'NEIRO', 'DADDY', 'WOLF', 'HMSTR', 'PEW', 'RAINCOIN',
                
                // Privacy Coins
                'XMR', 'ZEC', 'DASH', 'XNO', 'FIRO', 'ZEN', 'XVG', 'BEAM', 'PIVX', 'EPIC',
                
                // Utility & Enterprise Tokens
                'BAT', 'REP', 'GAS', 'GRS', 'WABI', 'MCO', 'KMD', 'RVN', 'FEG', 'XYO',
                'ARPA', 'AVA', 'AVABSC', 'AVAERC20', 'XDC', 'FLUF', 'BLOCKS', 'BONE', 'AVN',
                'GTERC20', 'RXCG', 'BSV', 'BTG', 'BCD', 'DCR', 'ETC', 'ETHW', 'OMG', 'XEM',
                'UST', 'VIB', 'SRK', 'NWC', 'IOTX', 'MIOTA', 'HOT', 'THETA', 'TRVL',
                'RACA', 'BRISEBSC', 'CNS', 'LUNC', '1INCH', '1INCHBSC', 'ARV', 'BOBA',
                'BTT', 'BTTBSC', 'CUDOS', 'GAFA', 'ETHBSC', 'PIKAETH', 'XCAD', 'GGTKN',
                'DGMOON', 'DIVI', 'KAS', 'NFAI', 'ETHARB', 'NOT', 'ZKSYNC', 'ZK', 'XAUT',
                'XEC', 'CFXBSC', 'ID', 'DGD', 'XZC', 'XCUR', 'CVC', 'GSPI', 'SPI', 'CULT',
                'BTFA', 'BTTC', 'TLOS', 'GETH', 'STKK', 'GARI', 'HEX', 'JST', 'USDJ', 'SUN',
                'WIN', 'SYSEVM', 'TUP', 'WBTC', 'ZRO', 'STRK', 'IPMB', 'LNQ', 'G', 'SNSY',
                'PLX', 'INJ', 'RJV', 'AVA2', 'ZENT', 'NEVER', 'CGPT', 'CSWAP', 'X', 'TET',
                'FTN', 'SOON',
                
                // Additional tokens
                'LUNA', 'LGCY', 'JASMY', 'BIFIBSC', 'ONIGI', 'BRGBSC', 'FITFIARC20',
                'BRISEMAINNET'
            ];

            // Remove duplicates and sort alphabetically
            $nowPaymentsCurrencies = array_unique($nowPaymentsCurrencies);
            sort($nowPaymentsCurrencies);

            // NOWPayments gateway credentials (default/example values)
            $nowPaymentsCredentials = [
                'api_key' => '',
                'secret_key' => '',
                'environment' => 'sandbox', // sandbox or production
                'ipn_secret' => '',
            ];

            // Check if NOWPayments gateway already exists
            $existingGateway = DB::table('gateways')
                ->where('gateway_code', 'nowpayments')
                ->first();

            if ($existingGateway) {
                // Update existing NOWPayments gateway with new supported currencies
                DB::table('gateways')
                    ->where('gateway_code', 'nowpayments')
                    ->update([
                        'supported_currencies' => json_encode($nowPaymentsCurrencies),
                        'updated_at' => Carbon::now(),
                    ]);

                echo "Updated existing NOWPayments gateway with " . count($nowPaymentsCurrencies) . " supported currencies.\n";
            } else {
                // Insert new NOWPayments gateway
                DB::table('gateways')->insert([
                    'gateway_code' => 'nowpayments',
                    'name' => 'NOWPayments',
                    'logo' => 'global/gateway/nowpayments.png',
                    'type' => 'auto',
                    'charge' => 0,
                    'charge_type' => 'fixed',
                    'minimum_deposit' => 0,
                    'maximum_deposit' => 0,
                    'rate' => 1,
                    'status' => false, // Disabled by default, admin needs to configure
                    'credentials' => json_encode($nowPaymentsCredentials),
                    'supported_currencies' => json_encode($nowPaymentsCurrencies),
                    'currency' => 'USD',
                    'currency_symbol' => '$',
                    'is_withdraw' => '0',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                echo "Created new NOWPayments gateway with " . count($nowPaymentsCurrencies) . " supported currencies.\n";
            }

            // Commit the transaction
            DB::commit();

            echo "NOWPayments currencies seeder completed successfully.\n";
            echo "Total supported currencies: " . count($nowPaymentsCurrencies) . "\n";
            echo "Gateway status: " . ($existingGateway ? 'Updated' : 'Created') . "\n";
            
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            
            echo "Error occurred while seeding NOWPayments currencies: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    /**
     * Get the list of supported currencies for NOWPayments
     * This method can be used to fetch live data from NOWPayments API if needed
     *
     * @return array
     */
    protected function fetchLiveCurrencies()
    {
        // This method can be implemented to fetch live currencies from NOWPayments API
        // For now, we use the static list above
        
        try {
            $apiUrl = 'https://api.nowpayments.io/v1/currencies';
            $response = file_get_contents($apiUrl);
            
            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['currencies']) && is_array($data['currencies'])) {
                    return array_map('strtoupper', $data['currencies']);
                }
            }
        } catch (\Exception $e) {
            // Fall back to static list if API call fails
            echo "Warning: Could not fetch live currencies from NOWPayments API. Using static list.\n";
        }
        
        return [];
    }
}
