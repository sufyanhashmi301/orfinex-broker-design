<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_ib_rule_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');
            $table->foreignId('user_ib_rule_id')->constrained('user_ib_rules')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_ib_rule_levels');
    }
};
