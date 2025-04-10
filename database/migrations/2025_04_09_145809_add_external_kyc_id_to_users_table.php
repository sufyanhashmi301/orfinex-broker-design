<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExternalKycIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'external_kyc_id')) {
                $table->string('external_kyc_id')->after('applicant_id')->nullable()->unique();
            }

            if (!Schema::hasColumn('users', 'auto_kyc_credentials')) {
                $table->json('auto_kyc_credentials')->after('external_kyc_id')->nullable();
            }

            if (!Schema::hasColumn('users', 'kyc_token')) {
                $table->string('kyc_token')->after('auto_kyc_credentials')->nullable();
            }

            if (!Schema::hasColumn('users', 'kyc_created_at')) {
                $table->timestamp('kyc_created_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'external_kyc_id',
                'auto_kyc_credentials',
                'kyc_token',
                'kyc_created_at',
            ]);
        });
    }
}
