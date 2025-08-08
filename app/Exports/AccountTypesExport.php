<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\ForexSchema;

class AccountTypesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $schemas;

    public function __construct($schemas)
    {
        $this->schemas = $schemas;
    }

    public function collection()
    {
        return $this->schemas;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Trader Type',
            'Leverage',
            'Badge',
            'Commission',
            'Spread',
            'Status',
            'Priority',
            'Account Category',
            'Countries',
            'Tags',
            'Is Global',
            'Created At',
            'Updated At'
        ];
    }

    public function map($schema): array
    {
        $countries = $schema->country ? implode(', ', json_decode($schema->country, true)) : '';
        $tags = $schema->tags ? implode(', ', json_decode($schema->tags, true)) : '';
        $category = $schema->accountCategories ? $schema->accountCategories->name : '';

        return [
            $schema->id,
            $schema->title,
            $schema->trader_type,
            $schema->leverage,
            $schema->badge ?: 'No Feature Badge',
            $schema->commission,
            $schema->spread,
            $schema->status ? 'Active' : 'Deactivated',
            $schema->priority,
            $category,
            $countries,
            $tags,
            $schema->is_global ? 'Yes' : 'No',
            $schema->created_at->format('Y-m-d H:i:s'),
            $schema->updated_at->format('Y-m-d H:i:s')
        ];
    }
}