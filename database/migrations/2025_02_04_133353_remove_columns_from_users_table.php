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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'balance',
                'profit_balance',
                'ib_login',
                'ib_balance',
                'ib_status',
                'is_multi_ib',
                'transfer_status',
                'auto_kyc_credentials',
                'kyc_level3_credential',
                'multi_ib_calc_at',
                'multi_ib_balance',
                'multi_ib_login',
                'kyc',
                'kyc_credential',
                'kyc_token',
                'kyc_created_at',
                'ref_id',
                'is_level_1_completed',
                'is_level_2_completed',
                'is_level_3_completed'
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

    }
};
