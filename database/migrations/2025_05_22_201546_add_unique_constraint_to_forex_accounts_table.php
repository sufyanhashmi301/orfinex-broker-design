<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToForexAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('forex_accounts', function (Blueprint $table) {
            $table->unique(['login', 'trader_type'], 'login_trader_type_unique');
        });
    }

    public function down()
    {
        Schema::table('forex_accounts', function (Blueprint $table) {
            $table->dropUnique('login_trader_type_unique');
        });
    }
}
