<?php

namespace App\Console\Commands;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ExpirePendingDeposits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deposits:expire-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire pending deposit transactions older than 1 hour and remove none status transactions older than 2 hours';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Starting to process transactions...');

            // Calculate the time thresholds
            $oneHourAgo = Carbon::now()->subHour();
            $twoHoursAgo = Carbon::now()->subHours(2);

            DB::beginTransaction();

            try {
                // 1. Find and expire pending deposit transactions older than 1 hour
                $expiredTransactions = Transaction::where('status', TxnStatus::Pending)
                    ->where('type', TxnType::Deposit)
                    ->where('created_at', '<', $oneHourAgo)
                    ->get();

                $expiredCount = $expiredTransactions->count();

                if ($expiredCount > 0) {
                    // Update the status to expired
                    foreach ($expiredTransactions as $transaction) {
                        $transaction->status = TxnStatus::Expired;
                        $transaction->save();
                    }

                    $this->info("Successfully expired {$expiredCount} pending deposit transaction(s).");
                } else {
                    $this->info('No pending deposits to expire.');
                }

                // 2. Find and remove transactions with status "none" older than 2 hours
                $noneTransactions = Transaction::where('status', TxnStatus::None)
                    ->where('created_at', '<', $twoHoursAgo)
                    ->get();

                $removedCount = $noneTransactions->count();

                if ($removedCount > 0) {
                    foreach ($noneTransactions as $transaction) {
                        $transaction->delete();
                    }

                    $this->info("Successfully removed {$removedCount} transaction(s) with 'none' status.");
                } else {
                    $this->info('No transactions with "none" status to remove.');
                }

                DB::commit();

                $this->info('Transaction cleanup completed successfully.');
                return 0;
            } catch (Throwable $e) {
                DB::rollBack();
                $this->error('Failed to process transactions: ' . $e->getMessage());
                Log::error('Failed to process transactions', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                return 1;
            }
        } catch (Throwable $e) {
            $this->error('Command execution failed: ' . $e->getMessage());
            Log::error('Transaction cleanup command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return 1;
        }
    }
}

