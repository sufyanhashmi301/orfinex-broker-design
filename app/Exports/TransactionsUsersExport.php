<?php
namespace App\Exports;
use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class TransactionsUsersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function query()
    {
        return Transaction::where('user_id', $this->userId)
            ->where('status', '!=', TxnStatus::None) // Exclude none status transactions
            ->latest()
            ->select('created_at', 'tnx', 'type', 'target_id', 'final_amount', 'method', 'status');
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
            'Status'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->created_at,
            $transaction->tnx,
            $transaction->type ? $transaction->type->label() : 'Unknown',
            $transaction->target_id,
            $transaction->final_amount,
            $transaction->method,
            $transaction->status ? $transaction->status->label() : 'Unknown',
        ];
    }
}
