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
        Schema::create('kyc_level_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kyc_level_id')->constrained('kyc_levels');
            $table->foreignId('kyc_id')->nullable()->constrained('kycs');
            $table->string('title');
            $table->string('unique_code');
            $table->text('description')->nullable();
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('kyc_level_settings');
    }
};
