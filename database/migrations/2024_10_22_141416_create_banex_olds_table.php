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
        Schema::create('banex_olds', function (Blueprint $table) {
            $table->id();
            $table->text('login')->nullable();
            $table->text('first_name')->nullable();
            $table->text('last_name')->nullable();
            $table->text('group')->nullable();
            $table->text('country')->nullable();
            $table->text('email')->nullable();
            $table->text('balance')->nullable();
            $table->text('equity')->nullable();
            $table->text('profit')->nullable();
            $table->text('floating')->nullable();
            $table->text('currency')->nullable();
            $table->text('lead_score')->nullable();
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
        Schema::dropIfExists('banex_olds');
    }
};
