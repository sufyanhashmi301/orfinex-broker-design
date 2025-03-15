<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForexSchemaIdToReferralRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('referral_relationships', function (Blueprint $table) {
            $table->unsignedBigInteger('forex_schema_id')->nullable()->after('multi_level_id');
            // Replace 'existing_column_name' with the name of the column after which you want to add this column.

            // If this column references another table, you can add a foreign key:
            // $table->foreign('forex_schema_id')->references('id')->on('forex_schemas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('referral_relationships', function (Blueprint $table) {
            $table->dropColumn('forex_schema_id');
        });
    }
}
