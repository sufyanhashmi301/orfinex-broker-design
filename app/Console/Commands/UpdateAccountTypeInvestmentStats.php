<?php

namespace App\Console\Commands;

use App\Models\AccountType;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use App\Services\ForexApiService;
use App\Models\AccountTypeInvestment;
use Illuminate\Support\Facades\Artisan;

class UpdateAccountTypeInvestmentStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:investment-stats {--save-record} {--both}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Account Type Investment Stats from API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Use to store record in hourly records
        $hourly_shceduled = $this->option('save-record');

        // update the stats and also store
        $both = $this->option('both');

        // Step 1: Get unique platform groups
        $platformGroups = AccountType::pluck('platform_group')->unique();

        $allResults = collect();

        // Step 2: Call API for each platform group and merge results
        foreach ($platformGroups as $group) {
            $forexApi = new ForexApiService();
            $api_data = [
                'group' => $group
            ];
            $response = $forexApi->statsUserGroup($api_data);
            
            if (isset($response)) {
                $allResults = $allResults->merge($response['result']);
            } else {
                $this->error("Failed to fetch data for group: {$group}");
            }
        }
        // dd($allResults);
        // Step 3: Loop through each AccountTypeInvestment and update stats
        foreach (AccountTypeInvestment::all() as $investment) {
            $matchingResult = $allResults->firstWhere('login', $investment->login);
            
            if ($matchingResult) {

                // Skip processing if current_equity is null
                if (!isset($matchingResult['current_Equity'])) {
                    $this->error("Skipping investment ID {$investment->id} due to null current_equity");
                    continue;
                }

                $data = [
                    'account_type_investment_id' => $investment->id,
                    'account_name' => $matchingResult['name'],
                    'platform_group' => $matchingResult['group'],
                    'balance' => $matchingResult['balance'],
                    'current_equity' => $matchingResult['current_Equity'],
                    'credit' => $matchingResult['credit'],
                    'prev_day_balance' => $matchingResult['prevDayBalance'],
                    'prev_day_equity' => $matchingResult['prevDayEquity'],
                    'today_pnl_realized' => $matchingResult['todayPNL_Realized'],
                    'today_pnl_unrealized' => $matchingResult['todayPNL_UnRealized'],
                    'total_pnl' => $matchingResult['total_PNL'],
                    'trading_days' => $matchingResult['tradingDays'],
                    'max_balance' => $matchingResult['max_Balance'],
                ];

                if($both) {
                    $investment->accountTypeInvestmentHourlyStatsRecord()->create($data + [ 'created_at' => CarbonImmutable::now() ]);

                    $stat = $investment->accountTypeInvestmentStat()->firstOrNew();
                    $stat->fill($data + ['updated_at' => CarbonImmutable::now()]);
                    $stat->save();
                } else{
                    if($hourly_shceduled) {
                        $stat = $investment->accountTypeInvestmentHourlyStatsRecord()->create($data + [ 'created_at' => CarbonImmutable::now() ]);
                    }else{
                        $stat = $investment->accountTypeInvestmentStat()->firstOrNew();
                        $stat->fill($data + ['updated_at' => CarbonImmutable::now()]);
                        $stat->save();
                    }
                }

                
            }
        }

        if($hourly_shceduled) {
            $this->info('Investment stats stored successfully!');
            // Artisan::call('accounts:promote-or-violate');
        }else{
            $this->info('Investment stats updated successfully!');
        }
        
    }
}
