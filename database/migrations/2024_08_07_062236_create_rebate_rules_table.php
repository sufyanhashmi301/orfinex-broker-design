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
        Schema::create('rebate_rules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('rule_type_id');
            $table->decimal('rebate_amount',8,2)->default(0.00);
            $table->integer('per_lot');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('rebate_rules');
    }
};
