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
        Schema::create('error_pages', function (Blueprint $table) {
            $table->id();
            
            // Basic identification
            $table->string('name');
            $table->string('type')->unique()->comment('withdraw_disabled, withdraw_off_day, deposit_disabled, send_money_disabled');
            
            // Content fields
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('message')->nullable();

            // Button
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('button_type')->nullable()->default('primary')->comment('primary, secondary, outline-dark');
            
            // Metadata
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
        Schema::dropIfExists('error_pages');
    }
};

