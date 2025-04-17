<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class SyncIbBonusOldToNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ib-bonus-old-to-new'
    . ' {--date= : The starting date (YYYY-MM-DD) from which to sync transactions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Efficiently delete and re-sync IB bonus transactions, account balances, and per-account last ledger entries in chunks.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateArg = $this->option('date') ?: '2025-08-04';
        $cutoff = Carbon::parse($dateArg)->startOfDay();

        $this->info("Starting IB bonus sync from {$cutoff->toDateString()}...");

        DB::transaction(function () use ($cutoff) {
            // 1. Delete existing IB bonus transactions from the new DB
            DB::table('transactions')
                ->where('type', 'ib_bonus')
                ->where('created_at', '>=', $cutoff)
                ->delete();

//            // 2. Fetch and insert IB bonus transactions from old DB in chunks
//            DB::connection('old_connection')
//                ->table('transactions')
//                ->where('type', 'ib_bonus')
//                ->where('created_at', '>=', $cutoff)
//                ->orderBy('id')
//                ->chunk(500, function ($chunk) {
//                    // Prepare id/data tuples for error logging
//                    $tuples = [];
//                    foreach ($chunk as $txn) {
//                        $data = (array) $txn;
//                        $id = $data['id'];
//                        unset($data['id']);
//                        $tuples[] = ['id' => $id, 'data' => $data];
//                    }
//
//                    $batch = array_column($tuples, 'data');
//                    if (empty($batch)) {
//                        return;
//                    }
//
//                    try {
//                        DB::table('transactions')->insert($batch);
//                    } catch (QueryException $e) {
//                        // Fallback: insert each row individually to isolate failures
//                        foreach ($tuples as $tuple) {
//                            try {
//                                DB::table('transactions')->insert($tuple['data']);
//                            } catch (QueryException $inner) {
//                                Log::error("IB-bonus insert failed for txn id={$tuple['id']}: {$inner->getMessage()}");
//                            }
//                        }
//                    }
//                    $this->info('  → Inserted batch of '.count($batch).' transactions.');
//                });

            // 3 & 4. Sync IB wallet balances and last ledger entries per account in chunks
            DB::connection('old_connection')
                ->table('accounts')
                ->where('balance', 'ib_wallet')
                ->orderBy('id')
                ->chunk(500, function ($oldAccounts) {
                    foreach ($oldAccounts as $oldAcc) {
                        // 3. Update IB wallet balance in new DB
                        DB::table('accounts')
                            ->where('user_id', $oldAcc->user_id)
                            ->where('balance', 'ib_wallet')
                            ->update(['amount' => $oldAcc->amount]);

                        // 4. Fetch latest ledger entry for this account by account_id
                        $oldLedger = DB::connection('old_connection')
                            ->table('ledgers')
                            ->where('account_id', $oldAcc->id)
                            ->orderBy('id', 'desc')
                            ->first();

                        if (! $oldLedger) {
                            continue;
                        }

                        $newLast = DB::table('ledgers')
                            ->where('account_id', $oldAcc->id)

                            ->orderBy('id', 'desc')
                            ->first();

                        if ($newLast) {
                            DB::table('ledgers')
                                ->where('id', $newLast->id)
                                ->update([
                                    'debit'      => $oldLedger->debit,
                                    'credit'     => $oldLedger->credit,
                                    'balance'    => $oldLedger->balance,
                                    'created_at' => $oldLedger->created_at,
                                    'updated_at' => Carbon::now(),
                                ]);
                        }
                    }
                    $this->info('  → Synced '.count($oldAccounts).' accounts and their last ledger entries.');
                });
        });

        $this->info('IB bonus sync completed successfully.');
        return 0;
    }
}
