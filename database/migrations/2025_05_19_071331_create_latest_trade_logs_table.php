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
        Schema::create('latest_trade_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_type_investment_id');
            $table->decimal('balance', 8, 2);
            $table->decimal('current_equity', 8, 2);
            $table->unsignedInteger('trading_days');

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
        Schema::dropIfExists('latest_trade_logs');
    }
};
