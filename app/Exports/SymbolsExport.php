<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class SymbolsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DB::connection('mt5_db')
            ->table('mt5_symbols')
            ->select('Symbol_ID', 'Symbol', 'Path', 'Description', 'ContractSize');
    
        // Apply the same filters as your index page
        if ($this->request->filled('global_search')) {
            $searchTerm = '%' . $this->request->global_search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('Symbol', 'LIKE', $searchTerm)
                  ->orWhere('Description', 'LIKE', $searchTerm)
                  ->orWhere('Path', 'LIKE', $searchTerm);
            });
        }
    
        if ($this->request->filled('contact_size')) {
            $query->where('ContractSize', $this->request->contact_size);
        }
    
        if ($this->request->filled('path')) {
            $query->where('Path', 'LIKE', '%' . $this->request->path . '%');
        }
    
        // Get the filtered results
        $symbols = $query->get();
    
        // Add status information (same as your index method)
        $localSymbols = DB::table('symbols')
            ->select('symbol', 'status')
            ->whereIn('symbol', $symbols->pluck('Symbol')->toArray())
            ->get()
            ->keyBy('symbol');
    
        foreach ($symbols as $symbol) {
            $symbol->status = isset($localSymbols[$symbol->Symbol]) && $localSymbols[$symbol->Symbol]->status == 1
                ? 'Enabled'
                : 'Disabled';
        }
    
        // Apply status filter if present
        if ($this->request->filled('status')) {
            $statusFilter = $this->request->status == "1" ? 'Enabled' : 'Disabled';
            $symbols = $symbols->filter(function($symbol) use ($statusFilter) {
                return $symbol->status === $statusFilter;
            });
        }
    
        return $symbols;
    }
    public function headings(): array
    {
        return [
            'Symbol ID',
            'Symbol',
            'Path',
            'Description',
            'Contract Size',
            'Status'
        ];
    }

    public function map($symbol): array
    {
        $status = DB::table('symbols')
            ->where('symbol', $symbol->Symbol)
            ->value('status');

        return [
            $symbol->Symbol_ID,
            $symbol->Symbol,
            $symbol->Path,
            $symbol->Description,
            $symbol->ContractSize,
            $status ? 'Enabled' : 'Disabled'
        ];
    }
}