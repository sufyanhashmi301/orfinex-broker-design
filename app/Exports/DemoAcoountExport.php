<?php
namespace App\Exports;

use App\Models\ForexAccount;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class DemoAccountExport implements FromQuery, WithHeadings, WithMapping
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

        $query = ForexAccount::query()
            ->where('account_type','demo')
            ->applyFilters($filters)
            ->applyBalanceStatusFilter($balanceStatus);

        return $query->select('login', 'ib_number', 'schema', 'username', 'group', 'currency', 'leverage', 'balance', 'equity', 'credit', 'status');
    }

    public function headings(): array
    {
        return [
            'Account Number',
            'User',
            'Account Type',
            'Group',
            'Currency',
            'Leverage',
            'Balance',
            'Agent/IB Number',
            'Status',
        ];
    }

    public function map($forexAccount): array
    {
        return [
            $forexAccount->login,
            $forexAccount->username,
            $forexAccount->schema,
            $forexAccount->group,
            $forexAccount->currency,
            $forexAccount->leverage,
            $forexAccount->balance,
            $forexAccount->ib_number,
            $forexAccount->status,
        ];
    }
}
