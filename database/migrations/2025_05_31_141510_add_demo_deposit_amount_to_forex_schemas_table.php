<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDemoDepositAmountToForexSchemasTable extends Migration
{
    public function up()
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->decimal('demo_deposit_amount', 15, 2)
                  ->nullable()
                  ->after('demo_islamic');
        });
    }

    public function down()
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->dropColumn('demo_deposit_amount');
        });
    }
}