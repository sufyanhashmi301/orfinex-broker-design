<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RebateRuleExport implements FromCollection, WithHeadings, WithMapping
{
    protected $rebateRules;

    public function __construct($rebateRules)
    {
        $this->rebateRules = $rebateRules;
    }

    public function collection()
    {
        return $this->rebateRules;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Rebate Name',
            'Symbol Groups',
            'Account Types',
            'IB Groups',
            'Total Rebate',
            'Status',
            'Created At',
        ];
    }

    public function map($rebateRule): array
    {
        // Get symbol group titles
        $symbolGroups = $rebateRule->symbolGroups->pluck('title')->implode(', ');

        // Get account type titles
        $accountTypes = $rebateRule->forexSchemas->pluck('title')->implode(', ');

        // Get IB group names
        $ibGroups = $rebateRule->ibGroups->pluck('name')->implode(', ');

        return [
            $rebateRule->id,
            $rebateRule->title,
            $symbolGroups ?: 'N/A',
            $accountTypes ?: 'N/A',
            $ibGroups ?: 'N/A',
            $rebateRule->rebate_amount,
            $rebateRule->status ? 'Active' : 'Disabled',
            $rebateRule->created_at ? $rebateRule->created_at->format('Y-m-d H:i:s') : '',
        ];
    }
} 