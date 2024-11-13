<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Enums\TxnType;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DepositsHistoryExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Query the transactions table with the same filters used in the transactions method.
     */
    public function query()
{
    return Transaction::where('user_id', auth()->user()->id)
        ->whereIn('type', [TxnType::Deposit, TxnType::ManualDeposit]) // Only deposits
        ->when($this->request->input('query'), function ($query) {
            $query->where(function ($subQuery) {
                $searchTerm = $this->request->input('query');
                $subQuery->where('description', 'like', "%{$searchTerm}%")
                         ->orWhere('tnx', 'like', "%{$searchTerm}%")
                         ->orWhere('status', 'like', "%{$searchTerm}%");  // Added status to search
            });
        })
        ->when($this->request->input('date'), function ($query) {
            $query->whereDay('created_at', '=', Carbon::parse($this->request->input('date'))->format('d'));
        })
        ->orderBy('created_at', 'desc');
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
            'Pay Amount',
            'Final Amount',
            'Amount',
            'Fee',
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
            $transaction->pay_amount . ' ' . $transaction->pay_currency,
            $transaction->final_amount . ' USD',
            $transaction->amount . ' USD',
            $transaction->charge . ' USD',
            ucfirst($transaction->status->value),
            $transaction->created_at,
        ];
    }
}
