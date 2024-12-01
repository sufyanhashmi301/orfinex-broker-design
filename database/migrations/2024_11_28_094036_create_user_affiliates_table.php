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
        Schema::create('user_affiliates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->integer('refer_count')->default(0);
            $table->decimal('total_purchase_amount', 8, 2);
            $table->decimal('total_commission', 8, 2);
            $table->decimal('commission_withdrawn', 8, 2);
            $table->decimal('commission_pending', 8, 2);
            $table->decimal('highest_commission_earned', 8, 2);
            // $table->decimal('current_balance', 8, 2);
            $table->decimal('withdrawable_balance', 8, 2);
            $table->string('referral_link');

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
        Schema::dropIfExists('user_affiliates');
    }
};
