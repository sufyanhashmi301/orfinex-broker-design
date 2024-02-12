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
            $table->string('multi_ib_login')->nullable()->after('ib_status');
            $table->double('multi_ib_balance')->default(0.00)->after('ib_status');
            $table->tinyInteger('is_multi_ib')->default(0)->after('ib_status')->nullable();
            $table->timestamp('multi_ib_calc_at')->after('ib_status')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('multi_ib_login');
            $table->dropColumn('multi_ib_balance');
            $table->dropColumn('is_multi_ib');
            $table->dropColumn('multi_ib_calc_at');
        });
    }
};
