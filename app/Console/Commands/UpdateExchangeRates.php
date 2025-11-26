<?php

namespace App\Console\Commands;

use App\Models\Rate;
use App\Models\Plugin;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateExchangeRates extends Command
{
    protected $signature = 'exchange:update-rates {--api= : Specific API to use (feednax, rapidapi)} {--force : Force update even if auto-update is disabled}';
    protected $description = 'Update exchange rates with multiple API providers and fallback mechanism';

    private $supportedApis = [
        'feednax' => 'Feednax Exchange API',
        'rapidapi' => 'Currency Exchange API'
    ];

    public function handle()
    {
        try {
            // Check if auto exchange rates update is enabled (unless forced)
            if (!$this->option('force') && !$this->isAutoUpdateEnabled()) {
                $this->info('Auto exchange rates update is disabled in company permissions. Use --force to override.');
                // Log::info('Auto exchange rates update skipped - disabled in company permissions.');
                return 0;
            }

            $specificApi = $this->option('api');
            $rates = null;
            $usedApi = null;

            if ($specificApi && isset($this->supportedApis[$specificApi])) {
                // Use specific API if requested
                $rates = $this->fetchRatesFromApi($specificApi);
                $usedApi = $specificApi;
            } else {
                // Try APIs in priority order (Feednax first, then fallback)
                $rates = $this->fetchRatesWithFallback();
                $usedApi = $this->getLastUsedApi();
            }

            if (!$rates) {
                $errorMsg = 'Failed to fetch exchange rates from all available APIs.';
                $this->error($errorMsg);
                Log::error($errorMsg);
                return 1;
            }

            // Process and update rates
            $this->updateRatesInDatabase($rates, $usedApi);
            
            $this->info("Exchange rates updated successfully using {$usedApi} API.");
            Log::info("Exchange rates updated successfully using {$usedApi} API.", [
                'rates_count' => count($rates),
                'api_used' => $usedApi
            ]);

            return 0;

        } catch (\Exception $e) {
            $errorMsg = 'Error updating exchange rates: ' . $e->getMessage();
            $this->error($errorMsg);
            Log::error($errorMsg, [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * Fetch rates with fallback mechanism
     */
    private function fetchRatesWithFallback()
    {
        // Try Feednax first (higher priority)
        $rates = $this->fetchRatesFromApi('feednax');
        if ($rates) {
            $this->lastUsedApi = 'feednax';
            return $rates;
        }

        // Fallback to RapidAPI
        $rates = $this->fetchRatesFromApi('rapidapi');
        if ($rates) {
            $this->lastUsedApi = 'rapidapi';
            return $rates;
        }

        return null;
    }

    private $lastUsedApi = null;

    private function getLastUsedApi()
    {
        return $this->lastUsedApi;
    }

    /**
     * Fetch rates from specific API
     */
    private function fetchRatesFromApi($apiType)
    {
        try {
            switch ($apiType) {
                case 'feednax':
                    return $this->fetchFromFeednax();
                case 'rapidapi':
                    return $this->fetchFromRapidApi();
                default:
                    Log::warning("Unsupported API type: {$apiType}");
                    return null;
            }
        } catch (\Exception $e) {
            Log::error("Error fetching from {$apiType} API: " . $e->getMessage(), [
                'api_type' => $apiType,
                'exception' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Fetch rates from Feednax API
     */
    private function fetchFromFeednax()
    {
        $feednaxPlugin = Plugin::where('name', 'Feednax Exchange API')->where('status', 1)->first();
        
        if (!$feednaxPlugin) {
            // Log::info('Feednax Exchange API plugin not found or disabled.');
            return null;
        }

        $config = json_decode($feednaxPlugin->data, true);
        
        $response = Http::withOptions(['verify' => false])
            ->timeout(30)
            ->get($config['api_url'], [
                'apikey' => $config['api_key'] ?? 'demo'
            ]);

        if ($response->successful()) {
            $data = $response->json();
            dd($data);
            if ($data['success'] ?? false) {
                // Log::info('Successfully fetched rates from Feednax API', [
                //     'timestamp' => $data['timestamp'] ?? null,
                //     'base_currency' => $data['base'] ?? 'USD',
                //     'rates_count' => count($data['rates'] ?? [])
                // ]);
                return $data['rates'] ?? [];
            }
        }

        Log::warning('Feednax API request failed', [
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        return null;
    }

    /**
     * Fetch rates from RapidAPI
     */
    private function fetchFromRapidApi()
    {
        $rapidApiPlugin = Plugin::where('name', 'Currency Exchange API')->where('status', 1)->first();
        
        if (!$rapidApiPlugin) {
            // Log::info('Currency Exchange API plugin not found or disabled.');
            return null;
        }

        $config = json_decode($rapidApiPlugin->data, true);
        
        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'x-rapidapi-host' => $config['api_host'],
                'x-rapidapi-key' => $config['api_key']
            ])
            ->timeout(30)
            ->get($config['api_url'], [
                'base' => $config['base_currency'] ?? 'USD',
            ]);

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['result'])) {
                // Log::info('Successfully fetched rates from RapidAPI', [
                //     'base_currency' => $config['base_currency'] ?? 'USD',
                //     'rates_count' => count($data['result'])
                // ]);
                return $data['result'];
            }
        }

        Log::warning('RapidAPI request failed', [
            'status' => $response->status(),
            'response' => $response->body()
        ]);
        return null;
    }

    /**
     * Update rates in database tables
     */
    private function updateRatesInDatabase($rates, $apiSource)
    {
        DB::beginTransaction();
        
        try {
            $updateCounts = [
                'rates_table' => 0,
                'deposit_methods' => 0,
                'withdraw_methods' => 0
            ];

            // Update rates in the Rate model
            foreach ($rates as $currencyCode => $rate) {
                $formattedRate = $this->truncateToTwoDecimals($rate);
                $updated = Rate::where('currency_code', $currencyCode)
                    ->update([
                        'rate' => $formattedRate,
                        'updated_at' => Carbon::now()
                    ]);
                $updateCounts['rates_table'] += $updated;
            }

            // Fetch unique currencies from DepositMethod and WithdrawMethod
            $depositCurrencies = DB::table('deposit_methods')->distinct()->pluck('currency')->toArray();
            $withdrawCurrencies = DB::table('withdraw_methods')->distinct()->pluck('currency')->toArray();

            // Combine currencies and filter relevant rates
            $relevantCurrencies = array_unique(array_merge($depositCurrencies, $withdrawCurrencies));
            $filteredRates = array_intersect_key($rates, array_flip($relevantCurrencies));

            // Update rates in the DepositMethod table
            foreach ($filteredRates as $currencyCode => $rate) {
                $formattedRate = $this->truncateToTwoDecimals($rate);
                $updated = DB::table('deposit_methods')
                    ->where('currency', $currencyCode)
                    ->update([
                        'rate' => $formattedRate,
                        'updated_at' => Carbon::now()
                    ]);
                $updateCounts['deposit_methods'] += $updated;
            }

            // Update rates in the WithdrawMethod table
            foreach ($filteredRates as $currencyCode => $rate) {
                $formattedRate = $this->truncateToTwoDecimals($rate);
                $updated = DB::table('withdraw_methods')
                    ->where('currency', $currencyCode)
                    ->update([
                        'rate' => $formattedRate,
                        'updated_at' => Carbon::now()
                    ]);
                $updateCounts['withdraw_methods'] += $updated;
            }

            DB::commit();

            Log::info('Database rates updated successfully', [
                'api_source' => $apiSource,
                'update_counts' => $updateCounts,
                'total_rates_processed' => count($rates),
                'relevant_currencies' => count($filteredRates)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if auto exchange rates update is enabled in company permissions
     */
    private function isAutoUpdateEnabled()
    {
        try {
            // Use the setting helper function with permission section and default to enabled (1)
            return (bool) setting('auto_exchange_rates_update', 'permission', 1);
        } catch (\Exception $e) {
            Log::error('Error checking auto update permission: ' . $e->getMessage());
            // Default to enabled if there's an error
            return true;
        }
    }

    /**
     * Format rate based on currency type and value size
     * For crypto currencies with very small values, preserve more precision
     */
    private function truncateToTwoDecimals($number)
    {
        // Handle very small numbers (like BTC rates) by preserving more precision
        if ($number < 0.01 && $number > 0) {
            // For very small numbers, use up to 10 decimal places to preserve precision
            return round($number, 10);
        }
        
        // For regular currencies, use the original 2-decimal truncation
        return floor($number * 100) / 100;
    }
}