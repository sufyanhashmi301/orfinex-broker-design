<?php

namespace App\Exports;

use App\Models\SymbolGroup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SymbolGroupsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $groups;

    public function __construct($groups)
    {
        $this->groups = $groups;
    }

    public function collection()
    {
        return $this->groups;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Group Name',
            'Symbols',
            'Created At',
            'Updated At'
        ];
    }

    public function map($group): array
    {
        return [
            $group->id,
            $group->title,
            $group->symbols->pluck('symbol')->implode(', '),
            $group->created_at->format('Y-m-d H:i:s'),
            $group->updated_at->format('Y-m-d H:i:s')
        ];
    }
}