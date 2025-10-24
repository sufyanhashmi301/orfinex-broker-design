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
        Schema::create('deposit_method_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deposit_method_id');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('deposit_method_id')->references('id')->on('deposit_methods')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['deposit_method_id', 'branch_id']);
            
            // Indexes for better performance
            $table->index('deposit_method_id');
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
        Schema::dropIfExists('deposit_method_branches');
    }
};
