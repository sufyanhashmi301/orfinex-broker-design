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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code_name');              // Name of the discount code (e.g., BlackFriday2024)
            $table->string('code')->unique();         // Actual code (e.g., BF2024)
            $table->string('scheme_type')->nullable();  // Type of discount (percentage or fixed amount)
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');  // Type of discount (percentage or fixed amount)
            $table->string('applied_to')->nullable(); // Define where the discount applies (e.g., specific product, category)
            $table->integer('usage_limit')->default(1); // Max number of times this discount can be used
            $table->integer('used_count')->default(0);  // Times this discount has been used
            $table->decimal('percentage', 5, 2)->nullable(); // Percentage discount, if applicable
            $table->decimal('fixed_amount', 10, 2)->nullable(); // Fixed discount amount, if applicable
            $table->date('expire_at')->nullable(); // Expiry date of the discount code
            $table->boolean('status')->default(true);  // Active or inactive status
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
        Schema::dropIfExists('discounts');
    }
};
