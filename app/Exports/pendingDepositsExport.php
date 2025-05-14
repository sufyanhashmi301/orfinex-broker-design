<?php
namespace App\Exports;

use App\Enums\TxnType;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class pendingDepositsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;
    protected $loggedInUser;

    public function __construct($request)
    {
        $this->request = $request;
        $this->loggedInUser = Auth::user()->load('users'); // Eager load the attached users
    }

    public function query()
    {
        $filters = $this->request->only(['email', 'status', 'created_at']);

        $query = Transaction::query()
            ->where('status', 'pending')
            ->where('type', TxnType::ManualDeposit)
            ->latest();

        // Apply user visibility rules
        if ($this->loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin sees all transactions - no additional filtering needed
        } elseif ($this->loggedInUser->can('show-all-users-by-default-to-staff')) {
            // Staff with permission sees all transactions - no additional filtering needed
        } else {
            // Regular staff only sees transactions from attached users
            $attachedUserIds = $this->loggedInUser->users->pluck('id')->toArray();

            if (!empty($attachedUserIds)) {
                $query->whereIn('user_id', $attachedUserIds);
            } else {
                // If no users are attached, return empty result
                $query->where('user_id', -1);
            }
        }

        // Apply filters if method exists
        if (method_exists(Transaction::class, 'applyFilters')) {
            $query->applyFilters($filters);
        }

        return $query->select(
            'user_id',
            'tnx',
            'target_id',
            'amount',
            'pay_currency',
            'pay_amount',
            'final_amount',
            'charge',
            'description',
            'status',
            'created_at'
        );
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
            'Account',
            'Pay Amount',
            'Final Amount',
            'Charge',
            'Description',
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
            $transaction->target_id ?? 'N/A',
            ($transaction->pay_amount ? $transaction->pay_amount . ' ' . $transaction->pay_currency : 'N/A'),
            ($transaction->final_amount ? $transaction->final_amount . ' USD' : 'N/A'),
            ($transaction->charge ? $transaction->charge . ' USD' : 'N/A'),
            $transaction->description ?? 'N/A',
            $transaction->status->label() ?? 'N/A',
            $transaction->created_at ? Carbon::parse($transaction->created_at)->format('d M Y g:i A') : 'N/A',
        ];
    }
}
