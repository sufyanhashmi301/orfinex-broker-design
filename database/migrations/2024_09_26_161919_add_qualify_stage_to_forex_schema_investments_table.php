<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQualifyStageToForexSchemaInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forex_schema_investments', function (Blueprint $table) {
            $table->string('qualify_stage')->default(1)->after('status');
            $table->timestamp('equity_cal_at')->nullable()->after('snap_floating');
            $table->timestamp('violated_at')->nullable()->after('drawdown_reason');
            $table->timestamp('daily_score_record_at')->nullable()->after('pay_detail');
            $table->timestamp('weekly_score_record_at')->nullable()->after('daily_score_record_at');
            $table->timestamp('total_score_record_at')->nullable()->after('weekly_score_record_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forex_schema_investments', function (Blueprint $table) {
            $table->dropColumn('qualify_stage');
            $table->dropColumn('equity_cal_at');
            $table->dropColumn('violated_at');
            $table->dropColumn('daily_score_record_at');
            $table->dropColumn('weekly_score_record_at');
            $table->dropColumn('total_score_record_at');
        });
    }
}
