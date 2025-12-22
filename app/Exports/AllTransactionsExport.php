<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class AllTransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;

    protected $transactions;

    public function __construct(Collection $transactions)
    {
        $this->transactions = $transactions;
    }

    /**
     * Return the collection of transactions.
     */
    public function collection()
    {
        return $this->transactions;
    }

    /**
     * Define the headings for each column in the Excel export.
     */
    public function headings(): array
    {
        return [
            'Username',
            'Email',
            'Description',
            'Account',
            'Transaction ID',
            'Type',
            'Amount',
            'Fee',
            'Pay Amount',
            'Final Amount',
            'Status',
            'Date',
        ];
    }

    /**
     * Map each row in the export to match the headings.
     */
    public function map($transaction): array
    {
        return [
            $transaction->user->username ?? 'N/A',   // User's username
            $transaction->user->email ?? 'N/A',      // User's email
            $transaction->description,
            $transaction->target_id . ' (' . ucwords(str_replace('_', ' ', $transaction->target_type)) . ')',  // Target ID with formatted target type
            $transaction->tnx,
            ucfirst(str_replace('_', ' ', $transaction->type->value)),
            $transaction->amount . ' USD',
            $transaction->charge . ' USD',
            $transaction->pay_amount . ' ' . $transaction->pay_currency,
            $transaction->final_amount . ' USD',
            ucfirst($transaction->status->value),
            $transaction->created_at,
        ];
    }
}
