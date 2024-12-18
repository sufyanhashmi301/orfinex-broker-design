<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForexSchemaRebateRuleTable extends Migration
{
    public function up()
    {
        Schema::create('forex_schema_rebate_rule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rebate_rule_id')->constrained('rebate_rules')->onDelete('cascade');
            $table->foreignId('forex_schema_id')->constrained('forex_schemas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('forex_schema_rebate_rule');
    }
}
