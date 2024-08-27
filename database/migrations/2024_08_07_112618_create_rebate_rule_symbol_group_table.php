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
        Schema::create('rebate_rule_symbol_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rebate_rule_id')->constrained()->onDelete('cascade');
            $table->foreignId('symbol_group_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('rebate_rule_symbol_group');
    }
};
