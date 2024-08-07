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
        Schema::create('rebate_rule_has_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rebate_rule_id')->constrained('rebate_rules');
            $table->foreignId('symbol_group_id')->constrained('symbol_groups');
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
        Schema::dropIfExists('rebate_rule_has_groups');
    }
};
