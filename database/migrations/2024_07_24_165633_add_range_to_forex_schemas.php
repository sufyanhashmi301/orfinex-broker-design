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
            $table->text('tags')->nullable()->after('country');
            $table->boolean('is_real_islamic')->default(0)->after('real_swap_free');
            $table->boolean('is_demo_islamic')->default(0)->after('demo_swap_free');
            $table->string('demo_server')->nullable()->after('demo_swap_free');
            $table->string('live_server')->nullable()->after('demo_server');
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
            $table->dropColumn('tags');
            $table->dropColumn('is_real_islamic');
            $table->dropColumn('is_demo_islamic');
            $table->dropColumn('is_demo_islamic');
            $table->dropColumn('demo_server');
            $table->dropColumn('live_server');
        });
    }
};
