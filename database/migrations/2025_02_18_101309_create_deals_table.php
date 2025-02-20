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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('lead_pipeline_id');
            $table->unsignedBigInteger('pipeline_stage_id');
            $table->unsignedBigInteger('lead_id');
            $table->date('close_date');
            $table->decimal('value', 10, 2);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('added_by');
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
        Schema::dropIfExists('deals');
    }
};
