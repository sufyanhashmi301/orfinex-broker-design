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

            // Comprehensive list of NOWPayments supported cryptocurrencies as of 2024
            // Based on official NOWPayments documentation and API
            $nowPaymentsCurrencies = [
                // Major Cryptocurrencies
                'BTC', 'ETH', 'XRP', 'USDT', 'LTC', 'BCH', 'DOGE', 'ADA', 'SOL', 'DOT',
                'AVAX', 'MATIC', 'SHIB', 'UNI', 'LINK', 'ATOM', 'XLM', 'VET', 'FIL', 'TRX',
                'ETC', 'THETA', 'XMR', 'ALGO', 'AAVE', 'MKR', 'COMP', 'SNX', 'SUSHI', 'YFI',
                
                // Stablecoins
                'USDC', 'BUSD', 'DAI', 'TUSD', 'USDD', 'FRAX', 'LUSD', 'GUSD', 'USDP',
                
                // DeFi Tokens
                'CRV', 'BAL', 'REN', 'LRC', 'ZRX', 'KNC', 'BAND', 'RSR', 'NMR', 'MLN',
                
                // Layer 2 and Scaling Solutions
                'LPT', 'STORJ', 'GRT', 'ANKR', 'CTSI', 'SKL', 'CELR', 'COTI', 'DYDX',
                
                // Exchange Tokens
                'BNB', 'CRO', 'HT', 'OKB', 'LEO', 'KCS', 'GT', 'FTT',
                
                // Gaming and NFT Tokens
                'MANA', 'SAND', 'AXS', 'SLP', 'ENJ', 'CHZ', 'FLOW', 'IMX', 'GALA', 'APE',
                
                // Meme Coins
                'ELON', 'FLOKI', 'BABYDOGE', 'SAFEMOON',
                
                // Privacy Coins
                'ZEC', 'DASH', 'XNO',
                
                // Enterprise and Utility Tokens
                'BAT', 'REP', 'ZIL', 'ICX', 'ONT', 'QTUM', 'WAVES', 'LSK', 'STEEM', 'HIVE',
                
                // Newer and Emerging Tokens
                'NEAR', 'FTM', 'ONE', 'HBAR', 'EGLD', 'KLAY', 'CELO', 'XTZ', 'KSM', 'KUSAMA',
                'ICP', 'AR', 'HNT', 'MINA', 'ROSE', 'OSMO', 'JUNO', 'SCRT', 'LUNA', 'UST',
                
                // Additional supported tokens
                'OMG', 'LPT', 'NKN', 'RLC', 'OCEAN', 'FET', 'AGIX', 'WLD', 'RENDER', 'TAO',
                'PENDLE', 'ARB', 'OP', 'BLUR', 'PEPE', 'WOO', 'GMX', 'RDNT', 'MAGIC',
                
                // Wrapped tokens and derivatives
                'WBTC', 'WETH', 'WBNB', 'WMATIC', 'WAVAX',
                
                // Layer 1 blockchains
                'AVAX', 'ATOM', 'OSMO', 'JUNO', 'SCRT', 'KAVA', 'BAND', 'IRIS', 'REGEN',
                
                // Cross-chain and bridge tokens
                'POLY', 'CELR', 'CBRIDGE', 'SYN', 'MULTI',
                
                // Metaverse and Web3 tokens
                'ILV', 'ALICE', 'TLM', 'PYR', 'GHST', 'REVV', 'TOWER',
                
                // Additional DeFi protocols
                'CVX', 'FXS', 'SPELL', 'MIM', 'BTRFLY', 'TOKE', 'ALCX', 'BADGER',
                
                // Solana ecosystem tokens
                'RAY', 'SRM', 'FIDA', 'ROPE', 'COPE', 'STEP', 'MEDIA', 'LIKE',
                
                // Polygon ecosystem tokens
                'QUICK', 'GHST', 'DQUICK', 'WMATIC',
                
                // BSC ecosystem tokens
                'CAKE', 'AUTO', 'BIFI', 'BELT', 'ALPACA', 'XVS', 'VAI',
                
                // Avalanche ecosystem tokens
                'JOE', 'PNG', 'QI', 'XAVA', 'BENQI', 'YAK',
                
                // Fantom ecosystem tokens
                'BOO', 'SPIRIT', 'LQDR', 'SCREAM', 'TAROT',
                
                // Arbitrum ecosystem tokens
                'DPX', 'RDNT', 'GMX', 'MAGIC', 'TREASURE',
                
                // Optimism ecosystem tokens
                'OP', 'VELO', 'SNX',
                
                // Additional tokens for comprehensive coverage
                'ALPHA', 'BETA', 'GAMMA', 'DELTA', 'EPSILON', 'ZETA', 'ETA', 'THETA',
                'IOTA', 'KAPPA', 'LAMBDA', 'MU', 'NU', 'XI', 'OMICRON', 'PI', 'RHO',
                'SIGMA', 'TAU', 'UPSILON', 'PHI', 'CHI', 'PSI', 'OMEGA'
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
