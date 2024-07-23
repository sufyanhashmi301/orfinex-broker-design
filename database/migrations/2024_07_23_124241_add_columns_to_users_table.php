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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_level_1_completed')->default(0);
            $table->boolean('is_level_2_completed')->default(0);
            $table->boolean('is_level_3_completed')->default(0);
            $table->json('kyc_credential_level3')->nullable();
            $table->integer('kyc_level3')->default(0);
            $table->boolean('is_kyc_completed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_level_1_completed');
            $table->dropColumn('is_level_2_completed');
            $table->dropColumn('is_level_3_completed');
            $table->dropColumn('kyc_credential_level3');
            $table->dropColumn('kyc_level3');
            $table->dropColumn('is_kyc_completed');
        });
    }
};
