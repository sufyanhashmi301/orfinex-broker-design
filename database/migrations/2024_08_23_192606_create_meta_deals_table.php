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
        Schema::create('meta_deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->bigInteger('login');
            $table->bigInteger('deal');
            $table->bigInteger('dealer');
            $table->bigInteger('order');
            $table->string('symbol');
            $table->bigInteger('volume');
            $table->bigInteger('volume_closed');
            $table->double('lot_share')->default(0);
            $table->dateTime('time');
            $table->dateTime('is_paid')->nullable();
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
        Schema::dropIfExists('meta_deals');
    }
};
