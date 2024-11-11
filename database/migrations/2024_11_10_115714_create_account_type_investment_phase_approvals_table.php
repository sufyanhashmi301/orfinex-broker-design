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
        Schema::create('account_type_investment_phase_approvals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_type_investment_id');
            $table->unsignedBigInteger('account_type_phase_id');

            $table->string('phase_type');
            $table->string('status');
            $table->integer('action')->default(0); // 0 or 1

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
        Schema::dropIfExists('account_type_investment_phase_approvals');
    }
};
