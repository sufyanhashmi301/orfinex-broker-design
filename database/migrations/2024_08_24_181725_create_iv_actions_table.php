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
        Schema::create('iv_actions', function (Blueprint $table) {
            $table->id(); // Adds an auto-incrementing ID column
            $table->string('action');
            $table->timestamp('action_at')->nullable(); // Allows NULL values
            $table->unsignedBigInteger('action_by'); // Assuming 'action_by' is a foreign key
            $table->string('type');
            $table->unsignedBigInteger('type_id'); // Assuming 'type_id' is a foreign key
            $table->timestamps(); // Adds 'created_at' and 'updated_at' columns

            // Add foreign key constraints if necessary
            // $table->foreign('action_by')->references('id')->on('users');
            // $table->foreign('type_id')->references('id')->on('types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iv_actions');
    }
};
