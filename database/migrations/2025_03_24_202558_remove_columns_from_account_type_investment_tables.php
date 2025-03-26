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
            $table->dropColumn([
                'account_name',
                'platform_group',
                'prev_day_balance',
                'prev_day_equity',
                'today_pnl_realized',
                'today_pnl_unrealized',
                'total_pnl',
                'max_balance',
                'credit',
            ]);
        });

        Schema::table('account_type_investment_hourly_stats_records', function (Blueprint $table) {
            $table->dropColumn([
                'account_name',
                'platform_group',
                'prev_day_balance',
                'prev_day_equity',
                'today_pnl_realized',
                'today_pnl_unrealized',
                'total_pnl',
                'max_balance',
                'credit',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_type_investment_tables', function (Blueprint $table) {
            //
        });
    }
};
