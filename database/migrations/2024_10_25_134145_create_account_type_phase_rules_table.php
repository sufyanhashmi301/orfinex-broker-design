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
        Schema::create('account_type_phase_rules', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_type_phase_id');
            $table->string('unique_id')->unique();

            $table->double('allotted_funds')->default(0.0);
            $table->double('daily_drawdown_limit')->default(0.0);
            $table->double('max_drawdown_limit')->default(0.0);
            $table->double('profit_target')->default(0.0);
            
            $table->double('profit_share_user')->default(0.0);
            $table->double('profit_share_admin')->default(0.0);

            $table->string('currency')->nullable();
            $table->double('discount')->default(0.0);
            $table->double('amount')->default(0.0);
            $table->double('total')->default(0.0);
            $table->double('fee')->default(0.0);
            
            $table->tinyInteger('is_new_order')->default(0);
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
        Schema::dropIfExists('account_type_phase_rules');
    }
};
