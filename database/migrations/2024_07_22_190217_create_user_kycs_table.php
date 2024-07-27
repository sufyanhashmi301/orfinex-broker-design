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
        Schema::create('user_kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kyclevel_id')->constrained('kyc_levels');
            $table->foreignId('kyclevelsetting_id')->constrained('kyc_level_settings');
            $table->json('kyc_credential')->nullable();
            $table->integer('kyc')->default(0);
            $table->boolean('is_level_1_completed')->default(0);
            $table->boolean('is_level_2_completed')->default(0);
            $table->boolean('is_level_3_completed')->default(0);
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
        Schema::dropIfExists('user_kycs');
    }
};
