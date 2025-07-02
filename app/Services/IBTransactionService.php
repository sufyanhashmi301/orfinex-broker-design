<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IBTransactionService
{
    protected static function createIBTransactionTable($year)
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

    public static function new($amount, $charge, $finalAmount, $method, $description, string|TxnType $type, string|TxnStatus $status, $currency = null, $payAmount = null, $userId = null, $fromUserId = null, $relatedModel = 'User', array $manualFieldData = [], string $approvalCause = 'none', $targetId = null, $targetType = null, $isLevel = false)
    {
        $currentYear = Carbon::now()->year;
        $tableName = 'ib_transactions_' . $currentYear;

        // Create the yearly table if it doesn't exist
        if (!self::createIBTransactionTable($currentYear)) {
            throw new \Exception("Failed to create IB transactions table for year {$currentYear}");
        }

        $tnx = uniqid('IB');

        // Store transaction in yearly table
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
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return (object)[
            'tnx' => $tnx,
            'amount' => $amount,
            'target_id' => $targetId,
            'target_type' => $targetType,
            'user_id' => $userId,
            'from_user_id' => $fromUserId
        ];
    }

    public static function update($tnx, $status, $userId = null, $approvalCause = 'none')
    {
        $currentYear = Carbon::now()->year;
        $tableName = 'ib_transactions_' . $currentYear;

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
        $currentYear = Carbon::now()->year;
        $tableName = 'ib_transactions_' . $currentYear;

        return DB::table($tableName)
            ->where('user_id', $userId)
            ->where('from_user_id', $fromUserId)
            ->where('description', $description)
            ->where('amount', $amount)
            ->exists();
    }
} 