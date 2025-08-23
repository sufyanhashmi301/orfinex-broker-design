<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action', 100)->index();
            $table->string('model', 100)->nullable()->index();
            $table->unsignedBigInteger('model_id')->nullable()->index();
            $table->unsignedBigInteger('admin_id')->nullable()->index();
            $table->json('changes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at');

            // Composite indexes for common queries
            $table->index(['model', 'model_id']);
            $table->index(['admin_id', 'created_at']);
            $table->index(['action', 'created_at']);
            
            // Foreign key constraints
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
