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
        Schema::create('affiliate_rule_levels', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('affiliate_rule_id');
            $table->unsignedBigInteger('level');
            $table->string('commission_percentage');

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
        Schema::dropIfExists('affiliate_rule_levels');
    }
};
