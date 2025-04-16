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
        $old = DB::connection('old_connection')->table('transactions');

        // 1) get all IB‑bonus IDs in old
        $oldIds = $old->where('type', TxnType::IbBonus)
            ->pluck('id')
            ->toArray();

        if (empty($oldIds)) {
            return $this->info('No IB‑bonus records found in old_connection.');
        }

        // 2) find which of those are already in primary DB
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

        // 3) chunk through just the missing rows and insert them verbatim
        $old->where('type', TxnType::IbBonus)
            ->whereIn('id', $missing)
            ->orderBy('id')
            ->chunk(100, function ($rows) {
                $batch = [];
                foreach ($rows as $row) {
                    // cast stdClass → array so we keep every column
                    $batch[] = (array) $row;
                }
                DB::table('transactions')->insert($batch);
            });

        $this->info('✓ Done copying IB‑bonus transactions.');
    }
}
