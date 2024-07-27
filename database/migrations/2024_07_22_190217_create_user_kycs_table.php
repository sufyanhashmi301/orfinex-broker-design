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
        Schema::create('userkycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kyclevel_id')->constrained('kyclevels');
            $table->foreignId('kyclevelsetting_id')->constrained('kyclevelsettings');
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
        Schema::dropIfExists('userkycs');
    }
};
