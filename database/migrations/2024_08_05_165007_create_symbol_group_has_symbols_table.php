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
        Schema::create('symbol_group_has_symbols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained('symbols');
            $table->foreignId('symbol_group_id')->constrained('symbol_groups');
            $table->string('symbol_name');
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
        Schema::dropIfExists('symbol_group_has_symbols');
    }
};
