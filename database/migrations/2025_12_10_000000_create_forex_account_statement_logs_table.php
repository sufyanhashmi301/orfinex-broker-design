<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forex_account_statement_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('forex_account_id');
            $table->string('account_login', 50)->index();
            $table->string('user_email')->index();
            $table->date('statement_date')->index();
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('error_message')->nullable();
            $table->integer('pdf_size')->nullable(); // PDF file size in bytes
            $table->timestamp('sent_at');
            $table->timestamps();

            // Indexes for performance (with custom short names)
            $table->index(['statement_date', 'status'], 'idx_date_status');
            $table->index(['forex_account_id', 'statement_date'], 'idx_account_date');
            
            // Unique constraint to prevent duplicate logs for same account/date
            $table->unique(['forex_account_id', 'statement_date'], 'unq_account_date');
            
            // Foreign key constraint
            $table->foreign('forex_account_id')->references('id')->on('forex_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forex_account_statement_logs');
    }
};