<?php

use App\Models\Rate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

public function handle()
{
    try {
        $response = Http::withOptions(['verify' => false])->withHeaders([
            'x-rapidapi-host' => 'currency-converter-pro1.p.rapidapi.com',
            'x-rapidapi-key' => '3eab3debf0msh7b97dbe30b9a426p1809fejsn0a7ea4674ebd'
        ])->timeout(30)
            ->get('https://currency-converter-pro1.p.rapidapi.com/latest-rates', [
                'base' => 'USD',
            ]);

        if ($response->successful()) {
            $rates = $response->json()['result'];

            // Update rates in the Rate model
            foreach ($rates as $countryCode => $rate) {
                Rate::where('currency_code', $countryCode)->update(['rate' => $rate]);
            }

            // Fetch unique currencies from DepositMethod and WithdrawMethod
            $depositCurrencies = DB::table('deposit_methods')->distinct()->pluck('currency')->toArray();
            $withdrawCurrencies = DB::table('withdraw_methods')->distinct()->pluck('currency')->toArray();

            // Combine currencies and filter relevant rates
            $relevantCurrencies = array_unique(array_merge($depositCurrencies, $withdrawCurrencies));
            $filteredRates = array_intersect_key($rates, array_flip($relevantCurrencies));

            // Update rates in the DepositMethod table
            foreach ($filteredRates as $currencyCode => $rate) {
                DB::table('deposit_methods')
                    ->where('currency', $currencyCode)
                    ->update(['rate' => $rate]);
            }

            // Update rates in the WithdrawMethod table
            foreach ($filteredRates as $currencyCode => $rate) {
                DB::table('withdraw_methods')
                    ->where('currency', $currencyCode)
                    ->update(['rate' => $rate]);
            }

            $this->info('Exchange rates updated successfully for all models.');
        } else {
            $this->error('Failed to fetch exchange rates.');
        }
    } catch (\Exception $e) {
        $this->error('Error: ' . $e->getMessage());
    }
}
