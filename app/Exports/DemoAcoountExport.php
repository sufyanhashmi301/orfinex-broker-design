<?php
namespace App\Exports;

use App\Models\ForexAccount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class DemoAcoountExport implements FromQuery, WithHeadings, WithMapping
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


        $query = ForexAccount::query()
            ->where('account_type','demo')
            ->applyFilters($filters);

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
