<?php

namespace App\Console\Commands;

use App\Enums\InvestmentStatus;
use App\Enums\TraderType;
use Carbon\Carbon;
use App\Models\AccountType;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use App\Services\ForexApiService;
use App\Models\AccountTypeInvestment;
use Illuminate\Support\Facades\Artisan;
use App\Models\AccountTypeInvestmentHourlyStatsRecord;
use App\Models\AccountTypeInvestmentStat;
use App\Services\MatchTraderApiService;

class UpdateAccountTypeInvestmentStats extends Command
{
    private $DELETE_OLD_RECORDS_BY_X_HOURS = 48;

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
     * Delete older than 48 hours records
     */
    private function deleteOldRecords() {
        // Get the cutoff time (X hours ago)
        $cutoffTime = Carbon::now()->subHours($this->DELETE_OLD_RECORDS_BY_X_HOURS);

        // Get all distinct account_type_investment_id values
        $investmentIds = AccountTypeInvestmentHourlyStatsRecord::distinct()
            ->pluck('account_type_investment_id');

        // Loop through each account_type_investment_id and delete records older than 48 hours
        foreach ($investmentIds as $id) {
            AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $id)
                ->where('created_at', '<', $cutoffTime)
                ->delete();
        }
    }

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
        
        // Step 1: Call API to get results of all trading accounts
        $allResults = $this->getTradingAccountStats();

        // Step 2: Loop through each AccountTypeInvestment and update stats
        $active_accounts = AccountTypeInvestment::where('status', InvestmentStatus::ACTIVE)->get();
        foreach ($active_accounts as $investment) {
            $matchingResult = $allResults->firstWhere('login', $investment->login);
            
            if ($matchingResult) {

                // Validation | Continue if...
                if(setting('active_trader_type') == TraderType::MT5) {
                    // MT5 Validation | Skip processing if current_equity is null
                    if (!isset($matchingResult['current_Equity'])) {
                        $this->error("Skipping investment ID {$investment->id} due to null current_equity");
                        continue;
                    }

                    // MT5 Validation | If the platform group has not been returned then continue
                    if($matchingResult['group'] == '') {
                        continue;
                    }
                }
                
                // Process wrt Trader Type
                $data = $this->processData($matchingResult, $investment);

                if($both) {
                    $investment->accountTypeInvestmentHourlyStatsRecord()->create($data + [ 'created_at' => CarbonImmutable::now() ]);

                    $stat = $investment->accountTypeInvestmentStat()->firstOrNew();
                    $stat->fill($data + ['updated_at' => CarbonImmutable::now()]);
                    $stat->save();
                } else{
                    if($hourly_shceduled) {
                        
                        $stat = $investment->accountTypeInvestmentHourlyStatsRecord()->create($data + [ 'created_at' => CarbonImmutable::now() ]);
                        
                        // Delete records older than 48 hours
                        $this->deleteOldRecords();
                    } else {
                        $stat = $investment->accountTypeInvestmentStat()->firstOrNew();
                        $stat->fill($data + ['updated_at' => CarbonImmutable::now()]);
                        $stat->save();
                    }
                }

                if($data['trading_days'] > 0) {
                    if($investment->user->first_trade_at == null) {
                        $investment->user->update(['first_trade_at' => Carbon::now()]);
                    }
                }

                // Update Trading Days
                $this->updateTradingDays($investment);
            }
        }

        if($hourly_shceduled) {
            $this->info('Account stats stored successfully!');
            // Artisan::call('accounts:promote-or-violate');
        }else{
            $this->info('Account stats updated successfully!');
        }
        
    }

    /**
     * Get Trading Stats from Server wrt Active Trader Type
     */
    private function getTradingAccountStats() {

        $allResults = collect();

        if(setting('active_trader_type') == TraderType::MT5) {
            $platformGroups = AccountType::pluck('platform_group')->unique();
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
        } elseif(setting('active_trader_type') == TraderType::MT) {
            $matchTraderApi = new MatchTraderApiService();
            
            $response = $matchTraderApi->getAllForexTradingAccounts(['size' => 1000]);

            if(!$response) {
                $this->error("MatchTrader Server Error: Failed to fetch data");
            }

            foreach($response['content'] as $tradingAccount) {
                
                $allResults->push(array_merge(
                    ['login' => $tradingAccount->login],
                    (array) $tradingAccount->financeInfo
                ));
            }
        }

        return $allResults;
    }

    /**
     * Process data wrt Trader Type
     */
    private function processData($matchingResult, $investment) {

        $latest_record = AccountTypeInvestmentStat::where('account_type_investment_id', $investment->id)->first();

        if(setting('active_trader_type') == TraderType::MT5) {
            $data = [
                'account_type_investment_id' => $investment->id,
                'balance' => $matchingResult['balance'],
                'current_equity' => $matchingResult['current_Equity'],
                'trading_days' => $matchingResult['tradingDays'],
            ];
        } elseif(setting('active_trader_type') == TraderType::MT) {
            $data = [
                'account_type_investment_id' => $investment->id,
                'balance' => $matchingResult['credit'] - abs($matchingResult['balance']),
                'current_equity' => $matchingResult['equity'],
                'trading_days' => !$latest_record ? 0 : $latest_record->trading_days,
            ];
        }

        return $data;
    }

    /**
     * Update Trading Days at CRM level (For APIs like MatchTrader)
     */
    private function updateTradingDays($account) {
        
        $latest_record = AccountTypeInvestmentStat::where('account_type_investment_id', $account->id)->first();
        
        // If first day at trading then compare the allotted funds with current balance
        if($latest_record->trading_days == 0) {
            
            if($account->getRuleSnapshotData()['allotted_funds'] != $latest_record->balance) {
                $latest_record->trading_days = 1;
                $latest_record->trading_days_updated_at = Carbon::now();
            }
        } else {
            $yesterday_latest_record = AccountTypeInvestmentHourlyStatsRecord::where('account_type_investment_id', $account->id)
                                                                        ->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
                                                                        ->orderBy('id', 'desc')->first();
            // Trading days not updated today 
            if(Carbon::today()->toDateString() != Carbon::parse($latest_record->trading_days_updated_at)->toDateString()) {
                // Difference between yesterday's record balance compared to latest one
                if($yesterday_latest_record->balance != $latest_record->balance) {
                    $latest_record->trading_days = $latest_record->trading_days + 1;
                    $latest_record->trading_days_updated_at = Carbon::now();
                }
            }
        }

        $latest_record->save();
    }
}
