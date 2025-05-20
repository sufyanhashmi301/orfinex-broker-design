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
        Schema::table('affiliate_rules', function (Blueprint $table) {
            $table->decimal('min_payout_limit', 8, 2)->default(0)->after('has_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('affiliate_rules', function (Blueprint $table) {
            $table->dropColumn('min_payout_limit');
        });
    }
};
