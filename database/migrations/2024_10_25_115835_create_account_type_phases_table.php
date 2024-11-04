<?php

use App\Enums\AccountTypePhase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_type_phases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_type_id');
            $table->integer('phase_step')->default(1);
            $table->enum('type', [
                AccountTypePhase::EVALUATION,
                AccountTypePhase::VERIFICATION,
                AccountTypePhase::FUNDED,
            ]);
            $table->integer('validity_period')->default(0);
            $table->string('term_type')->nullable();
            $table->string('server')->nullable();
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
        Schema::dropIfExists('account_type_phases');
    }
};
