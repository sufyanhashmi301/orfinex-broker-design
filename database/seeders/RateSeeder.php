<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rate;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info("Starting currency seeding...");
        // Define the currencies that need to be seeded/updated
        $currencies = [
            "BTC" => "Bitcoin",
            "ETH_USDT" => "Ethereum USDT",
            "BNB" => "Binance Coin", 
            "USDT-TRX" => "Tether TRX",
            "USDT-BSC" => "Tether BSC",
            "USDC-BSC" => "USD Coin BSC",
            "ETH_BUSD" => "Ethereum BUSD",
            "USDT-POLYGON" => "Tether Polygon"
        ];

        try {
            DB::beginTransaction();
            
            foreach ($currencies as $currency_code => $currency_name) {
                // Check if currency exists
                $existingRate = Rate::where('currency_code', $currency_code)->first();
                
                if ($existingRate) {
                    // Update existing rate to 1
                    $existingRate->update([
                        'rate' => 1,
                        'currency_name' => $currency_name,
                        'currency_symbol' => $currency_code
                    ]);
                    
                    $this->command->info("Updated existing currency: {$currency_code} with rate = 1");
                } else {
                    // Create new currency record
                    Rate::create([
                        'country_id' => null,
                        'currency_code' => $currency_code,
                        'currency_name' => $currency_name,
                        'currency_symbol' => $currency_code,
                        'rate' => 1
                    ]);
                    
                    $this->command->info("Created new currency: {$currency_code} with rate = 1");
                }
            }
            
            DB::commit();
            $this->command->info("Successfully processed all currencies.");
            
        } catch (\Exception $e) {
            DB::rollback();
            $this->command->error("Error processing currencies: " . $e->getMessage());
            throw $e;
        }
    }
}
