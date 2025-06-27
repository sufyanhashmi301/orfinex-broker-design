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
    public function up(): void
    {
        Schema::create('deposit_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code')->unique();
            $table->double('amount', 15, 2);
            $table->dateTime('expiry_date');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'used', 'expired'])->default('active');
            $table->unsignedBigInteger('used_by')->nullable();
            $table->string('modal')->nullable();
            $table->dateTime('used_date')->nullable();
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
        Schema::dropIfExists('deposit_vouchers');
    }
};
