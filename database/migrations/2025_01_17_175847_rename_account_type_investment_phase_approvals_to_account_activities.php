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
        Schema::table('account_activities', function (Blueprint $table) {
            Schema::rename('account_type_investment_phase_approvals', 'account_activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_activities', function (Blueprint $table) {
            Schema::rename('account_activities', 'account_type_investment_phase_approvals');
        });
    }
};
