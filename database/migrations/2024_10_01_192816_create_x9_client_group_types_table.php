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
        Schema::create('x9_client_group_types', function (Blueprint $table) {
            $table->id();
//            $table->integer('client_group_type_id'); // To store the account type name like "DEMO" or "REAL"
            $table->string('name'); // To store the account type name like "DEMO" or "REAL"
            $table->string('description')->nullable(); // To store the description of the account type
            $table->boolean('is_visible')->default(true); // Boolean to manage visibility of the account type
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
        Schema::dropIfExists('x9_client_group_types');
    }
};
