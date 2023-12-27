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
        Schema::create('forex_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('forex_schema_id');
            $table->string('account_name');
            $table->string('login')->nullable();
            $table->string('account_type');
            $table->string('trading_platform');
            $table->string('group');
            $table->string('currency');
            $table->double('leverage');
            $table->double('balance')->default(0);
            $table->double('credit')->default(0);
            $table->double('equity')->default(0);
            $table->double('free_margin')->default(0);
            $table->string('server')->nullable();
            $table->string('agent')->nullable();
            $table->json('meta')->nullable();
            $table->string('status')->nullable();
            $table->json('created_by')->nullable();
            $table->tinyInteger('first_min_deposit_paid')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forex_accounts');
    }
};
