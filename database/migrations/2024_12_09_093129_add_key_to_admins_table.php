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
    Schema::table('admins', function (Blueprint $table) {
        $table->string('key')->nullable()->after('password'); // Add the key column
    });
}

public function down()
{
    Schema::table('admins', function (Blueprint $table) {
        $table->dropColumn('key');
    });
}

};
