<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Enums\TxnType;

class SyncIbBonusTransactions extends Command
{
    protected $signature   = 'sync:ib-bonus';
    protected $description = 'Copy missing IB Bonus rows from old_connection into primary DB';

    public function handle()
    {
        // 1) Point at the old table
        $old = DB::connection('old_connection')->table('transactions');

        // 2) Grab every IB‑bonus ID from old_connection
        $oldIds = $old
            ->where('type', TxnType::IbBonus)
            ->pluck('id')
            ->toArray();

        if (empty($oldIds)) {
            return $this->info('No IB‑bonus records found in old_connection.');
        }

        // 3) See which of those IDs already live in your main DB
        $existing = DB::table('transactions')
            ->where('type', TxnType::IbBonus)
            ->whereIn('id', $oldIds)
            ->pluck('id')
            ->toArray();

        $missing = array_diff($oldIds, $existing);

        if (empty($missing)) {
            return $this->info('All IB‑bonus transactions already copied.');
        }

        $this->info('Importing '.count($missing).' missing IB‑bonus rows…');

        // 4) Chunk through just the missing rows, casting to array, and insert verbatim
        $old
            ->where('type', TxnType::IbBonus)
            ->whereIn('id', $missing)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                $batch = [];
                foreach ($rows as $row) {
                    // (array)$row preserves exactly the columns that actually exist
                    $batch[] = (array) $row;
                }
                DB::table('transactions')->insert($batch);
            });

        $this->info('✓ Done copying IB‑bonus transactions.');
    }
}
