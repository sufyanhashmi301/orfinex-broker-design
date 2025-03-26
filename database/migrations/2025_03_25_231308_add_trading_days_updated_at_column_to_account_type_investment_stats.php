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
        Schema::table('account_type_investment_stats', function (Blueprint $table) {
            $table->date('trading_days_updated_at')->after('trading_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_type_investment_stats', function (Blueprint $table) {
            $table->dropColumn('trading_days_updated_at');
        });
    }
};
