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
        Schema::table('funded_balances', function (Blueprint $table) {
            $table->decimal('payout_pending', 8, 2)->default(0)->after('last_retrieved_profit'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('funded_balances', function (Blueprint $table) {
            $table->dropColumn('payout_pending');
        });
    }
};
