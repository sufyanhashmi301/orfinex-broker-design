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
            $table->bigInteger('start_range')->nullable()->after('is_bonus');
            $table->bigInteger('end_range')->nullable()->after('start_range');
            $table->text('tag')->nullable()->after('country');

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
            $table->dropColumn('start_range');
            $table->dropColumn('end_range');
            $table->dropColumn('tag');

        });
    }
};
