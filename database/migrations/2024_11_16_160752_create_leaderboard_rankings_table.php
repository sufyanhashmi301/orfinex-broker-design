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
        Schema::create('leaderboard_rankings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('leaderboard_rankings_id');
            $table->integer('ranking');
            $table->string('user_name');
            $table->decimal('profit', 8, 2);
            $table->decimal('equity', 8, 2);
            $table->string('account_size');
            $table->string('gain');

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
        Schema::dropIfExists('leaderboard_rankings');
    }
};
