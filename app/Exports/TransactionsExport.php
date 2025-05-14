<?php
namespace App\Exports;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;
    protected $loggedInUser;

    public function __construct($request)
    {
        $this->request = $request;
        $this->loggedInUser = Auth::user();
    }

    public function query()
    {
        $filters = $this->request->only(['email', 'status', 'type', 'created_at']);

        $query = Transaction::query();

        if ($this->loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin sees all transactions
        } elseif ($this->loggedInUser->can('show-all-users-by-default-to-staff')) {
            // Staff with permission sees all transactions
        } else {
            $attachedUserIds = $this->loggedInUser->users->pluck('id');
            if ($attachedUserIds->isNotEmpty()) {
                $query->whereIn('user_id', $attachedUserIds);
            } else {
                $query->where('id', -1); // No transactions visible
            }
        }

        $query->applyFilters($filters);

        return $query->select('user_id', 'tnx', 'type', 'description', 'status', 'created_at');
    }

    public function headings(): array
    {
        return [
            'First Name', 'Last Name', 'Username', 'Phone', 'User Email',
            'Transaction ID', 'Type', 'Account', 'Pay Amount',
            'Final Amount', 'Charge', 'Description', 'Status', 'Date'
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
            $transaction->pay_amount .' '.$transaction->pay_currency ?? 'N/A',
            $transaction->final_amount .' USD' ?? 'N/A',
            $transaction->charge .' USD' ?? 'N/A',
            $transaction->description ?? 'N/A',
            $transaction->status->label() ?? 'N/A',
            $transaction->created_at ? Carbon::parse($transaction->created_at)->format('d M Y g:i A'): 'N/A',
        ];
    }
}
