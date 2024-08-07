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
        Schema::table('kycs', function (Blueprint $table) {
            $table->foreignId('kyc_sub_level_id')
                ->after('id')->nullable()->constrained('kyc_sub_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->dropForeign(['kyc_sub_level_id']);
            $table->dropColumn('kyc_sub_level_id');
        });
    }
};
