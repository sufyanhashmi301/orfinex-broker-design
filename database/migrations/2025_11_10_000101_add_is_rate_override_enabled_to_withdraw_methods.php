<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('withdraw_methods', function (Blueprint $table) {
            if (!Schema::hasColumn('withdraw_methods', 'is_rate_override_enabled')) {
                $table->boolean('is_rate_override_enabled')->default(false)->after('rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('withdraw_methods', function (Blueprint $table) {
            if (Schema::hasColumn('withdraw_methods', 'is_rate_override_enabled')) {
                $table->dropColumn('is_rate_override_enabled');
            }
        });
    }
};



