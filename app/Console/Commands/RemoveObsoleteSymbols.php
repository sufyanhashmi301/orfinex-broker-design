<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Symbol;

class RemoveObsoleteSymbols extends Command
{
    protected $signature = 'symbols:remove-obsolete';
    protected $description = 'Remove symbols that do not exist in mt5_symbols and their associations in symbol_group_symbol';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Step 1: Get all symbols from mt5_symbols table
        $mt5SymbolsList = DB::connection('mt5_db')
            ->table('mt5_symbols')
            ->pluck('Symbol')
            ->toArray();

        // Step 2: Get all symbols from the local symbols table
        $localSymbolsList = Symbol::pluck('symbol')->toArray();

        // Step 3: Identify symbols that are in local symbols table but not in mt5_symbols
        $symbolsToDelete = array_diff($localSymbolsList, $mt5SymbolsList);

        if (empty($symbolsToDelete)) {
            $this->info('No obsolete symbols found.');
            return;
        }

        // Step 4: Remove these symbols from symbol_groups pivot table first
        $symbolIdsToDelete = Symbol::whereIn('symbol', $symbolsToDelete)->pluck('id');

        DB::table('symbol_group_symbol')
            ->whereIn('symbol_id', $symbolIdsToDelete)
            ->delete();

        // Step 5: Now remove these symbols from the symbols table
        Symbol::whereIn('symbol', $symbolsToDelete)->delete();

        $this->info('Obsolete symbols and their associations have been removed successfully.');
    }
}
