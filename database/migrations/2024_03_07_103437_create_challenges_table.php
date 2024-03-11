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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('challenge_name');
            $table->string('challenge_code');
            $table->string('schema_badge');
            $table->integer('type_of_phases')->default(0);
            $table->string('next_stage_process');
            $table->integer('max_purchase_limit')->nullable();
            $table->string('refundable_by')->default(0);
            $table->string('daily_risk_track')->nullable();
            $table->string('main_risk_track')->default(1);
            $table->string('random_risk_track')->default(5);
            $table->integer('priority_level')->default(0);
            $table->integer('affiliate_partner_commission')->default(0);
            $table->integer('vacations')->default(0);
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
        Schema::dropIfExists('challenges');
    }
};