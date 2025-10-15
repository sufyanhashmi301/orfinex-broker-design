<?php

namespace App\Console\Commands;

use App\Enums\AccountBalanceType;
use App\Enums\TxnType;
use App\Models\Account;
use App\Models\User;
use App\Services\IBTransactionPeriodService;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Throwable;

class RemoveDuplicateIBTransactions extends Command
{
    protected $signature = 'ib:remove-duplicates {--period=current} {--dry-run} {--chunk-size=100}';
    protected $description = 'Remove duplicate IB transactions and deduct amounts from ib_wallet';

    protected $stats = [
        'duplicates_found' => 0,
        'duplicates_removed' => 0,
        'wallet_adjustments' => 0,
        'errors' => 0,
        'total_amount_deducted' => 0
    ];

    public function handle()
    {
        try {
            $this->info('Starting duplicate IB transaction removal process...');
            
            $period = $this->option('period');
            $dryRun = $this->option('dry-run');
            $chunkSize = (int) $this->option('chunk-size');
            
            // Get current period if not specified
            if ($period === 'current') {
                $period = IBTransactionPeriodService::getCurrentPeriod();
            }
            
            $tableName = IBTransactionPeriodService::getTableName($period);
            
            // Check if table exists
            if (!Schema::hasTable($tableName)) {
                $this->error("Table {$tableName} does not exist for period {$period}");
                return 1;
            }
            
            $this->info("Processing period: {$period} (Table: {$tableName})");
            
            if ($dryRun) {
                $this->warn('DRY RUN MODE - No changes will be made');
            }
            
            // Process duplicates in chunks
            $this->processDuplicatesInChunks($tableName, $chunkSize, $dryRun);
            
            // Display results
            $this->displayResults();
            
            return 0;
            
        } catch (Throwable $e) {
            Log::error("Duplicate IB transaction removal failed: {$e->getMessage()}", [
                'trace' => $e->getTraceAsString()
            ]);
            $this->error("Process failed: {$e->getMessage()}");
            return 1;
        }
    }

    protected function processDuplicatesInChunks($tableName, $chunkSize, $dryRun)
    {
        // Find duplicates by grouping on the key fields
        $duplicateGroups = DB::table($tableName)
            ->select([
                'description',
                'amount', 
                'from_user_id',
                'user_id',
                DB::raw('COUNT(*) as duplicate_count'),
                DB::raw('MIN(id) as keep_id'),
                DB::raw('GROUP_CONCAT(id) as all_ids')
            ])
            ->where('type', TxnType::IbBonus->value)
            ->groupBy(['description', 'amount', 'from_user_id', 'user_id'])
            ->having('duplicate_count', '>', 1)
            ->get();

        $this->stats['duplicates_found'] = $duplicateGroups->sum('duplicate_count') - $duplicateGroups->count();
        
        if ($duplicateGroups->isEmpty()) {
            $this->info('No duplicate transactions found.');
            return;
        }

        $this->info("Found {$duplicateGroups->count()} duplicate groups with {$this->stats['duplicates_found']} duplicate transactions");

        // Process each duplicate group
        foreach ($duplicateGroups as $group) {
            try {
                $this->processDuplicateGroup($tableName, $group, $dryRun);
            } catch (Throwable $e) {
                $this->stats['errors']++;
                Log::error("Error processing duplicate group: {$e->getMessage()}", [
                    'group' => $group,
                    'trace' => $e->getTraceAsString()
                ]);
                $this->error("Error processing group for user {$group->user_id}: {$e->getMessage()}");
            }
        }
    }

    protected function processDuplicateGroup($tableName, $group, $dryRun)
    {
        $allIds = explode(',', $group->all_ids);
        $keepId = $group->keep_id;
        $duplicateIds = array_filter($allIds, fn($id) => $id != $keepId);
        
        if (empty($duplicateIds)) {
            return;
        }

        // Calculate total amount to deduct (number of duplicates * amount)
        $duplicateCount = count($duplicateIds);
        $totalAmountToDeduct = BigDecimal::of($group->amount)->multipliedBy($duplicateCount);
        
        // The user_id is the one who RECEIVED the duplicate amounts, so deduct from their wallet
        $receiverUserId = $group->user_id;
        
        $this->info("Processing {$duplicateCount} duplicates for receiver user {$receiverUserId}, amount to deduct: {$totalAmountToDeduct}");

        if (!$dryRun) {
            DB::beginTransaction();
            
            try {
                // Remove duplicate transactions
                $deleted = DB::table($tableName)
                    ->whereIn('id', $duplicateIds)
                    ->delete();
                
                $this->stats['duplicates_removed'] += $deleted;
                
                // Deduct amount from the receiver's ib_wallet (user who got the duplicate amounts)
                $actualDeducted = $this->deductFromIBWallet($receiverUserId, $totalAmountToDeduct->toFloat());
                
                $this->stats['wallet_adjustments']++;
                $this->stats['total_amount_deducted'] = BigDecimal::of($this->stats['total_amount_deducted'])
                    ->plus($actualDeducted)
                    ->toFloat();
                
                DB::commit();
                
                Log::info("Removed {$deleted} duplicate transactions for receiver user {$receiverUserId}, deducted {$actualDeducted} from wallet");
                
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        } else {
            $this->line("  [DRY RUN] Would remove {$duplicateCount} duplicates and deduct {$totalAmountToDeduct} from receiver user {$receiverUserId} wallet");
        }
    }

    protected function deductFromIBWallet($userId, $amount)
    {
        // Get user's IB wallet account
        $account = get_user_account($userId, AccountBalanceType::IB_WALLET);
        
        if (!$account) {
            throw new \Exception("IB Wallet account not found for user {$userId}");
        }

        // Lock the account for update
        $account = Account::where('wallet_id', $account->wallet_id)
            ->lockForUpdate()
            ->firstOrFail();

        $currentBalance = BigDecimal::of($account->amount);
        $deductAmount = BigDecimal::of($amount);
        
        // Determine actual deduction amount - never allow negative balance
        if ($currentBalance->isLessThan($deductAmount)) {
            $this->warn("Insufficient balance for user {$userId}. Current: {$currentBalance}, Required: {$deductAmount}. Setting wallet to 0.");
            // Deduct all available balance (set wallet to 0)
            $actualDeductAmount = $currentBalance;
            $newBalance = BigDecimal::of(0);
        } else {
            // Sufficient balance - deduct the full amount
            $actualDeductAmount = $deductAmount;
            $newBalance = $currentBalance->minus($deductAmount);
        }
        
        if ($actualDeductAmount->isPositive()) {
            // Update account balance (never negative)
            $account->amount = $newBalance->toFloat();
            $account->save();
            
            // Update user's ib_balance column (never negative)
            $user = User::where('id', $userId)->lockForUpdate()->first();
            if ($user) {
                $userCurrentBalance = BigDecimal::of($user->ib_balance ?? 0);
                if ($userCurrentBalance->isGreaterThanOrEqualTo($actualDeductAmount)) {
                    $user->ib_balance = $userCurrentBalance->minus($actualDeductAmount)->toFloat();
                } else {
                    // Set to 0 if insufficient balance
                    $user->ib_balance = 0;
                }
                $user->save();
            }
            
            
            Log::info("Deducted {$actualDeductAmount} from user {$userId} IB wallet. New balance: {$newBalance}");
        }
        
        // Return the actual amount deducted for tracking
        return $actualDeductAmount->toFloat();
    }


    protected function displayResults()
    {
        $this->info("\n=== Duplicate Removal Results ===");
        $this->info("Duplicates Found: {$this->stats['duplicates_found']}");
        $this->info("Duplicates Removed: {$this->stats['duplicates_removed']}");
        $this->info("Wallet Adjustments: {$this->stats['wallet_adjustments']}");
        $this->info("Total Amount Deducted: {$this->stats['total_amount_deducted']}");
        $this->info("Errors: {$this->stats['errors']}");
        
        if ($this->stats['errors'] > 0) {
            $this->warn("Some errors occurred during processing. Check logs for details.");
        }
        
        if ($this->stats['duplicates_removed'] > 0) {
            $this->info("✅ Successfully processed duplicate transactions");
        }
    }
}
