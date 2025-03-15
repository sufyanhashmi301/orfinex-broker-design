<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateX9ClientGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('x9_client_groups', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('client_group_type_id'); // Foreign key for client group type
//            $table->unsignedBigInteger('client_group_id'); // Foreign key for client group type
            $table->string('name'); // Name of the client group
            $table->string('currency'); // Currency type (e.g., USD)
            $table->string('type'); // Group type (e.g., REAL or DEMO)
            $table->timestamps(); // Automatically adds created_at and updated_at fields



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('x9_client_groups');
    }
}
