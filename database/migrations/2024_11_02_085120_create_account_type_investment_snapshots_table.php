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
        Schema::create('account_type_investment_snapshots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_type_investment_id');

            $table->json('account_types_data');
            $table->json('account_types_phases_data');
            $table->json('account_types_phases_rules_data');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_type_investment_snapshots');
    }
};
