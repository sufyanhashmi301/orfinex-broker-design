<?php
namespace App\Exports;

use App\Enums\KYCStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class LevelThreePendingExport implements FromQuery, WithHeadings, WithMapping
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
        $filters = $this->request->only(['global_search', 'status', 'created_at']);

        $query = User::query()
            ->where('kyc_level3_credential', '!=', NULL)
            ->where('kyc', KYCStatus::PendingLevel3->value)
            ->latest('updated_at');

        // Apply user visibility rules
        if ($this->loggedInUser->hasRole('Super-Admin')) {
            // Super-Admin sees all users - no additional filtering needed
        } elseif ($this->loggedInUser->can('show-all-users-by-default-to-staff')) {
            // Staff with permission sees all users - no additional filtering needed
        } else {
            // Regular staff only sees attached users
            $attachedUserIds = $this->loggedInUser->users->pluck('id')->toArray();
            if (!empty($attachedUserIds)) {
                $query->whereIn('id', $attachedUserIds);
            } else {
                // If no users are attached, return empty result
                $query->where('id', -1);
            }
        }

        // Apply filters if method exists
        if (method_exists(User::class, 'applyFilters')) {
            $query->applyFilters($filters);
        }

        return $query->select('created_at', 'username', 'kyc_credential', 'status');
    }

    public function headings(): array
    {
        return [
            'Date',
            'User',
            'Type',
            'Status',
        ];
    }

    public function map($user): array
    {
        // Decode the JSON data in kyc_credential
        $kycCredential = json_decode($user->kyc_credential, true);

        // Get the kyc_type_of_name from the decoded data
        $kycTypeOfName = $kycCredential['kyc_type_of_name'] ?? 'N/A';

        // Local mapping of status IDs to names
        $statusNames = [
            1 => __('Level1'),
            2 => __('Pending'),
            3 => __('Rejected'),
            4 => __('Level2'),
            5 => __('PendingLevel3'),
            6 => __('RejectLevel3'),
            7 => __('Level3'),
        ];

        // Retrieve the readable name or default to 'Unknown'
        $statusName = $statusNames[$user->status] ?? 'Unknown';

        return [
            $user->created_at,
            $user->username,
            $kycTypeOfName,
            $statusName,
        ];
    }
}
