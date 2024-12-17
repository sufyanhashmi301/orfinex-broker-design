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
        Schema::create('risk_rules', function (Blueprint $table) {
            $table->id();


            $table->string('title');
            $table->string('slug');
            $table->text('api_endpoint');
            $table->json('data');
            $table->json('criteria');
            $table->date('data_from');
            $table->date('data_to');

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
        Schema::dropIfExists('risk_rules');
    }
};
