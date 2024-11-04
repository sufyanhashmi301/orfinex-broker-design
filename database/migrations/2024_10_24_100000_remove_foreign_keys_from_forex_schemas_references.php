<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forex_schema_phases', function (Blueprint $table) {
            $table->dropForeign('forex_schema_phases_forex_schema_id_foreign');
            $table->dropIndex('forex_schema_phases_forex_schema_id_foreign');
        });

        // Drop foreign keys and associated indexes from forex_schema_phase_rules table
        Schema::table('forex_schema_phase_rules', function (Blueprint $table) {
            $table->dropForeign('forex_schema_phase_rules_forex_schema_phase_id_foreign');
            $table->dropIndex('forex_schema_phase_rules_forex_schema_phase_id_foreign');
        });

        // Drop foreign keys and associated indexes from multi_levels table
        Schema::table('multi_levels', function (Blueprint $table) {
            $table->dropForeign('multi_levels_forex_scheme_id_foreign');
            $table->dropIndex('multi_levels_forex_scheme_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forex_schemas_references', function (Blueprint $table) {
            //
        });
    }
};
