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
        Schema::table('user_otps', function (Blueprint $table) {
            if (!Schema::hasColumn('user_otps', 'failed_attempts')) {
                $table->integer('failed_attempts')->default(0)->after('verified');
            }
            if (!Schema::hasColumn('user_otps', 'restricted_until')) {
                $table->timestamp('restricted_until')->nullable()->after('failed_attempts');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_otps', function (Blueprint $table) {
            $table->dropColumn(['failed_attempts', 'restricted_until']);
        });
    }
}; 