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
            
            // Add first_or_every_deposit column after description
            if (!Schema::hasColumn('bonuses', 'first_or_every_deposit')) {
                $table->string('first_or_every_deposit')->nullable()->after('description');
            }
            
            // Add is_kyc_verified column after applicable_by
            if (!Schema::hasColumn('bonuses', 'is_kyc_verified')) {
                $table->boolean('is_kyc_verified')->default(0)->after('applicable_by');
            }
            
            // Add kyc_verified column (seems to be required by database constraint)
            if (!Schema::hasColumn('bonuses', 'kyc_verified')) {
                $table->boolean('kyc_verified')->default(0)->after('is_kyc_verified');
            }
            
            // Add first_deposit column (seems to be required by database constraint)
            if (!Schema::hasColumn('bonuses', 'first_deposit')) {
                $table->boolean('first_deposit')->default(0)->after('kyc_verified');
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
            if (Schema::hasColumn('bonuses', 'first_deposit')) {
                $table->dropColumn('first_deposit');
            }
            
            if (Schema::hasColumn('bonuses', 'kyc_verified')) {
                $table->dropColumn('kyc_verified');
            }
            
            if (Schema::hasColumn('bonuses', 'is_kyc_verified')) {
                $table->dropColumn('is_kyc_verified');
            }
            
            if (Schema::hasColumn('bonuses', 'first_or_every_deposit')) {
                $table->dropColumn('first_or_every_deposit');
            }
            
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
