<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LeaderboardBadgesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('leaderboard_badges')->truncate();
        DB::table('leaderboard_rankings_categories')->truncate();
        DB::table('leaderboard_rankings')->truncate();

        // leaderboard badges Seeder
        $data = [
            [
                'title' => 'Highest Payout',
                'title_slug' => Str::slug('Highest Payout', '_'),
                'user_name' => 'Jagroop D',
                'amount' => '$100,000',
                'details' => json_encode([
                    'achieved_amount' => '$123,00.23',
                    'gain' => '+123%'
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Best Ratio',
                'title_slug' => Str::slug('Best Ratio', '_'),
                'user_name' => 'Diego C',
                'amount' => '$5,000',
                'details' => json_encode([
                    'percentage' => '100%',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Fastest Evalution',
                'title_slug' => Str::slug('Fastest Evalution', '_'),
                'user_name' => 'Mayank S',
                'amount' => '$100,000',
                'details' => json_encode([
                    'time' => '1d 0h 1m 10s',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Medal 1',
                'title_slug' => Str::slug('Medal 1', '_'),
                'user_name' => 'Kai S',
                'amount' => '$100,000',
                'details' => json_encode([
                    'buy_or_sell' => 'buy',
                    'currency' => 'XAUUSD',
                    'gain' => '$12,000',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Medal 2',
                'title_slug' => Str::slug('Medal 2', '_'),
                'user_name' => 'Adib Z',
                'amount' => '$100,000',
                'details' => json_encode([
                    'buy_or_sell' => 'buy',
                    'currency' => 'XAUUSD',
                    'gain' => '$12,000',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Medal 3',
                'title_slug' => Str::slug('Medal 3', '_'),
                'user_name' => 'Ranger A',
                'amount' => '$100,000',
                'details' => json_encode([
                    'buy_or_sell' => 'sell',
                    'currency' => 'XAUUSD',
                    'gain' => '$12,000',
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('leaderboard_badges')->insert($data);

        // leaderboard ranking categories seeder
        $data2 = [
            [
                'name' => '10k',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => '25k',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => '50k',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => '100k',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        DB::table('leaderboard_rankings_categories')->insert($data2);

        // leaderboard rankings entries (fixed 5 for now)
        $data3 = [];
        for($i=0; $i < 5; $i++){

            $temp = [
                'leaderboard_rankings_category_id' => DB::table('leaderboard_rankings_categories')->first()->id,
                'ranking' => $i + 1,
                'user_name' => 'Debra',
                'profit' => '$7188',
                'equity' => '$9194',
                'account_size' => '$7492',
                'gain' => '7%',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            array_push($data3, $temp);
  
        }
        DB::table('leaderboard_rankings')->insert($data3);
        

    }
}
