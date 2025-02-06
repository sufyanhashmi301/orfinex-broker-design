<?php

use Illuminate\Support\Facades\DB;
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
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::dropIfExists('advertisement_materials');
        Schema::dropIfExists('advertisments');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customer_groups');
        Schema::dropIfExists('customer_group_has_customers');
        Schema::dropIfExists('custom_css');
        Schema::dropIfExists('forex_accounts');
        Schema::dropIfExists('forex_schema_investments');
        Schema::dropIfExists('ib_questions');
        Schema::dropIfExists('ib_question_answers');
        Schema::dropIfExists('ib_schemas');
        Schema::dropIfExists('ib_transactions');
        Schema::dropIfExists('invests');
        Schema::dropIfExists('iv_actions');
        Schema::dropIfExists('labels');
        Schema::dropIfExists('landing_contents');
        Schema::dropIfExists('landing_pages');
        Schema::dropIfExists('ledgers');
        Schema::dropIfExists('level_referrals');
        Schema::dropIfExists('meta_deals');
        Schema::dropIfExists('meta_transactions');
        Schema::dropIfExists('multi_levels');
        Schema::dropIfExists('multi_level_rebate_rule');
        Schema::dropIfExists('old_transactions');
        Schema::dropIfExists('profit_deductions');
        Schema::dropIfExists('rebate_rules');
        Schema::dropIfExists('rebate_rule_symbol_group');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('referral_links');
        Schema::dropIfExists('referral_programs');
        Schema::dropIfExists('referral_relationships');
        Schema::dropIfExists('referral_targets');
        Schema::dropIfExists('risk_profile_tags');
        Schema::dropIfExists('risk_profile_tags_users');
        Schema::dropIfExists('schemas');
        Schema::dropIfExists('swap_based_accounts');
        Schema::dropIfExists('swap_free_accounts');
        Schema::dropIfExists('symbols');
        Schema::dropIfExists('symbol_groups');
        Schema::dropIfExists('symbol_symbol_group');
        Schema::dropIfExists('user_imports');
        Schema::dropIfExists('user_metas');
        Schema::dropIfExists('user_trading_daily_stats');
        Schema::dropIfExists('x9_client_groups');
        Schema::dropIfExists('x9_client_group_types');
        Schema::dropIfExists('x9_operation_types');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
