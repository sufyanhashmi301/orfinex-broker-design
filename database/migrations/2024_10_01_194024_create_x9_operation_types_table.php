<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateX9OperationTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('x9_operation_types', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
//            $table->unsignedBigInteger('operation_id'); // Foreign key or reference for operation type
            $table->string('name'); // Operation type name (e.g., "Deposit", "Withdrawal")
            $table->unsignedBigInteger('operation_type_id'); // Foreign key or reference for operation type
            $table->string('operation_type_name'); // Operation type name (e.g., "Balance", "Bonus", etc.)
            $table->timestamps(); // Automatically manages created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('x9_operation_types');
    }
}
