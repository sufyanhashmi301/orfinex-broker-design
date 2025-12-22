<?php

namespace App\Console\Commands;

use App\Models\Gateway;
use App\Models\Rate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class UpdateCryptoRatesFromGateways extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:update-gateway-rates {--force : Force update all rates} {--gateway= : Specific gateway code to process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch unique crypto currencies from gateways table and update rates using CoinGecko API';

    /**
     * CoinGecko API base URL
     */
    private const COINGECKO_API_URL = 'https://api.coingecko.com/api/v3/simple/price';

    /**
     * Batch size for API calls to avoid rate limiting
     */
    private const BATCH_SIZE = 50;

    /**
     * Mapping of currency codes to CoinGecko IDs
     * This includes support for network-specific tokens (e.g., USDT.ERC20, USDT.TRC20)
     */
    private array $currencyToCoinGeckoMap = [
        // Native Cryptocurrencies
        'BTC' => 'bitcoin',
        'ETH' => 'ethereum',
        'XRP' => 'ripple',
        'BCH' => 'bitcoin-cash',
        'LTC' => 'litecoin',
        'LTCT' => 'litecoin', // Litecoin Test
        'TRX' => 'tron',
        'DASH' => 'dash',
        'POL' => 'matic-network',
        'MATIC' => 'matic-network',
        'TON' => 'the-open-network',
        'DOGE' => 'dogecoin',
        'ETC' => 'ethereum-classic',
        'SOL' => 'solana',
        'QTUM' => 'qtum',
        'XVG' => 'verge',
        'FIRO' => 'firo',
        'BNB' => 'binancecoin',
        'AVAX' => 'avalanche-2',
        'ADA' => 'cardano',
        'DOT' => 'polkadot',
        'LINK' => 'chainlink',
        'XLM' => 'stellar',
        'XMR' => 'monero',
        'EOS' => 'eos',
        'ATOM' => 'cosmos',
        'NEO' => 'neo',
        'ZEC' => 'zcash',
        
        // Stablecoins - Base tokens
        'USDT' => 'tether',
        'USDC' => 'usd-coin',
        'BUSD' => 'binance-usd',
        'DAI' => 'dai',
        'TUSD' => 'true-usd',
        'FDUSD' => 'first-digital-usd',
        'UST' => 'terrausd',
        'USDP' => 'paxos-standard',
        
        // ERC-20 Tokens (Ethereum Network)
        'TUSD.ERC20' => 'true-usd',
        'CAKE.ERC20' => 'pancakeswap-token',
        'WLD.ERC20' => 'worldcoin-wld',
        'TON.ERC20' => 'the-open-network',
        'DAI.ERC20' => 'dai',
        'SHIB.ERC20' => 'shiba-inu',
        'EVERY.ERC20' => 'everyworld',
        'USDC.ERC20' => 'usd-coin',
        'ARB.ERC20' => 'arbitrum',
        'WETH.ERC20' => 'weth',
        'FDUSD.ERC20' => 'first-digital-usd',
        'GALA.ERC20' => 'gala',
        'USDT.ERC20' => 'tether',
        'INJ.ERC20' => 'injective-protocol',
        'UNI.ERC20' => 'uniswap',
        'AAVE.ERC20' => 'aave',
        'MKR.ERC20' => 'maker',
        'SNX.ERC20' => 'havven',
        'COMP.ERC20' => 'compound-governance-token',
        
        // BEP-20 Tokens (BSC Network)
        'BNB.BSC' => 'binancecoin',
        'CAKE.BEP20' => 'pancakeswap-token',
        'DAI.BEP20' => 'dai',
        'AVAX.BEP20' => 'avalanche-2',
        'ETH.BEP20' => 'ethereum',
        'SHIB.BEP20' => 'shiba-inu',
        'TUSD.BEP20' => 'true-usd',
        'WETH.BEP20' => 'weth',
        'USDT.BEP20' => 'tether',
        'BTC.BEP20' => 'binance-bitcoin',
        'BTCB.BEP20' => 'binance-bitcoin',
        'TON.BEP20' => 'the-open-network',
        'USDC.BEP20' => 'usd-coin',
        'INJ.BEP20' => 'injective-protocol',
        'AI.BEP20' => 'sleepless-ai',
        'FDUSD.BEP20' => 'first-digital-usd',
        
        // TRC-20 Tokens (Tron Network)
        'USDT.TRC20' => 'tether',
        'TON.TRC20' => 'the-open-network',
        'TUSD.TRC20' => 'true-usd',
        'USDC.TRC20' => 'usd-coin',
        
        // BASE Network Tokens
        'ETH.BASE' => 'ethereum',
        'WETH.BASE' => 'weth',
        'EVERY.BASE' => 'everyworld',
        'USDC.BASE' => 'usd-coin',
        
        // Polygon Network Tokens
        'USDC.POL' => 'usd-coin',
        'SHIB.POL' => 'shiba-inu',
        'WETH.POL' => 'weth',
        'USDT.POL' => 'tether',
        
        // Solana Network Tokens
        'USDC.SOL' => 'usd-coin',
        'USDT.SOL' => 'tether',
        
        // Arbitrum Network
        'ARB' => 'arbitrum',
        'ETH.ARB' => 'ethereum',
        
        // Optimism Network
        'OP' => 'optimism',
        'ETH.OP' => 'ethereum',
        
        // Other Popular Tokens
        'SHIB' => 'shiba-inu',
        'CAKE' => 'pancakeswap-token',
        'UNI' => 'uniswap',
        'AAVE' => 'aave',
        'SAND' => 'the-sandbox',
        'MANA' => 'decentraland',
        'APE' => 'apecoin',
        'CRO' => 'crypto-com-chain',
        'FTM' => 'fantom',
        'ALGO' => 'algorand',
        'VET' => 'vechain',
        'ICP' => 'internet-computer',
        'FIL' => 'filecoin',
        'HBAR' => 'hedera-hashgraph',
        'APT' => 'aptos',
        'OP' => 'optimism',
        'LDO' => 'lido-dao',
        'QNT' => 'quant-network',
        'ARB' => 'arbitrum',
        'IMX' => 'immutable-x',
        'STX' => 'blockstack',
        'INJ' => 'injective-protocol',
        'RUNE' => 'thorchain',
        'GRT' => 'the-graph',
        'MKR' => 'maker',
    ];

    /**
     * Mapping of currency symbols for display
     */
    private array $currencySymbols = [
        'BTC' => '₿',
        'ETH' => 'Ξ',
        'LTC' => 'Ł',
        'XRP' => 'XRP',
        'BCH' => 'BCH',
        'USDT' => '₮',
        'USDC' => 'USDC',
        'BNB' => 'BNB',
        'DOGE' => 'Ð',
        'TRX' => 'TRX',
        'SOL' => 'SOL',
        'ADA' => '₳',
        'DOT' => 'DOT',
        'MATIC' => 'MATIC',
        'POL' => 'POL',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting crypto rates update from gateways...');
        
        try {
            // Step 1: Get unique currencies from gateways
            $currencies = $this->getUniqueCurrenciesFromGateways();
            
            if (empty($currencies)) {
                $this->warn('No currencies found in gateways table.');
                return 0;
            }

            $this->info('Found ' . count($currencies) . ' unique currencies from gateways.');
            
            // Step 2: Identify crypto currencies (filter out fiat)
            $cryptoCurrencies = $this->filterCryptoCurrencies($currencies);
            
            if (empty($cryptoCurrencies)) {
                $this->warn('No crypto currencies found.');
                return 0;
            }

            $this->info('Identified ' . count($cryptoCurrencies) . ' crypto currencies.');
            
            // Step 3: Map to CoinGecko IDs
            $mappedCurrencies = $this->mapToCoinGeckoIds($cryptoCurrencies);
            
            if (empty($mappedCurrencies)) {
                $this->warn('No currencies could be mapped to CoinGecko IDs.');
                return 0;
            }

            $this->info('Successfully mapped ' . count($mappedCurrencies) . ' currencies to CoinGecko IDs.');
            
            // Step 4: Fetch rates from CoinGecko in batches
            $rates = $this->fetchRatesFromCoinGecko($mappedCurrencies);
            
            if (empty($rates)) {
                $this->error('Failed to fetch rates from CoinGecko API.');
                Log::error('CoinGecko API returned no rates.');
                return 1;
            }

            $this->info('Successfully fetched ' . count($rates) . ' rates from CoinGecko.');
            
            // Step 5: Update or insert rates in database
            $result = $this->updateOrInsertRates($rates);
            
            $this->info('✓ Rates update completed successfully!');
            $this->table(
                ['Action', 'Count'],
                [
                    ['Updated', $result['updated']],
                    ['Inserted', $result['inserted']],
                    ['Failed', $result['failed']],
                    ['Total Processed', $result['total']],
                ]
            );

            Log::info('Crypto rates updated from gateways', [
                'updated' => $result['updated'],
                'inserted' => $result['inserted'],
                'failed' => $result['failed'],
                'total' => $result['total'],
            ]);

            return 0;

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Error updating crypto rates from gateways', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }

    /**
     * Get unique currencies from all gateways
     *
     * @return array
     */
    private function getUniqueCurrenciesFromGateways(): array
    {
        try {
            $query = Gateway::query();
            
            // Filter by specific gateway if provided
            if ($gatewayCode = $this->option('gateway')) {
                $query->where('gateway_code', $gatewayCode);
                $this->info("Filtering by gateway: {$gatewayCode}");
            }

            $gateways = $query->get();
            
            $allCurrencies = [];

            foreach ($gateways as $gateway) {
                $supportedCurrencies = $gateway->supported_currencies;
                
                // Decode JSON if it's a string
                if (is_string($supportedCurrencies)) {
                    $supportedCurrencies = json_decode($supportedCurrencies, true);
                }
                
                // Ensure it's an array
                if (is_array($supportedCurrencies)) {
                    $allCurrencies = array_merge($allCurrencies, $supportedCurrencies);
                }
            }

            // Remove duplicates and return
            return array_unique($allCurrencies);

        } catch (\Exception $e) {
            Log::error('Error fetching currencies from gateways', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Filter out fiat currencies and keep only crypto currencies
     *
     * @param array $currencies
     * @return array
     */
    private function filterCryptoCurrencies(array $currencies): array
    {
        // Common fiat currencies to exclude
        $fiatCurrencies = [
            'USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SEK', 'NZD',
            'MXN', 'SGD', 'HKD', 'NOK', 'KRW', 'TRY', 'RUB', 'INR', 'BRL', 'ZAR',
            'PLN', 'THB', 'IDR', 'HUF', 'CZK', 'ILS', 'CLP', 'PHP', 'AED', 'COP',
            'SAR', 'MYR', 'RON', 'ARS', 'VND', 'PKR', 'EGP', 'NGN', 'BDT', 'UAH',
        ];

        return array_filter($currencies, function ($currency) use ($fiatCurrencies) {
            // Extract base currency code (before dot for network-specific tokens)
            $baseCurrency = explode('.', $currency)[0];
            
            // Keep if not in fiat list
            return !in_array(strtoupper($baseCurrency), $fiatCurrencies);
        });
    }

    /**
     * Map currency codes to CoinGecko IDs
     *
     * @param array $currencies
     * @return array [currencyCode => coinGeckoId]
     */
    private function mapToCoinGeckoIds(array $currencies): array
    {
        $mapped = [];
        $unmapped = [];

        foreach ($currencies as $currency) {
            $upperCurrency = strtoupper($currency);
            
            if (isset($this->currencyToCoinGeckoMap[$upperCurrency])) {
                $mapped[$upperCurrency] = $this->currencyToCoinGeckoMap[$upperCurrency];
            } else {
                $unmapped[] = $upperCurrency;
            }
        }

        if (!empty($unmapped)) {
            $this->warn('Unmapped currencies (will be skipped): ' . implode(', ', $unmapped));
            Log::warning('Unmapped currencies in CoinGecko mapping', [
                'currencies' => $unmapped,
            ]);
        }

        return $mapped;
    }

    /**
     * Fetch rates from CoinGecko API in batches
     *
     * @param array $mappedCurrencies [currencyCode => coinGeckoId]
     * @return array [currencyCode => rate]
     */
    private function fetchRatesFromCoinGecko(array $mappedCurrencies): array
    {
        $rates = [];
        
        // Get unique CoinGecko IDs (remove duplicates for tokens on different networks)
        $uniqueCoinGeckoIds = array_unique(array_values($mappedCurrencies));
        
        // Split into batches to avoid rate limiting
        $batches = array_chunk($uniqueCoinGeckoIds, self::BATCH_SIZE);
        
        $this->info('Fetching rates in ' . count($batches) . ' batch(es)...');

        foreach ($batches as $batchIndex => $batch) {
            try {
                $this->info('Processing batch ' . ($batchIndex + 1) . '/' . count($batches) . '...');
                
                $response = Http::withOptions(['verify' => false])
                    ->timeout(30)
                    ->get(self::COINGECKO_API_URL, [
                        'ids' => implode(',', $batch),
                        'vs_currencies' => 'usd',
                        'precision' => 'full',
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Map back to currency codes
                    foreach ($mappedCurrencies as $currencyCode => $coinGeckoId) {
                        if (isset($data[$coinGeckoId]['usd'])) {
                            $usdPrice = $data[$coinGeckoId]['usd'];
                            
                            // Calculate inverse rate (how many crypto units per 1 USD)
                            // This matches your existing rate format
                            if ($usdPrice > 0) {
                                $rates[$currencyCode] = 1 / $usdPrice;
                            } else {
                                $this->warn("Invalid price for {$currencyCode}: {$usdPrice}");
                            }
                        }
                    }
                } else {
                    $this->warn("Batch {$batchIndex} failed: HTTP " . $response->status());
                    Log::warning('CoinGecko API batch request failed', [
                        'batch_index' => $batchIndex,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);
                }

                // Rate limiting: sleep between batches
                if ($batchIndex < count($batches) - 1) {
                    sleep(2); // 2 seconds between batches to respect rate limits
                }

            } catch (\Exception $e) {
                $this->warn("Batch {$batchIndex} error: " . $e->getMessage());
                Log::error('Error fetching batch from CoinGecko', [
                    'batch_index' => $batchIndex,
                    'error' => $e->getMessage(),
                ]);
                continue;
            }
        }

        return $rates;
    }

    /**
     * Update or insert rates in the database
     *
     * @param array $rates [currencyCode => rate]
     * @return array ['updated' => int, 'inserted' => int, 'failed' => int, 'total' => int]
     */
    private function updateOrInsertRates(array $rates): array
    {
        $result = [
            'updated' => 0,
            'inserted' => 0,
            'failed' => 0,
            'total' => count($rates),
        ];

        DB::beginTransaction();

        try {
            foreach ($rates as $currencyCode => $rate) {
                try {
                    // Extract base currency code (remove network suffix if present)
                    $baseCurrencyCode = explode('.', $currencyCode)[0];
                    
                    // Format rate with appropriate precision
                    $formattedRate = $this->formatRate($rate);
                    
                    // Get currency name and symbol
                    $currencyName = $this->getCurrencyName($baseCurrencyCode);
                    $currencySymbol = $this->getCurrencySymbol($baseCurrencyCode);

                    // Check if rate exists (for crypto, country_id should be null)
                    $existingRate = Rate::where('currency_code', $currencyCode)
                        ->whereNull('country_id')
                        ->first();

                    if ($existingRate) {
                        // Update existing rate
                        $existingRate->update([
                            'rate' => $formattedRate,
                            'currency_name' => $existingRate->currency_name ?: $currencyName,
                            'currency_symbol' => $existingRate->currency_symbol ?: $currencySymbol,
                            'updated_at' => Carbon::now(),
                        ]);
                        $result['updated']++;
                        $this->line("  ✓ Updated: {$currencyCode} = {$formattedRate}");
                    } else {
                        // Insert new rate
                        Rate::create([
                            'country_id' => null,
                            'currency_code' => $currencyCode,
                            'currency_name' => $currencyName,
                            'currency_symbol' => $currencySymbol,
                            'rate' => $formattedRate,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                        $result['inserted']++;
                        $this->line("  + Inserted: {$currencyCode} = {$formattedRate}");
                    }

                } catch (\Exception $e) {
                    $result['failed']++;
                    $this->error("  ✗ Failed: {$currencyCode} - " . $e->getMessage());
                    Log::error('Error updating/inserting rate', [
                        'currency_code' => $currencyCode,
                        'rate' => $rate,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $result;
    }

    /**
     * Format rate based on value size
     *
     * @param float $rate
     * @return float
     */
    private function formatRate(float $rate): float
    {
        // For very small numbers (high-value cryptos like BTC), preserve more precision
        if ($rate < 0.0001) {
            return round($rate, 10);
        } elseif ($rate < 0.01) {
            return round($rate, 8);
        } elseif ($rate < 1) {
            return round($rate, 6);
        } else {
            return round($rate, 4);
        }
    }

    /**
     * Get currency name from code
     *
     * @param string $code
     * @return string
     */
    private function getCurrencyName(string $code): string
    {
        $names = [
            'BTC' => 'Bitcoin',
            'ETH' => 'Ethereum',
            'LTC' => 'Litecoin',
            'XRP' => 'Ripple',
            'BCH' => 'Bitcoin Cash',
            'USDT' => 'Tether',
            'USDC' => 'USD Coin',
            'BNB' => 'Binance Coin',
            'DOGE' => 'Dogecoin',
            'TRX' => 'Tron',
            'SOL' => 'Solana',
            'ADA' => 'Cardano',
            'DOT' => 'Polkadot',
            'MATIC' => 'Polygon',
            'POL' => 'Polygon',
            'SHIB' => 'Shiba Inu',
            'DAI' => 'Dai',
            'AVAX' => 'Avalanche',
            'TON' => 'Toncoin',
            'ETC' => 'Ethereum Classic',
            'DASH' => 'Dash',
        ];

        return $names[$code] ?? $code;
    }

    /**
     * Get currency symbol from code
     *
     * @param string $code
     * @return string
     */
    private function getCurrencySymbol(string $code): string
    {
        return $this->currencySymbols[$code] ?? strtoupper($code);
    }
}

