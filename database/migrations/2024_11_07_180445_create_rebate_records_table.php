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
        Schema::create('rebate_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->integer('login');
            $table->integer('deal')->nullable();
            $table->string('symbol')->nullable();
            $table->double('amount')->default(0.0);
            $table->double('rebate_amount')->default(0.0);
            $table->double('final_amount')->default(0.0);
            $table->string('currency')->default(base_currency());
            $table->datetime('record_at');
            $table->datetime('cleared_at')->nullable();
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
        Schema::dropIfExists('rebate_records');
    }
};
