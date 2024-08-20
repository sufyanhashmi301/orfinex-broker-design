<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelToReferralLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_links', function (Blueprint $table) {
            $table->integer('level')->default(0); // Adds an integer column with a default value
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_links', function (Blueprint $table) {
            $table->dropColumn('level'); // Removes the column if the migration is rolled back
        });
    }
}
