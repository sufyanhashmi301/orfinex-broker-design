<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();  // Automatically creates an 'id' column as primary key.
            $table->unsignedBigInteger('transaction_id')->unique();  // Assuming 'transaction_id' is a foreign key.
            $table->unsignedBigInteger('account_id');  // Assuming 'account_id' is a foreign key.
            $table->double('debit')->nullable();  // Assuming 'debit' may need to store monetary values.
            $table->double('credit')->nullable();  // Assuming 'credit' may need to store monetary values.
            $table->double('balance');  // Assuming 'balance' may need to store monetary values.
            $table->timestamps();  // Automatically creates 'created_at' and 'updated_at' columns.
        });

        // Optional: Define foreign key constraints if applicable.
        Schema::table('ledgers', function (Blueprint $table) {
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledgers');
    }
}
