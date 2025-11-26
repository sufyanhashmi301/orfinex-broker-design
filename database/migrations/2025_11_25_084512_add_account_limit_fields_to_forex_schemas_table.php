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
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->integer('live_account_limit')->default(0)->after('real_islamic');
            $table->integer('demo_account_limit')->default(0)->after('demo_islamic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->dropColumn(['live_account_limit', 'demo_account_limit']);
        });
    }
};
