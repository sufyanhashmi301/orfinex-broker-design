<?php

use App\Enums\TraderType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->id();

            $table->string('trader_type')->default(TraderType::MT5);

            // Basics
            $table->string('icon')->nullable();
            $table->string('title');
            $table->string('platform_group');
            $table->string('type');
            $table->string('badge')->nullable();
            $table->integer('priority');
            $table->integer('accounts_limit')->default(1);
            $table->bigInteger('accounts_range_start')->nullable();
            $table->bigInteger('accounts_range_end')->nullable();
            $table->string('leverage');
            $table->string('commission')->nullable();
            $table->string('spread')->nullable();
            $table->text('description')->nullable();
            
            // Key Features
            $table->string('upto_allotted_fund')->nullable();
            $table->string('upto_profit_target')->nullable();
            $table->string('upto_daily_max_loss')->nullable();
            $table->string('upto_maximum_loss')->nullable();
            
            // Filters
            $table->text('tags')->nullable();
            $table->text('countries')->nullable();
            
            // Options
            $table->tinyInteger('is_weekend_holding')->default(0);
            $table->tinyInteger('is_scalable')->default(0);
            $table->tinyInteger('is_refundable')->default(0);
            $table->tinyInteger('status')->default(0);

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
        Schema::dropIfExists('account_types');
    }
};
