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
        Schema::dropIfExists('forex_schema_phase_rules');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::create('forex_schema_phase_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('forex_schema_phase_id');
            $table->string('unique_id');
            $table->double('amount')->default(0.0);
            $table->double('fee')->default(0.0);
            $table->double('discount')->default(0.0);
            $table->string('currency')->nullable();
            $table->double('total')->default(0.0);
            $table->double('allotted_funds')->default(0.0);
            $table->double('daily_drawdown_limit')->default(0.0);
            $table->double('max_drawdown_limit')->default(0.0);
            $table->double('profit_target')->default(0.0);
            $table->double('profit_share_user')->default(0.0);
            $table->double('profit_share_admin')->default(0.0);
            $table->tinyInteger('is_new_order')->default(0);
            $table->timestamps();
            $table->softDeletes(); // Adding soft delete column

        });

    }
};
