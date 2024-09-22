<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ForexSchemaInvestment;
use App\Models\UserTradingDailyStat; // Use the correct model for saving daily stats
use App\Services\ForexApiService; // Ensure this service exists
use App\Enums\InvestmentStatus;

class FetchInvestmentDailyScore extends Command
{
    // The name and signature of the command
    protected $signature = 'investments:fetch-daily-score';

    // The console command description
    protected $description = 'Fetch daily score for active investments and save it into the user_trading_daily_stats table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get only active investments
        $activeInvestments = ForexSchemaInvestment::where('status', InvestmentStatus::ACTIVE)
            ->orderBy('id', 'desc')
            ->get();

        // Initialize Forex API service
        $forexApi = new ForexApiService();

        foreach ($activeInvestments as $invest) {
            // Prepare data for API request
            $data = [
                'login' => $invest->login
            ];

            // Fetch the daily risk score from the Forex API
            $todayScore = $forexApi->getTodayRiskScore($data);

            if ($todayScore['success']) {
                // Save the score into UserTradingDailyStat table
                UserTradingDailyStat::create([
                    'user_id' => $invest->user_id, // Assuming the investment has a user_id field
                    'login' => $todayScore['result']['login'],
                    'net_profit' => $todayScore['result']['net_Profit'],
                    'highest_profit_trade' => $todayScore['result']['highest_Profit_Trade'],
                    'highest_lost_trade' => $todayScore['result']['highest_Lost_Trade'],
                    'total_trades' => $todayScore['result']['total_Trades'],
                    'total_profit' => $todayScore['result']['total_Profit'],
                    'total_losses' => $todayScore['result']['total_Losses'],
                    'pnl_ratio' => $todayScore['result']['pnL_Ratio'],
                    'avg_trade_profit_per_loss' => $todayScore['result']['avg_Trade_Profit_Per_Loss'],
                    'win_rate' => $todayScore['result']['win_Rate'],
                    'loss_rate' => $todayScore['result']['loss_Rate'],
                    'avg_holding_time' => $todayScore['result']['avg_Holding_Time'],
                    'total_deposits' => $todayScore['result']['total_Deposits'],
                    'total_withdrawals' => $todayScore['result']['total_Withdrawals'],
                    'withdrawal_rate' => $todayScore['result']['withdrawal_Rate'],
                    'risk_reward_ratio' => $todayScore['result']['risk_Reward_Ratio'],
                    'capital_retention_ratio' => $todayScore['result']['captial_Retention_Ratio'],
                    'stat_date' => now(), // You can adjust this if API provides a specific date
                ]);

                // Log success message
                $this->info('Daily score saved for user ID: ' . $invest->user_id);
            } else {
                // Log if there was an issue fetching the score
                $this->error('Failed to fetch daily score for user ID: ' . $invest->user_id);
            }
        }

        return Command::SUCCESS;
    }
}
