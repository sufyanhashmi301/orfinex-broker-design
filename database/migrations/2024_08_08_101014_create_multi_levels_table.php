<?php

use App\Enums\MultiLevelType;
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
        Schema::create('multi_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forex_scheme_id')->constrained('forex_schemas'); // This also creates an unsigned big integer
            $table->string('type')->default(MultiLevelType::SWAP);
            $table->string('title');
            $table->integer('level_order');
            $table->string('group_tag');
            $table->text('description')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('multi_levels');
    }
};
