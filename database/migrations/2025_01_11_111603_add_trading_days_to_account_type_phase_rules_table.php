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
        Schema::table('account_type_phase_rules', function (Blueprint $table) {
            $table->integer('trading_days')->nullable()->after('profit_target');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_type_phase_rules', function (Blueprint $table) {
            $table->dropColumn('trading_days');
        });
    }
};
