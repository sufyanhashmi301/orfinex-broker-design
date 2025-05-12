<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCentBonusToForexSchemasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->boolean('is_cent_bonus')->default(false)->after('is_bonus'); // Replace with actual column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->dropColumn('is_cent_bonus');
        });
    }
}

