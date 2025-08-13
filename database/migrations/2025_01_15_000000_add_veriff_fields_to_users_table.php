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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kyc_provider')) {
                $table->string('kyc_provider')->after('kyc_created_at')->nullable()
                    ->comment('KYC provider: sumsub, veriff, manual');
            }
            
            if (!Schema::hasColumn('users', 'kyc_session_id')) {
                $table->string('kyc_session_id')->after('kyc_provider')->nullable()
                    ->comment('Provider-specific session/verification ID');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'kyc_provider',
                'kyc_session_id',
            ]);
        });
    }
};
