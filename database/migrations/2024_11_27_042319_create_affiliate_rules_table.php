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
        Schema::create('affiliate_rules', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->json('for_account_type_ids');
            // $table->unsignedBigInteger('count');
            $table->string('count_mode');
            $table->unsignedBigInteger('balance_retention_period');
            $table->text('description');
            $table->integer('has_levels');
            $table->integer('is_active');

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
        Schema::dropIfExists('affiliate_rules');
    }
};
