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
        Schema::create('ib_transactions_template', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('from_user_id')->nullable();
            $table->string('from_model', 255)->default('User');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('target_type', 256)->nullable();
            $table->boolean('is_level')->default(false);
            $table->string('tnx', 255)->unique();
            $table->string('description', 255)->nullable();
            $table->string('amount', 255);
            $table->string('type', 255);
            $table->string('charge', 255)->default('0');
            $table->string('final_amount', 255)->default('0');
            $table->string('method', 255)->nullable();
            $table->string('pay_currency', 256)->nullable();
            $table->double('pay_amount')->nullable();
            $table->text('manual_field_data')->nullable();
            $table->text('approval_cause')->nullable();
            $table->unsignedInteger('action_by')->nullable();
            $table->string('status', 255);
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
        Schema::dropIfExists('ib_transactions_template');
    }
};
