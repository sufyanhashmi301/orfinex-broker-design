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
        Schema::create('bonus_deductions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bonus_transaction_id');
            
            $table->unsignedBigInteger('withdraw_transaction_id');

            $table->string('deducted_amount');

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
        Schema::dropIfExists('bonus_deductions');
    }
};
