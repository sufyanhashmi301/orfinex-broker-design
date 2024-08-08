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
        Schema::create('swap_free_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_type_id')->constrained('forex_accounts');
            $table->string('title');
            $table->integer('level_order')->unique();
            $table->string('group_tag');
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('swap_free_accounts');
    }
};
