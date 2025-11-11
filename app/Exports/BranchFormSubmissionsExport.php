<?php

namespace App\Exports;

use App\Models\BranchFormSubmission;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BranchFormSubmissionsExport implements FromCollection, WithHeadings
{
    protected string $status;
    protected array $filters;

    public function __construct(string $status, array $filters = [])
    {
        $this->status = $status;
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = BranchFormSubmission::with(['user','branch'])
            ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status));

        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->whereHas('user', function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if (!empty($this->filters['branch'])) {
            $branchSearch = $this->filters['branch'];
            $query->whereHas('branch', function ($q) use ($branchSearch) {
                $q->where('name', 'like', "%{$branchSearch}%");
            });
        }
        if (!empty($this->filters['created_at'])) {
            $range = explode(' to ', str_replace(' - ', ' to ', $this->filters['created_at']));
            $from = $range[0] ?? null;
            $to = $range[1] ?? $range[0] ?? null;
            if ($from) $query->whereDate('created_at', '>=', $from);
            if ($to) $query->whereDate('created_at', '<=', $to);
        }

        return $query->get()->map(function ($row) {
            return [
                'Date' => $row->created_at->format('Y-m-d H:i'),
                'User' => optional($row->user)->full_name ?: optional($row->user)->username,
                'Email' => optional($row->user)->email,
                'Branch' => optional($row->branch)->name,
                'Status' => ucfirst($row->status),
            ];
        });
    }

    public function headings(): array
    {
        return ['Date', 'User', 'Email', 'Branch', 'Status'];
    }
}


