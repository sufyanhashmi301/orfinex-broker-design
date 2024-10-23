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
        Schema::dropIfExists('bonus_user');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('bonus_user', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('bonus_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('transaction_id');

            $table->unsignedBigInteger('account_target_id');
            $table->string('account_target_type');

            $table->unsignedBigInteger('added_by')->nullable(); // self or by Admin
            $table->string('type'); // add or subtract. Also helps maintain the history of bonus additions/removals
            $table->decimal('amount', 10, 2); // amount added or subtracted. only when manually applied by admin

            $table->timestamps();
        });
    }
};
