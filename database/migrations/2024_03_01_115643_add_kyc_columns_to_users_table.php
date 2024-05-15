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
            $table->text('kyc_token')->nullable()->after('kyc_credential');
            $table->string('applicant_id')->nullable()->after('kyc_token');
            $table->timestamp('kyc_created_at')->nullable()->after('applicant_id');
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
            $table->dropColumn('kyc_token');
            $table->dropColumn('kyc_created_at');
        });
    }
};
