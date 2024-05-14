<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_investments', function (Blueprint $table) {
            $table->id();
            $table->string('pvx');
            $table->foreignId('user_id');
            $table->foreignId('pricing_scheme_id');
            $table->foreignId('account_type_id')->nullable();
            $table->string('account_name')->nullable();
            $table->string('login')->nullable();
            $table->string('group')->nullable();
            $table->double('current_balance')->default(0);
            $table->double('current_equity')->default(0);
            $table->string('account_type');
            $table->double('amount')->default(0);
            $table->double('amount_allotted')->default(0);
            $table->double('discount')->default(0);
            $table->double('leverage_amount')->default(0);
            $table->double('days_to_pass_amount')->default(0);
            $table->double('profit_split_amount')->default(0);
            $table->double('payout_amount')->default(0);
            $table->double('swap_free_amount')->default(0);
            $table->double('total')->default(0);
            $table->double('profit')->default(0);
            $table->double('received')->default(0);
            $table->string('currency');
            $table->integer('term_count')->default(0);
            $table->integer('term_total')->default(0);
            $table->integer('term_calc')->nullable();
            $table->dateTime('term_start');
            $table->dateTime('term_end')->nullable();
            $table->longText('scheme')->nullable();
            $table->longText('meta')->nullable();
            $table->text('desc')->nullable();
            $table->string('platform')->nullable();
            $table->integer('leverage')->default(0);
            $table->string('days_to_pass');
            $table->integer('profit_share_user')->default(0);
            $table->integer('profit_share_admin')->default(0);
            $table->string('payouts');
            $table->double('max_drawdown_limit')->default(0);
            $table->double('daily_drawdown_limit')->default(0);
            $table->double('snap_balance')->default(0);
            $table->double('snap_equity')->default(0);
            $table->double('snap_floating')->default(0);
            $table->double('max_balance')->default(0);
            $table->string('main_password')->nullable();
            $table->string('invest_password')->nullable();
            $table->string('phone_password')->nullable();
            $table->string('status');
            $table->text('drawdown_reason')->nullable();
            $table->string('pay_from')->nullable();
            $table->text('pay_detail')->nullable();
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
        Schema::dropIfExists('pricing_investments');
    }
}
