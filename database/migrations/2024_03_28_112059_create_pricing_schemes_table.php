<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingSchemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('short')->nullable();
            $table->text('desc')->nullable();
            $table->double('amount')->default(0);
            $table->double('amount_allotted')->default(0);
            $table->double('discount_price')->default(0);
            $table->integer('leverage')->default(0);
            $table->string('days_to_pass');
            $table->integer('profit_share_user')->default(0);
            $table->integer('profit_share_admin')->default(0);
            $table->integer('profit_target')->default(0);
            $table->integer('min_trading_days')->default(0);
            $table->integer('max_trading_days')->default(0);
            $table->string('swap_group');
            $table->string('swap_free_group');
            $table->string('payouts');
            $table->string('term_type');
            $table->string('calc_period');
            $table->double('max_drawdown_limit')->default(0);
            $table->double('daily_drawdown_limit')->default(0);
            $table->string('status');
            $table->string('type')->nullable();
            $table->string('sub_type')->nullable();
            $table->string('stage')->default(1);
            $table->tinyInteger('is_highlighted')->default(0);
            $table->string('approval')->default('pending');
            $table->tinyInteger('ea_boat')->default(0);
            $table->tinyInteger('trading_news')->default(1);
            $table->tinyInteger('re_attempt_discount')->default(0);
            $table->tinyInteger('weekend_holding')->default(0);
            $table->tinyInteger('refundable')->default(0);
            $table->tinyInteger('is_discount')->default(0);
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
        Schema::dropIfExists('pricing_schemes');
    }
}
