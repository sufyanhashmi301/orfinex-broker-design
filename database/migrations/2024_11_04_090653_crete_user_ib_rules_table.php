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
        Schema::create('user_ib_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('ib_group_id');
            $table->foreignId('rebate_rule_id');
            $table->double('sub_ib_share')->default(0);//in percentage
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
        Schema::dropIfExists('user_ib_rules');

    }
};
