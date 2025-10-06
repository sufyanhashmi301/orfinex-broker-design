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
        Schema::table('bonuses', function (Blueprint $table) {
            // Add kyc_slug column after bonus_name
            if (!Schema::hasColumn('bonuses', 'kyc_slug')) {
                $table->string('kyc_slug')->nullable()->after('bonus_name');
            }
            
            // Add bonus removal columns after amount
            if (!Schema::hasColumn('bonuses', 'bonus_removal_type')) {
                $table->string('bonus_removal_type')->nullable()->after('amount');
            }
            
            if (!Schema::hasColumn('bonuses', 'bonus_removal_amount')) {
                $table->string('bonus_removal_amount')->nullable()->after('bonus_removal_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bonuses', function (Blueprint $table) {
            // Remove columns in reverse order
            if (Schema::hasColumn('bonuses', 'bonus_removal_amount')) {
                $table->dropColumn('bonus_removal_amount');
            }
            
            if (Schema::hasColumn('bonuses', 'bonus_removal_type')) {
                $table->dropColumn('bonus_removal_type');
            }
            
            if (Schema::hasColumn('bonuses', 'kyc_slug')) {
                $table->dropColumn('kyc_slug');
            }
        });
    }
};
