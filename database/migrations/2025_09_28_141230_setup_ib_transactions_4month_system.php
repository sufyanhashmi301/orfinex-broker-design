<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create initial 4-month based IB transactions tables for current and next periods
        $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
        $nextPeriod = IBTransactionPeriodService::getNextPeriod($currentPeriod);
        
        $this->createIBTransactionTable($currentPeriod);
        $this->createIBTransactionTable($nextPeriod);
        
        // Also create table for previous period if it doesn't exist (for data migration)
        $previousPeriod = IBTransactionPeriodService::getPreviousPeriod($currentPeriod);
        $this->createIBTransactionTable($previousPeriod);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Get all possible periods for current year and previous year
        $currentYear = Carbon::now()->year;
        $previousYear = $currentYear - 1;
        
        $periods = array_merge(
            IBTransactionPeriodService::getYearPeriods($currentYear),
            IBTransactionPeriodService::getYearPeriods($previousYear)
        );
        
        foreach ($periods as $period) {
            $tableName = IBTransactionPeriodService::getTableName($period);
            Schema::dropIfExists($tableName);
        }
    }
    
    /**
     * Create IB transaction table for a specific period
     *
     * @param string $period
     * @return void
     */
    private function createIBTransactionTable($period)
    {
        $tableName = IBTransactionPeriodService::getTableName($period);
        
        if (!Schema::hasTable($tableName)) {
            if (Schema::hasTable('ib_transactions_template')) {
                DB::statement("CREATE TABLE {$tableName} LIKE ib_transactions_template");
            } else {
                // Fallback: create table with basic structure if template doesn't exist
                Schema::create($tableName, function (Blueprint $table) {
                    $table->id();
                    $table->unsignedInteger('user_id');
                    $table->unsignedInteger('from_user_id')->nullable();
                    $table->string('from_model', 255)->default('User');
                    $table->unsignedBigInteger('target_id')->nullable();
                    $table->string('target_type', 256)->nullable();
                    $table->boolean('is_level')->default(false);
                    $table->string('tnx', 255)->unique();
                    $table->string('description', 255)->nullable();
                    $table->string('amount', 255);
                    $table->string('type', 255);
                    $table->string('charge', 255)->default('0');
                    $table->string('final_amount', 255)->default('0');
                    $table->string('method', 255)->nullable();
                    $table->string('pay_currency', 256)->nullable();
                    $table->double('pay_amount')->nullable();
                    $table->text('manual_field_data')->nullable();
                    $table->text('approval_cause')->nullable();
                    $table->unsignedInteger('action_by')->nullable();
                    $table->string('status', 255);
                    $table->timestamps();
                });
            }
        }
    }
};
