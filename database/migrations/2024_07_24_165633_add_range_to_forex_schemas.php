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
            $table->text('is_real_islamic')->default(0)->after('real_swap_free');
            $table->text('is_demo_islamic')->default(0)->after('demo_swap_free');

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

        });
    }
};
