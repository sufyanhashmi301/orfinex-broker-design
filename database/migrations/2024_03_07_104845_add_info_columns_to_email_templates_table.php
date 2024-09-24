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
        Schema::table('email_templates', function (Blueprint $table) {
            $table->text('note')->nullable()->after('short_codes');
            $table->string('support_link')->nullable()->after('note');
            $table->text('warning_content')->nullable()->after('support_link');
            $table->text('company_info')->nullable()->after('warning_content');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->dropColumn('support_link');
            $table->dropColumn('warning_content');
            $table->dropColumn('company_info');
        });
    }
};
