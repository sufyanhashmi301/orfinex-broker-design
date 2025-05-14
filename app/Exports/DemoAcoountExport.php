<?php
namespace App\Exports;

use App\Models\ForexAccount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Auth;

class DemoAcoountExport implements FromQuery, WithHeadings, WithMapping
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
        $filters = $this->request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag']);


        $query = ForexAccount::query()
            ->where('account_type','demo')
            ->applyFilters($filters);
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
        return $query->select('login', 'account_name', 'group', 'currency', 'leverage', 'balance', 'equity', 'credit', 'status');
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
