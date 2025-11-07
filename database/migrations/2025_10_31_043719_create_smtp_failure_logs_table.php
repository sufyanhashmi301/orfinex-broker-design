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
        Schema::create('smtp_failure_logs', function (Blueprint $table) {
            $table->id();
            $table->string('error_message');
            $table->string('error_code')->nullable();
            $table->string('email_template')->nullable();
            $table->string('recipient')->nullable();
            $table->json('shortcodes')->nullable();
            $table->text('stack_trace')->nullable();
            $table->json('context')->nullable();
            $table->timestamp('resent_at')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smtp_failure_logs');
    }
};
