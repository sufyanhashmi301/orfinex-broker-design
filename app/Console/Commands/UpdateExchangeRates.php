<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:update-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update exchange rates in the Countries table every 30 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
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

                // Loop through the countries and update exchange rates
                foreach ($rates as $countryCode => $rate) {
                    DB::table('rates')->where('currency_code', $countryCode)->update(['rate' => $rate]);
                }

                $this->info('Exchange rates updated successfully.');
            } else {
                $this->error('Failed to fetch exchange rates.');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
