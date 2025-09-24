<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IBGroupExport implements FromCollection, WithHeadings, WithMapping
{
    protected $ibGroups;

    public function __construct($ibGroups)
    {
        $this->ibGroups = $ibGroups;
    }

    public function collection()
    {
        return $this->ibGroups;
    }

    public function headings(): array
    {
        return [
            'Group Name',
            'Status',
            'Is Global Account',
            'Rebate Rules',
            'Account Types',
            'Created At',
        ];
    }

    public function map($ibGroup): array
    {
        // Get rebate rule titles
        $rebateRules = $ibGroup->rebateRules->pluck('title')->implode(', ');

        // Get account type titles from all rebate rules
        $accountTypes = collect();
        foreach ($ibGroup->rebateRules as $rule) {
            if ($rule->forexSchemas) {
                $accountTypes = $accountTypes->merge($rule->forexSchemas->pluck('title'));
            }
        }
        $accountTypes = $accountTypes->unique()->implode(', ');

        return [
            $ibGroup->name,
            $rebateRules ?: 'N/A',
            $accountTypes ?: 'N/A',
            $ibGroup->status ? 'Active' : 'Disabled',
            $ibGroup->is_global_account ? 'Active' : 'Disabled',
            $ibGroup->created_at ? $ibGroup->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
}