<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->boolean('is_update_trading_password')->default(true)->after('is_cent_account');
        });
    }

    public function down()
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->dropColumn('is_update_trading_password');
        });
    }
};