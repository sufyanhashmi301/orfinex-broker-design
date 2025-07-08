<?php
namespace App\Exports;

use App\Enums\IBStatus;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RejectedIbExport implements FromQuery, WithHeadings, WithMapping
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
        $filters = $this->request->only(['global_search', 'ib_group', 'phone', 'country', 'status', 'created_at', 'tag', 'date_filter']);
        $balanceStatus = $this->request->balanceStatus;

        $query = User::where('ib_status', IBStatus::REJECTED);

        // Apply IB Group filter if specified
        if (!empty($this->request->ib_group)) {
            $query->where('ib_group_id', $this->request->ib_group);
        }

        if ($this->loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin sees all users
        } elseif ($this->loggedInUser->can('show-all-users-by-default-to-staff')) {
            // Staff with permission sees all users
        } else {
            $attachedUserIds = $this->loggedInUser->users->pluck('id');
            if ($attachedUserIds->isNotEmpty()) {
                $query->whereIn('id', $attachedUserIds);
            } else {
                $query->where('id', -1); // No users visible
            }
        }

        // Process date range filters
        $dateRanges = [];

        // 1. Created_at custom range
        if (!empty($this->request->created_at)) {
            $dates = explode(' to ', $this->request->created_at);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $dateRanges[] = [
                    'start' => $start,
                    'end' => $end,
                    'days' => $start->diffInDays($end)
                ];
            }
        }

        // 2. Predefined range
        if ($this->request->date_filter) {
            $filter = $this->request->date_filter;
            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $dateRanges[] = [
                    'start' => $dateRange[0],
                    'end' => $dateRange[1],
                    'days' => $dateRange[0]->diffInDays($dateRange[1])
                ];
            }
        }

        // 3. Apply the shortest date range if multiple exist
        if (count($dateRanges) > 0) {
            $shortestRange = collect($dateRanges)->sortBy('days')->first();
            $query->whereBetween('created_at', [
                $shortestRange['start'],
                $shortestRange['end']
            ]);
        }

        // Apply other filters
        $query->applyFilters($filters)
              ->applyBalanceStatusFilter($balanceStatus);

        return $query->select('first_name', 'last_name', 'username', 'email', 'phone', 'country', 'gender', 'ib_group_id', 'ib_status');
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Username',
            'Email',
            'Phone',
            'Country',
            'Gender',
            'IB Group',
            'IB Status'
        ];
    }

    public function map($user): array
    {
        return [
            $user->first_name,
            $user->last_name,
            safe($user->username),
            safe($user->email),
            $user->phone,
            $user->country,
            $user->gender,
            $user->ibGroup->name ?? 'N/A',
            $user->ib_status,
        ];
    }
}