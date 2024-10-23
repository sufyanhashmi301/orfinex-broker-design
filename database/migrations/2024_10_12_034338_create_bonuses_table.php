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
        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();

            $table->string('kyc_slug');
            $table->string('bonus_name');
            
            $table->date('start_date');
            $table->date('last_date');
            
            $table->string('type');
            $table->string('amount');

            $table->string('process');

            $table->string('bonus_removal_type');
            $table->string('bonus_removal_amount')->nullable();

            $table->string('applicable_by');
            
            $table->text('terms_link');
            $table->text('description');
            
            $table->string('first_or_every_deposit');

            $table->integer('status')->default(0);

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
        Schema::dropIfExists('bonuses');
    }
};
