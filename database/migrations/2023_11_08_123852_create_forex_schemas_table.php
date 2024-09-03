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
        Schema::create('forex_schemas', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('desc')->nullable();
            $table->string('badge')->nullable();
            $table->string('leverage');
            $table->double('first_min_deposit')->nullable();
            $table->string('real_swap_free')->nullable();
            $table->string('real_islamic')->nullable();
            $table->string('demo_swap_free')->nullable();
            $table->string('demo_islamic')->nullable();
            $table->tinyInteger('is_withdraw')->default(0);
            $table->tinyInteger('is_ib_partner')->default(0);
            $table->tinyInteger('is_internal_transfer')->default(0);
            $table->tinyInteger('is_external_transfer')->default(0);
            $table->tinyInteger('is_bonus')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->text('country')->nullable();
            $table->timestamps();
        });
        DB::table('forex_schemas')->update(['country' => json_encode(["All"])]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forex_schemas');
    }
};
