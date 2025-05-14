<?php
namespace App\Exports;

use App\Models\ForexAccount;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class RealAccountExport implements FromQuery, WithHeadings, WithMapping
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
        $filters = $this->request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag']);

        $query = ForexAccount::query()
            ->where('account_type', 'real');

        // Apply user visibility rules
        if ($this->loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin sees all accounts - no additional filtering needed
        } elseif ($this->loggedInUser->can('show-all-users-by-default-to-staff')) {
            // Staff with permission sees all accounts - no additional filtering needed
        } else {
            // Regular staff only sees accounts of attached users
            $attachedUserIds = $this->loggedInUser->users->pluck('id')->toArray();
            if (!empty($attachedUserIds)) {
                $query->whereIn('user_id', $attachedUserIds);
            } else {
                // If no users are attached, return empty result
                $query->where('user_id', -1);
            }
        }

        // Apply filters if method exists
        if (method_exists(ForexAccount::class, 'applyFilters')) {
            $query->applyFilters($filters);
        }

        return $query->select('login', 'account_name', 'group', 'currency', 'leverage', 'balance', 'status');
    }

    public function headings(): array
    {
        return [
            'Account Number',
            'Account Name',
            'Group',
            'Currency',
            'Leverage',
            'Balance',
            'Status',
        ];
    }

    public function map($forexAccount): array
    {
        return [
            $forexAccount->login,
            $forexAccount->account_name,
            $forexAccount->group,
            $forexAccount->currency,
            $forexAccount->leverage,
            $forexAccount->balance,
            $forexAccount->status,
        ];
    }
}
