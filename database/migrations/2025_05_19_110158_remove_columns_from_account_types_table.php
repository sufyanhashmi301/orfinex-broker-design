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
        Schema::table('account_types', function (Blueprint $table) {
            $table->dropColumn([
                'upto_allotted_fund',
                'upto_profit_target',
                'upto_daily_max_loss',
                'upto_maximum_loss',
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
        Schema::table('account_types', function (Blueprint $table) {
            $table->string('upto_allotted_fund')->nullable();
            $table->string('upto_profit_target')->nullable();
            $table->string('upto_daily_max_loss')->nullable();
            $table->string('upto_maximum_loss')->nullable();
        });
    }
};
