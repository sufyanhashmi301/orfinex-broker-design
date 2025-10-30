<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->double('demo_min_deposit_amount')->nullable()->after('demo_deposit_amount');
            $table->double('demo_max_deposit_amount')->nullable()->after('demo_min_deposit_amount');
        });
    }

    public function down(): void
    {
        Schema::table('forex_schemas', function (Blueprint $table) {
            $table->dropColumn(['demo_min_deposit_amount', 'demo_max_deposit_amount']);
        });
    }
};


