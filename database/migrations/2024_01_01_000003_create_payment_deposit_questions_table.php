<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_deposit_questions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('fields'); // JSON field for form structure
            $table->boolean('status')->default(1); // Active/Inactive
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_deposit_questions');
    }
};
