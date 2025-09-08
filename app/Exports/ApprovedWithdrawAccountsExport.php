<?php

namespace App\Exports;

use App\Models\WithdrawAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApprovedWithdrawAccountsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $filters = $this->request->only(['username', 'email', 'created_at']);

        $data = WithdrawAccount::where('status', 'approved')
            ->with(['user', 'method'])
            ->latest();

        // Apply filters
        if (!empty($filters['username'])) {
            $data = $data->whereHas('user', function($query) use ($filters) {
                $query->where('username', 'like', '%' . $filters['username'] . '%')
                      ->orWhere('first_name', 'like', '%' . $filters['username'] . '%')
                      ->orWhere('last_name', 'like', '%' . $filters['username'] . '%')
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$filters['username']}%"]);
            });
        }

        if (!empty($filters['email'])) {
            $data = $data->whereHas('user', function($query) use ($filters) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            });
        }

        if (!empty($filters['created_at'])) {
            // Handle date range format "start_date to end_date"
            if (strpos($filters['created_at'], ' to ') !== false) {
                $dates = explode(' to ', $filters['created_at']);
                if (count($dates) == 2) {
                    $startDate = \Carbon\Carbon::parse($dates[0])->startOfDay();
                    $endDate = \Carbon\Carbon::parse($dates[1])->endOfDay();
                    $data = $data->whereBetween('created_at', [$startDate, $endDate]);
                }
            } else {
                // Single date
                $data = $data->whereDate('created_at', $filters['created_at']);
            }
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
            __('Approved'),
        ];
    }
} 