<?php
namespace App\Exports;
use App\Enums\IBStatus;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class RejectedIbExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $filters = $this->request->only(['global_search', 'phone', 'country', 'status', 'created_at', 'tag']);
        $balanceStatus = $this->request->balanceStatus;

        $query = User::where('ib_status', IBStatus::REJECTED)
            ->applyFilters($filters)
            ->applyBalanceStatusFilter($balanceStatus);

        return $query->select('first_name', 'last_name', 'username', 'email', 'phone', 'country', 'gender', 'comment');
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
            'Tag'
        ];
    }

    public function map($user): array
    {
        return [
            $user->first_name,
            $user->last_name,
            $user->username,
            $user->email,
            $user->phone,
            $user->country,
            $user->gender,
            $user->comment,
        ];
    }
}
