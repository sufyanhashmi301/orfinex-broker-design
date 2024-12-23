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
        Schema::table('risk_rules', function (Blueprint $table) {
            $table->string('api_request_http_method', 10)->after('slug')->default('POST');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risk_rules', function (Blueprint $table) {
            $table->dropColumn('api_request_http_method');
        });
    }
};
