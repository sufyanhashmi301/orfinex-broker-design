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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('actor_id');
            $table->string('actor_type');
            $table->string('action');
            $table->text('description')->nullable();
            $table->json('meta')->nullable();
            $table->string('ip');
            $table->string('location')->nullable();
            $table->string('agent')->nullable();
            $table->timestamps();

            $table->index(['actor_id', 'actor_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};
