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
        Schema::table('groups', function (Blueprint $table) {
            // Make group_id nullable
            $table->unsignedBigInteger('group_id')->nullable()->change();

            // Add new columns after currencyDigits
            $table->string('trader_type')->nullable()->after('currencyDigits');
            $table->string('source_type')->nullable()->after('trader_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Revert changes in the down method
            $table->unsignedBigInteger('group_id')->nullable(false)->change();
            $table->dropColumn(['trader_type', 'source_type']);
        });
    }
};
