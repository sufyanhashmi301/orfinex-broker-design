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
        Schema::table('admin_2fa_codes', function (Blueprint $table) {
            $table->integer('resend_attempts')->default(0)->after('used');
            $table->timestamp('restricted_until')->nullable()->after('resend_attempts');
            
            $table->index(['admin_id', 'restricted_until']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_2fa_codes', function (Blueprint $table) {
            $table->dropIndex(['admin_id', 'restricted_until']);
            $table->dropColumn(['resend_attempts', 'restricted_until']);
        });
    }
};
