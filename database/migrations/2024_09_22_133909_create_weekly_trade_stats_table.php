<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeeklyTradeStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_trade_stats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('login');
            $table->decimal('net_profit', 15, 8)->default(0);
            $table->decimal('highest_profit_trade', 15, 8)->default(0);
            $table->decimal('highest_lost_trade', 15, 8)->default(0);
            $table->integer('total_trades')->default(0);
            $table->decimal('total_profit', 15, 8)->default(0);
            $table->decimal('total_losses', 15, 8)->default(0);
            $table->decimal('pnl_ratio', 10, 2)->default(0);
            $table->decimal('avg_trade_profit_per_loss', 10, 2)->default(0);
            $table->decimal('win_rate', 10, 2)->default(0);
            $table->decimal('loss_rate', 10, 2)->default(0);
            $table->decimal('avg_holding_time', 15, 8)->default(0);
            $table->decimal('total_deposits', 15, 8)->default(0);
            $table->decimal('total_withdrawals', 15, 8)->default(0);
            $table->decimal('withdrawal_rate', 10, 2)->default(0);
            $table->decimal('risk_reward_ratio', 10, 2)->default(0);
            $table->decimal('capital_retention_ratio', 10, 2)->default(0);
            $table->date('stat_date'); // To track the week for the statistics
            $table->timestamps(); // To track when the record is created and updated
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weekly_trade_stats');
    }
}
