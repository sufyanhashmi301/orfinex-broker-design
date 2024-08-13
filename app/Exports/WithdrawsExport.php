<?php
namespace App\Exports;

use App\Enums\TxnType;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class WithdrawsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        
        $filters = $this->request->only(['email', 'status',  'created_at']);
        
        $query = Transaction::query()->where('type', TxnType::Withdraw)
        ->orWhere('type', TxnType::WithdrawAuto)
            ->applyFilters($filters);
    
        return $query->select('user_id', 'tnx',  'target_id', 'amount', 'method', 'status');
       
    }

    public function headings(): array
    {
        return [
            'User Name',
            'User Email',
            'Transaction ID',
            'Account',
            'Amount',
            'Gateway',
            'Status',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->user->username ?? 'N/A',  
            $transaction->user->email ?? 'N/A', 
            $transaction->tnx ?? 'N/A',
            $transaction->target_id ?? 'N/A',
            $transaction->amount ?? 'N/A',
            $transaction->method ?? 'N/A',
            $transaction->status->label() ?? 'N/A',
            //$transaction->created_at ? $transaction->created_at->format('d F Y') : 'N/A', // Formatted date
        ];
    }
}
