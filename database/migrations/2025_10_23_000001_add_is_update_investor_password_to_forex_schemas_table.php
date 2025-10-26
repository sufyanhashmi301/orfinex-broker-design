<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->boolean('is_update_investor_password')->default(false)->after('is_update_trading_password');
        });
    }

    public function down(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->dropColumn('is_update_investor_password');
        });
    }
};


