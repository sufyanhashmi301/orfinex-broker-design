<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOldTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('from_user_id')->nullable();
            $table->string('from_model', 191)->default('User');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('target_type', 191)->nullable();
            $table->boolean('is_level')->default(false);
            $table->string('tnx', 191)->unique();
            $table->string('description', 191)->nullable();
            $table->string('amount', 191);
            $table->string('type', 191);
            $table->string('charge', 191)->default('0');
            $table->string('final_amount', 191)->default('0');
            $table->string('method', 191)->nullable();
            $table->string('pay_currency', 191)->nullable();
            $table->double('pay_amount')->nullable();
            $table->text('manual_field_data')->nullable();
            $table->text('approval_cause')->nullable();
            $table->string('status', 191);
            $table->timestamps();

            // Separate indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('target_id');
            $table->index('target_type');
            $table->index('type');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('old_transactions');
    }
}
