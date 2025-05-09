<?php
namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;

class UsersExport implements FromQuery, WithHeadings, WithMapping
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
        $filters = $this->request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag', 'staff_name']);
        $balanceStatus = $this->request->balanceStatus;

        // Start with base query
        $query = User::query()
            ->with(['staff' => function($query) {
                $query->select('admins.id', 'admins.first_name', 'admins.last_name', 'admins.email');
            }]);

        // Apply user visibility rules (same as in index method)
        if ($this->loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin sees all users - no additional filtering needed
        } elseif ($this->loggedInUser->can('show-all-users-by-default-to-staff')) {
            // Staff with permission sees all users - no additional filtering needed
        } else {
            // Regular staff only sees attached users
            $attachedUserIds = $this->loggedInUser->users->pluck('id');
            if ($attachedUserIds->isNotEmpty()) {
                $query->whereIn('id', $attachedUserIds);
            } else {
                // If no users are attached, return empty result
                $query->where('id', -1);
            }
        }

        // Apply staff name filter if present
        if (!empty($filters['staff_name'])) {
            $query->whereHas('staff', function($q) use ($filters) {
                $searchTerm = $filters['staff_name'];
                
                if (str_contains($searchTerm, ' ')) {
                    $nameParts = explode(' ', $searchTerm, 2);
                    $q->where(function($subQuery) use ($nameParts) {
                        $subQuery->where(function($q) use ($nameParts) {
                                $q->where('first_name', 'like', '%'.$nameParts[0].'%')
                                  ->where('last_name', 'like', '%'.$nameParts[1].'%');
                            })
                            ->orWhere(function($q) use ($nameParts) {
                                $q->where('first_name', 'like', '%'.$nameParts[1].'%')
                                  ->where('last_name', 'like', '%'.$nameParts[0].'%');
                            });
                    });
                } else {
                    $q->where(function($subQuery) use ($searchTerm) {
                        $subQuery->where('first_name', 'like', '%'.$searchTerm.'%')
                                ->orWhere('last_name', 'like', '%'.$searchTerm.'%');
                    });
                }
            });
        }

        // Apply other filters
        $query->applyFilters($filters)
              ->applyBalanceStatusFilter($balanceStatus);

        return $query;
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
            'Tag',
            'Balance',
            'Staff Name',
        ];
    }

    public function map($user): array
    {
        $staffNames = $user->staff->map(function($staff) {
            return $staff->first_name.' '.$staff->last_name;
        })->implode(', ');

        return [
            $user->first_name,
            $user->last_name,
            $user->username,
            $user->email,
            $user->phone,
            $user->country,
            $user->gender,
            $user->comment,
            $user->balance,
            $staffNames ?: 'N/A',
        ];
    }
}