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
        Schema::create('account_type_investments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('account_type_phase_rule_id');

            $table->string('account_name')->nullable();
            $table->string('trader_type');
            $table->string('login')->nullable();
            $table->string('platform_group')->nullable();

            $table->decimal('total', 8, 2);

            $table->dateTime('phase_started_at')->nullable();
            $table->dateTime('phase_ended_at')->nullable();

            $table->string('currency');
            $table->string('main_password')->nullable();
            $table->string('status');


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
        Schema::dropIfExists('account_type_investments');
    }
};
