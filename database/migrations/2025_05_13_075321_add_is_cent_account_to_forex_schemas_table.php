<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsCentAccountToForexSchemasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->boolean('is_cent_account')->default(false)->after('is_bonus'); // Adjust position if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->dropColumn('is_cent_account');
        });
    }
}
