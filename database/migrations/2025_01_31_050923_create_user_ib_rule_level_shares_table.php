<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_ib_rule_level_shares', function (Blueprint $table) {
            $table->id(); // Regular primary key
            $table->foreignId('user_ib_rule_level_id')->constrained('user_ib_rule_levels')->onDelete('cascade'); // Foreign Key
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');
            $table->decimal('share', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_ib_rule_level_shares');
    }
};
