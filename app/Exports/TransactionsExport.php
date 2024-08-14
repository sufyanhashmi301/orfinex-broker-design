<?php
namespace App\Exports;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        
        $filters = $this->request->only(['email', 'status', 'type', 'created_at']);
        
        $query = Transaction::query()
            ->applyFilters($filters);
    
        return $query->select('user_id', 'tnx', 'type', 'target_id', 'amount', 'method', 'status','created_at');
       
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Username',
            'Phone',
            'User Email',
            'Transaction ID',
            'Type',
            'Account',
            'Amount',
            'Gateway',
            'Status',
            'Date',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->user->first_name ?? 'N/A',  
            $transaction->user->last_name ?? 'N/A',  
            $transaction->user->username ?? 'N/A',  
            $transaction->user->phone ?? 'N/A',  
            $transaction->user->email ?? 'N/A', 
            $transaction->tnx ?? 'N/A',
            $transaction->type->label() ?? 'N/A',
            $transaction->target_id ?? 'N/A',
            $transaction->amount ?? 'N/A',
            $transaction->method ?? 'N/A',
            $transaction->status->label() ?? 'N/A',
            $transaction->created_at ?? 'N/A', // Formatted date
        ];
    }
}
