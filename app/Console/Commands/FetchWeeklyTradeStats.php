<?php

namespace App\Console\Commands;

use App\Enums\InvestmentStatus;
use App\Models\ForexSchemaInvestment;
use Illuminate\Console\Command;
use App\Models\WeeklyTradeStat; // Your model for the weekly_trade_stats table
use App\Services\ForexApiService; // Assuming you have a service class to interact with your Forex API

class FetchWeeklyTradeStats extends Command
{
    // The name and signature of the command
    protected $signature = 'trade:fetch-weekly-stats';

    // The console command description
    protected $description = 'Fetch weekly trade stats and save them into the weekly_trade_stats table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Initialize your Forex API service
        $forexApi = new ForexApiService();

        // Assume you're fetching multiple accounts' weekly stats from an API
        $weeklyAccounts = ForexSchemaInvestment::where('status', InvestmentStatus::ACTIVE)
            ->get(); // You can customize this to get the investments for which you want to fetch weekly stats

        foreach ($weeklyAccounts as $account) {
            // Fetch weekly stats for the account login
            $data = [
                'login' => $account->login,
            ];

            $weeklyScore = $forexApi->getWeekRiskScore($data); // Replace this with the actual API call for weekly stats

            if ($weeklyScore['success']) {
                // Save the weekly stats into the database
                WeeklyTradeStat::create([
                    'login' => $weeklyScore['result']['login'],
                    'net_profit' => $weeklyScore['result']['net_Profit'],
                    'highest_profit_trade' => $weeklyScore['result']['highest_Profit_Trade'],
                    'highest_lost_trade' => $weeklyScore['result']['highest_Lost_Trade'],
                    'total_trades' => $weeklyScore['result']['total_Trades'],
                    'total_profit' => $weeklyScore['result']['total_Profit'],
                    'total_losses' => $weeklyScore['result']['total_Losses'],
                    'pnl_ratio' => $weeklyScore['result']['pnL_Ratio'],
                    'avg_trade_profit_per_loss' => $weeklyScore['result']['avg_Trade_Profit_Per_Loss'],
                    'win_rate' => $weeklyScore['result']['win_Rate'],
                    'loss_rate' => $weeklyScore['result']['loss_Rate'],
                    'avg_holding_time' => $weeklyScore['result']['avg_Holding_Time'],
                    'total_deposits' => $weeklyScore['result']['total_Deposits'],
                    'total_withdrawals' => $weeklyScore['result']['total_Withdrawals'],
                    'withdrawal_rate' => $weeklyScore['result']['withdrawal_Rate'],
                    'risk_reward_ratio' => $weeklyScore['result']['risk_Reward_Ratio'],
                    'capital_retention_ratio' => $weeklyScore['result']['captial_Retention_Ratio'],
                    'stat_date' => now(), // You can modify this if the API provides a specific date
                ]);

                // Log success
                $this->info('Weekly trade stats saved for login: ' . $weeklyScore['result']['login']);
            } else {
                // Log failure if the API request failed
                $this->error('Failed to fetch weekly trade stats for login: ' . $account->login);
            }
        }

        return Command::SUCCESS;
    }
}
