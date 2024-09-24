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
            $table->text('kyc_level3_credential')->nullable()->after('kyc_credential');
//            $table->dropColumn('is_level_1_completed');
//            $table->dropColumn('is_level_2_completed');
//            $table->dropColumn('is_level_3_completed');

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
            $table->dropColumn(['kyc_level3_credential']);
//            $table->tinyInteger('is_level_1_completed'); // Adjust the column type if necessary
//            $table->tinyInteger('is_level_2_completed'); // Adjust the column type if necessary
//            $table->tinyInteger('is_level_3_completed'); // Adjust the column type if necessary

        });
    }
};
