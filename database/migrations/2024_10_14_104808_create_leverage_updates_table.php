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
        Schema::create('leverage_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('forex_account_id');
            $table->integer('last_leverage');
            $table->integer('updated_leverage');
            $table->tinyInteger('status')->default(false);
            $table->foreignId('approved_by')->nullable();
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
        Schema::dropIfExists('leverage_updates');
    }
};
