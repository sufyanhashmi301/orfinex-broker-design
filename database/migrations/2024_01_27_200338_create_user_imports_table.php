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
        Schema::create('user_imports', function (Blueprint $table) {
            $table->id();
            $table->string('login')->nullable();
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('m_name')->nullable();
            $table->string('group')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('leverage')->nullable();
            $table->string('register_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_imports');
    }
};
