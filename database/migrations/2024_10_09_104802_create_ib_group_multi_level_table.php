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
        Schema::create('ib_group_multi_level', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ib_group_id')->constrained('ib_groups')->onDelete('cascade');
            $table->foreignId('multi_level_id')->constrained('multi_levels')->onDelete('cascade');
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
        Schema::dropIfExists('ib_group_multi_level');
    }
};
