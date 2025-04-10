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
        Schema::create('account_balance_operations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('account_type_investment_id');
            $table->decimal('amount', 8);
            $table->string('operation');
            $table->integer('affect_stats');
            $table->text('comments');

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
        Schema::dropIfExists('account_balance_operations');
    }
};
