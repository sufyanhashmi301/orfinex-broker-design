
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIbGroupRebateRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ib_group_rebate_rule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ib_group_id')->constrained('ib_groups')->onDelete('cascade');
            $table->foreignId('rebate_rule_id')->constrained('rebate_rules')->onDelete('cascade');
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
        Schema::dropIfExists('ib_group_rebate_rule');
    }
}
