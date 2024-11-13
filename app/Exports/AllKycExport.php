<?php
namespace App\Exports;

use App\Enums\KYCStatus;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class AllKycExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $filters = $this->request->only(['global_search', 'status',  'created_at']);

        $query = User::query()
            ->whereNotNull('kyc_credential')->latest()->applyFilters($filters);

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
    
        // Local mapping of status IDs to names with translations
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
