<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTradingDailyStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trading_daily_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key for user_id
            $table->bigInteger('login');
            $table->decimal('net_profit', 15, 8);
            $table->decimal('highest_profit_trade', 15, 8);
            $table->decimal('highest_lost_trade', 15, 8);
            $table->integer('total_trades');
            $table->decimal('total_profit', 15, 8);
            $table->decimal('total_losses', 15, 8);
            $table->decimal('pnl_ratio', 10, 2);
            $table->decimal('avg_trade_profit_per_loss', 10, 2);
            $table->decimal('win_rate', 10, 2);
            $table->decimal('loss_rate', 10, 2);
            $table->decimal('avg_holding_time', 15, 8);
            $table->decimal('total_deposits', 15, 8);
            $table->decimal('total_withdrawals', 15, 8);
            $table->decimal('withdrawal_rate', 10, 2);
            $table->decimal('risk_reward_ratio', 10, 2);
            $table->decimal('capital_retention_ratio', 10, 2);
            $table->date('stat_date'); // For daily stats, using 'stat_date' to represent the date for which these stats are recorded
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_trading_daily_stats');
    }
}
