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
        Schema::create('ib_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('deal');
            $table->string('login');
            $table->string('profit');
            $table->string('client_no');
            $table->string('trade_id');
            $table->string('level_share');
            $table->timestamp('process_time');
            $table->timestamp('calc_at')->nullable();
            $table->timestamp('clear_at')->nullable();
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
        Schema::dropIfExists('ib_transactions');
    }
};
