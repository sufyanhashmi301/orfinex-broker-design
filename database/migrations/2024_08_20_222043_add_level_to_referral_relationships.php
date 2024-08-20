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
        Schema::table('referral_relationships', function (Blueprint $table) {
            $table->foreignId('multi_level_id')->after('user_id')->nullable()->constrained('multi_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_relationships', function (Blueprint $table) {
            $table->dropColumn('multi_level_id'); // Removes the column if the migration is rolled back

        });
    }
};
