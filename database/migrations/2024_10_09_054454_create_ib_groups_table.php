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
        Schema::create('ib_groups', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Name of the IB group
            $table->text('desc')->nullable(); // Optional description field
            $table->tinyInteger('status')->default(1); // Status (1 for active, 0 for inactive)
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ib_groups');
    }
};
