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
        Schema::create('forex_schema_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('forex_schema_id');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('forex_schema_id')->references('id')->on('forex_schemas')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            // Unique constraint to prevent duplicate assignments
            $table->unique(['forex_schema_id', 'branch_id']);

            // Indexes for performance
            $table->index('forex_schema_id');
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forex_schema_branches');
    }
};
