<?php

use App\Enums\FundedSchemeTypes;
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
        Schema::create('forex_schema_phases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forex_schema_id')->constrained();
            $table->integer('phase')->default(1);
            $table->string('group');
            $table->enum('type', [
                FundedSchemeTypes::CHALLENGE_PHASE,
                FundedSchemeTypes::FUNDED_PHASE,
                FundedSchemeTypes::DIRECT_FUNDING,
            ]);
            $table->integer('validity_count')->default(0);
            $table->string('term_type')->nullable();
            $table->string('server')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Adding soft delete column

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forex_schema_phases');
    }
};
