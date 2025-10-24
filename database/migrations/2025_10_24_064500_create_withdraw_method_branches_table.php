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
        Schema::create('withdraw_method_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('withdraw_method_id');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('withdraw_method_id')->references('id')->on('withdraw_methods')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['withdraw_method_id', 'branch_id']);
            
            // Indexes for better performance
            $table->index('withdraw_method_id');
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
        Schema::dropIfExists('withdraw_method_branches');
    }
};
