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
        Schema::dropIfExists('forex_schemas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('forex_schemas', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->integer('priority');
            $table->string('badge')->nullable();
            $table->string('spread')->nullable();
            $table->string('commission')->nullable();
            $table->string('leverage');
            $table->double('first_min_deposit')->nullable();
            $table->integer('account_limit')->default(1);
            $table->bigInteger('start_range')->nullable();
            $table->bigInteger('end_range')->nullable();
            $table->tinyInteger('is_weekend_holding')->default(0);
            $table->tinyInteger('is_scalable')->default(0);
            $table->tinyInteger('is_refundable')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('country')->nullable();
            $table->text('tags')->nullable();
            $table->string('upto_allotted_fund')->nullable();
            $table->string('upto_profit_target')->nullable();
            $table->string('upto_daily_max_loss')->nullable();
            $table->string('upto_maximum_loss')->nullable();
            $table->timestamps();
        });
    }
};
