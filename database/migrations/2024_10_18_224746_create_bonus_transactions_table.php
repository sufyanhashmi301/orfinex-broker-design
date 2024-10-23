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
        Schema::create('bonus_transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('bonus_id');

            $table->string('account_target_id');
            $table->string('account_type');

            $table->string('given_by'); // Admin or System
            $table->string('bonus_amount');
            $table->string('bonus_amount_left')->default(0);

            $table->string('bonus_removal_type');
            $table->string('bonus_removal_amount')->nullable();

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
        Schema::dropIfExists('bonus_transactions');
    }
};
