<?php

namespace App\Console\Commands;

use App\Models\Rate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateTokenRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:update-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update token rates in the Countries table every 30 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $response = Http::withHeaders([
                'x-rapidapi-host' => 'crypto-market-prices.p.rapidapi.com',
                'x-rapidapi-key' => '3eab3debf0msh7b97dbe30b9a426p1809fejsn0a7ea4674ebd'
            ])
            ->withoutVerifying() // Disable SSL verification
            ->get('https://crypto-market-prices.p.rapidapi.com/tokens?base=USD');

            if ($response->successful()) {
                // Decode the JSON response
                $data = $response->json();
        
                // Extract the tokens
                $tokens = $data['data']['tokens'];
        
                // Step 3: Update the rates in the database
                foreach ($tokens as $token) {
                    // Match currency_code with symbol and update the rate
                    Rate::where('currency_code', $token['symbol'])
                        ->whereNull('country_id') // Check for country_id is null
                        ->update(['rate' => 1 / $token['price']]);
                }
                $this->info('Rates updated successfully.');
       
            } else {
                // Handle error response
                $this->info('Failed to fetch data from API.');
            }

            
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
