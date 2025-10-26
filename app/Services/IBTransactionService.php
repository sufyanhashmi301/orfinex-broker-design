<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\IBTransactionPeriodService;
use App\Enums\TxnType;
use App\Enums\TxnStatus;

class IBTransactionService
{
    public static function createIBTransactionTable($year)
    {
        $table = 'ib_transactions_' . $year;

        if (Schema::hasTable($table)) {
            return true;
        }

        if (!Schema::hasTable('ib_transactions_template')) {
            return false;
        }

        try {
            DB::statement("CREATE TABLE {$table} LIKE ib_transactions_template");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Create IB transaction table for 4-month period
     *
     * @param string $period
     * @return bool
     */
    public static function createIBTransactionTable4Month($period)
    {
        $table = IBTransactionPeriodService::getTableName($period);

        if (Schema::hasTable($table)) {
            return true;
        }

        if (!Schema::hasTable('ib_transactions_template')) {
            return false;
        }

        try {
            DB::statement("CREATE TABLE {$table} LIKE ib_transactions_template");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function new($amount, $charge, $finalAmount, $method, $description, string|TxnType $type, string|TxnStatus $status, $currency = null, $payAmount = null, $userId = null, $fromUserId = null, $relatedModel = 'User', array $manualFieldData = [], string $approvalCause = 'none', $targetId = null, $targetType = null, $isLevel = false, $transactionDate = null)
    {
        // dd($transactionDate);
        // Use provided transaction date or current date
        $transactionDate = $transactionDate ? Carbon::parse($transactionDate) : Carbon::now();
        $currentPeriod = IBTransactionPeriodService::getCurrentPeriod($transactionDate);
        $tableName = IBTransactionPeriodService::getTableName($currentPeriod);
        // dd($tableName);

        // // Create the 4-month period table if it doesn't exist
        // if (!self::createIBTransactionTable4Month($currentPeriod)) {
        //     throw new \Exception("Failed to create IB transactions table for period {$currentPeriod}");
        // }

        // // Auto-create next quarter table if we're in the last month of current period
        // if (IBTransactionPeriodService::shouldCreateNextPeriodTable($transactionDate)) {
        //     try {
        //         $nextPeriod = IBTransactionPeriodService::getNextPeriod($currentPeriod);
        //         self::createIBTransactionTable4Month($nextPeriod);
        //     } catch (\Exception $e) {
        //         // Log but don't fail - next period table creation is not critical
        //         \Log::warning("Failed to create next period table: " . $e->getMessage());
        //     }
        // }

        $tnx = uniqid('IBX');
        // dd($tnx);

        // Use database transaction to ensure data consistency
        DB::transaction(function () use ($tableName, $userId, $fromUserId, $relatedModel, $targetId, $targetType, $isLevel, $tnx, $description, $amount, $type, $charge, $finalAmount, $method, $currency, $payAmount, $manualFieldData, $approvalCause, $status, $transactionDate) {
            // Store transaction in 4-month period table
            DB::table($tableName)->insert([
                'user_id' => $userId,
                'from_user_id' => $fromUserId,
                'from_model' => $relatedModel,
                'target_id' => $targetId,
                'target_type' => $targetType,
                'is_level' => $isLevel,
                'tnx' => $tnx,
                'description' => $description,
                'amount' => $amount,
                'type' => $type,
                'charge' => $charge,
                'final_amount' => $finalAmount,
                'method' => $method,
                'pay_currency' => $currency,
                'pay_amount' => $payAmount,
                'manual_field_data' => json_encode($manualFieldData),
                'approval_cause' => $approvalCause,
                'status' => $status,
                'created_at' => $transactionDate->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);

        });

        return (object)[
            'tnx' => $tnx,
            'amount' => $amount,
            'type' => $type->value,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'user_id' => $userId,
            'from_user_id' => $fromUserId
        ];
    }

    public static function update($tnx, $status, $userId = null, $approvalCause = 'none')
    {
        $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
        $tableName = IBTransactionPeriodService::getTableName($currentPeriod);

        return DB::table($tableName)
            ->where('tnx', $tnx)
            ->where('user_id', $userId)
            ->update([
                'status' => $status,
                'approval_cause' => $approvalCause,
                'updated_at' => now()
            ]);
    }

    public static function isDuplicate($userId, $fromUserId, $description, $amount)
    {
        $currentPeriod = IBTransactionPeriodService::getCurrentPeriod();
        // dd($currentPeriod);
        $tableName = IBTransactionPeriodService::getTableName($currentPeriod);

        return DB::table($tableName)
            ->where('user_id', $userId)
            ->where('from_user_id', $fromUserId)
            ->where('description', $description)
            ->where('amount', $amount)
            ->exists();
    }

    /**
     * Update transaction in specific period table
     *
     * @param string $tnx
     * @param string $status
     * @param string $period
     * @param int|null $userId
     * @param string $approvalCause
     * @return int
     */
    public static function updateInPeriod($tnx, $status, $period, $userId = null, $approvalCause = 'none')
    {
        $tableName = IBTransactionPeriodService::getTableName($period);

        $query = DB::table($tableName)->where('tnx', $tnx);
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query->update([
            'status' => $status,
            'approval_cause' => $approvalCause,
            'updated_at' => now()
        ]);
    }

    /**
     * Check for duplicate in specific period
     *
     * @param int $userId
     * @param int $fromUserId
     * @param string $description
     * @param string $amount
     * @param string $period
     * @return bool
     */
    public static function isDuplicateInPeriod($userId, $fromUserId, $description, $amount, $period)
    {
        $tableName = IBTransactionPeriodService::getTableName($period);

        return DB::table($tableName)
            ->where('user_id', $userId)
            ->where('from_user_id', $fromUserId)
            ->where('description', $description)
            ->where('amount', $amount)
            ->exists();
    }

    /**
     * Get transactions for a specific period
     *
     * @param string $period
     * @param array $conditions
     * @return \Illuminate\Support\Collection
     */
    public static function getTransactionsForPeriod($period, $conditions = [])
    {
        $tableName = IBTransactionPeriodService::getTableName($period);
        
        if (!Schema::hasTable($tableName)) {
            return collect([]);
        }

        $query = DB::table($tableName);
        
        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        return $query->get();
    }
} 