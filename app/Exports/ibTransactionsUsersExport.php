<?php
namespace App\Exports;

use App\Services\IBTransactionQueryService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class ibTransactionsUsersExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    
    protected $userId;
    protected $filters;

    public function __construct($userId, $filters = [])
    {
        $this->userId = $userId;
        $this->filters = $filters;
    }

    public function collection()
    {
        // Use the same service and logic as the display
        $query = IBTransactionQueryService::getUserIBTransactions($this->userId, $this->filters);
        
        if (!$query) {
            return collect([]);
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }


    public function headings(): array
    {
        return [
            'Date', 
            'Transaction ID',
            'Type',
            'Account',
            'Amount',
            'Gateway',
            'Status',
            'Login',
            'Deal',
            'Symbol',
            'Description'
        ];
    }

    public function map($transaction): array
    {
        // Parse manual field data for deal information
        $manualData = [];
        if (!empty($transaction->manual_field_data) && $transaction->manual_field_data !== '[]') {
            $manualData = json_decode($transaction->manual_field_data, true) ?: [];
        }
        
        // Use manual field data time if available, otherwise created_at
        $date = isset($manualData['time']) 
            ? Carbon::parse($manualData['time'])->format('M d, Y h:i A')
            : Carbon::parse($transaction->created_at)->format('M d, Y h:i A');
        
        return [
            $date,
            $transaction->tnx,
            $transaction->type,
            $transaction->target_id,
            $transaction->final_amount,
            $transaction->method,
            $transaction->status,
            $manualData['login'] ?? '-',
            $manualData['deal'] ?? '-',
            $manualData['symbol'] ?? '-',
            $transaction->description,
        ];
    }
}
