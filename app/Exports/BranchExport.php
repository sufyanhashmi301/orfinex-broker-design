<?php

namespace App\Exports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BranchExport implements FromCollection, WithHeadings, WithMapping
{
    protected $branches;

    public function __construct($branches)
    {
        $this->branches = $branches;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->branches;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Branch Name',
            'Code',
            'Status',
            'Users Count',
            'Staff Count',
            'Created At',
            'Updated At',
        ];
    }

    /**
     * @param mixed $branch
     * @return array
     */
    public function map($branch): array
    {
        return [
            $branch->id,
            $branch->name,
            $branch->code,
            $branch->status ? 'Active' : 'Disabled',
            $branch->users()->count(),
            $branch->admins()->count(),
            $branch->created_at->format('Y-m-d H:i:s'),
            $branch->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}