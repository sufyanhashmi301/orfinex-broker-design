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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('account_type_investment_id');
            $table->string('transaction_id_string')->nullable();
            $table->double('package_price', 2);
            $table->double('package_discount', 2);
            $table->json('coupon_code_discount');
            $table->json('addon');
            $table->double('total_amount', 2);
            // $table->string('status'); // pending, rejected, paid

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
        Schema::dropIfExists('invoices');
    }
};
