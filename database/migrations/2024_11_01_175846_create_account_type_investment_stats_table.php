<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_type_investment_stats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_type_investment_id');
            
            $table->string('account_name');
            $table->string('platform_group');

            $table->decimal('balance', 8, 2);
            $table->decimal('current_equity', 8, 2);
            $table->decimal('credit', 8, 2);
            $table->decimal('prev_day_balance', 8, 2);
            $table->decimal('prev_day_equity', 8, 2);
            $table->decimal('today_pnl_realized', 8, 2);
            $table->decimal('today_pnl_unrealized', 8, 2);
            $table->decimal('total_pnl', 8, 2);
            $table->decimal('max_balance', 8, 2);

            $table->unsignedInteger('trading_days');

            $table->dateTime('updated_at');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_type_investment_stats');
    }
};
