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
            $table->boolean('is_level_1_completed')->default(0)->after('kyc');
            $table->boolean('is_level_2_completed')->default(0)->after('is_level_1_completed');
            $table->boolean('is_level_3_completed')->default(0)->after('is_level_2_completed');
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
        });
    }
};
