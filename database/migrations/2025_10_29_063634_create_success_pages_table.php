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
        Schema::create('success_pages', function (Blueprint $table) {
            $table->id();
            
            // Basic identification
            $table->string('name');
            $table->string('type');
            
            // Content fields
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('message')->nullable();
            $table->text('quote')->nullable();
            $table->string('quote_author')->nullable();
            
            // Visual elements
            $table->string('image_path')->nullable();
            
            // Action buttons
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('button_type')->nullable()->default('primary')->comment('primary, secondary, outline-dark');
            $table->boolean('trustpilot_button_show')->default(false);
            
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
        Schema::dropIfExists('success_pages');
    }
};
