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
        Schema::create('funded_balances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            // $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('account_type_investment_id');
            $table->integer('user_profit_share');
            // $table->integer('added_to_payout_request');
            $table->decimal('profit', 8, 2);
            $table->decimal('last_retrieved_profit', 8, 2);
            $table->decimal('payout_pending', 8, 2);

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
        Schema::dropIfExists('funded_balances');
    }
};
