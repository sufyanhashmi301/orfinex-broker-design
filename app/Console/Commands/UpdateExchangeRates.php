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
    protected $signature = 'exchange:update-rates';
    protected $description = 'Update exchange rates in the Countries table every 30 minutes';

    public function handle()
    {
        try {
            // Get API credentials from Plugin
            $apiPlugin = Plugin::where('name', 'Currency Exchange API')->first();
            
            if (!$apiPlugin) {
                $this->error('Currency Exchange API plugin not found.');
                Log::error('Currency Exchange API plugin not found.');
                return 1;
            }
            
            $apiConfig = json_decode($apiPlugin->data, true);
            
            $response = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'x-rapidapi-host' => $apiConfig['api_host'],
                    'x-rapidapi-key' => $apiConfig['api_key']
                ])
                ->timeout(30)
                ->get($apiConfig['api_url'], [
                    'base' => $apiConfig['base_currency'] ?? 'USD',
                ]);

            if ($response->successful()) {
                $rates = $response->json()['result'];

                // Update rates in the Rate model
                foreach ($rates as $countryCode => $rate) {
                    $formattedRate = $this->truncateToTwoDecimals($rate);
                    Rate::where('currency_code', $countryCode)->update(['rate' => $formattedRate]);
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
                    DB::table('deposit_methods')
                        ->where('currency', $currencyCode)
                        ->update(['rate' => $formattedRate]);
                }

                // Update rates in the WithdrawMethod table
                foreach ($filteredRates as $currencyCode => $rate) {
                    $formattedRate = $this->truncateToTwoDecimals($rate);
                    DB::table('withdraw_methods')
                        ->where('currency', $currencyCode)
                        ->update(['rate' => $formattedRate]);
                }

                $this->info('Exchange rates updated successfully for all models.');
                Log::info('Exchange rates updated successfully.');
            } else {
                $errorMsg = 'Failed to fetch exchange rates. Response: ' . $response->body();
                $this->error($errorMsg);
                Log::error($errorMsg);
            }
        } catch (\Exception $e) {
            $errorMsg = 'Error updating exchange rates: ' . $e->getMessage();
            $this->error($errorMsg);
            Log::error($errorMsg);
            return 1;
        }
        
        return 0;
    }

    private function truncateToTwoDecimals($number)
    {
        return floor($number * 100) / 100;
    }
}