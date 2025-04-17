<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Enums\TxnType;
use Illuminate\Database\QueryException;

class SyncIbBonusTransactions extends Command
{
    protected $signature   = 'sync:ib-bonus-transaction';
    protected $description = 'Copy missing IB Bonus rows from old_connection into primary DB, chunked to avoid huge placeholders';

    public function handle()
    {
        $this->info("Starting IB‑bonus sync…");

        // Define the query on the old connection, ordered by PK
        $oldQuery = DB::connection('old_connection')
            ->table('transactions')
            ->where('type', TxnType::IbBonus)
            ->where('created_at', '>=', '2025-04-08')
            ->orderBy('id');

        // Stream in chunks of 500 rows at a time
        $oldQuery->chunkById(500, function ($rows) {
            // 1) Gather this chunk’s IDs
            $ids = array_map(fn($r) => $r->id, $rows->all());

            // 2) Find which of these IDs already exist in main DB
            $existing = DB::table('transactions')
                ->whereIn('id', $ids)
                ->pluck('id')
                ->all();

            // 3) Build a batch of *only* the missing rows
            $batch = [];
            foreach ($rows as $row) {
                if (! in_array($row->id, $existing, true)) {
                    $batch[] = (array) $row;
                }
            }

            // 4) Bulk‐insert that batch (if any)
            if (! empty($batch)) {
                try {
                    DB::table('transactions')->insert($batch);
                } catch (QueryException $e) {
                    // Fallback: insert one by one to isolate bad rows
                    foreach ($batch as $data) {
                        try {
                            DB::table('transactions')->insert($data);
                        } catch (QueryException $inner) {
                            \Log::error("IB‑bonus insert failed for id={$data['id']}: {$inner->getMessage()}");
                        }
                    }
                }
                $this->info('  → Inserted IDs: '.implode(', ', array_column($batch, 'id')));
            }
        });

        $this->info("IB‑bonus sync complete.");
    }
}
