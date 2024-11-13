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

class TransferHistoryExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Query the transactions table with filters based on user input.
     */
    public function query()
    {
        return Transaction::where('user_id', auth()->user()->id)
            ->whereIn('type', [TxnType::SendMoney, TxnType::SendMoneyInternal, TxnType::ReceiveMoneyInternal]) // Matching types
            ->when($this->request->input('query'), function ($query) {
                $searchTerm = $this->request->input('query');
                $query->where(function ($subQuery) use ($searchTerm) {
                    $subQuery->where('description', 'like', "%{$searchTerm}%")
                             ->orWhere('tnx', 'like', "%{$searchTerm}%")
                             ->orWhere('status', 'like', "%{$searchTerm}%"); // Search by description, transaction ID, or status
                });
            })
            ->when($this->request->input('date'), function ($query) {
                $query->whereDate('created_at', Carbon::parse($this->request->input('date'))); // Exact date filter
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
