<?php

namespace App\Exports;

use App\Models\WithdrawAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendingWithdrawAccountsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $filters = $this->request->only(['username', 'email', 'created_at']);

        $data = WithdrawAccount::where('status', 'pending')
            ->with(['user', 'method'])
            ->latest();

        // Apply filters
        if (!empty($filters['username'])) {
            $data = $data->whereHas('user', function($query) use ($filters) {
                $query->where('username', 'like', '%' . $filters['username'] . '%');
            });
        }

        if (!empty($filters['email'])) {
            $data = $data->whereHas('user', function($query) use ($filters) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            });
        }

        if (!empty($filters['created_at'])) {
            $data = $data->whereDate('created_at', $filters['created_at']);
        }

        return $data->get();
    }

    public function headings(): array
    {
        return [
            __('Date'),
            __('Username'),
            __('User Name'),
            __('User Email'),
            __('Method Name'),
            __('Currency'),
            __('Status'),
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->format('Y-m-d H:i:s'),
            $row->user->username ?? 'N/A',
            $row->user->full_name ?? 'N/A',
            $row->user->email ?? 'N/A',
            $row->method_name,
            $row->method->currency ?? 'N/A',
            __('Pending'),
        ];
    }
} 