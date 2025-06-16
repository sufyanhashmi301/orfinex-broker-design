<?php
namespace App\Exports;

use App\Enums\TxnType;
use App\Enums\TxnStatus;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ibTransactionsUsersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;
protected $userId;
    protected $filters;

    public function __construct($userId, $filters = [])
    {
        $this->userId = $userId;
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Transaction::where('user_id', $this->userId)
            ->where('type', TxnType::IbBonus->value);

        // Apply the same filters as in your ibBonus method
        if (!empty($this->filters['created_at'])) {
            $dates = explode(' to ', $this->filters['created_at']);
            if (count($dates) == 2) {
                $start = Carbon::parse($dates[0])->startOfDay();
                $end = Carbon::parse($dates[1])->endOfDay();
                $query->where(function($query) use ($start, $end) {
                    $query->where(function($q) use ($start, $end) {
                        $q->whereRaw("JSON_EXTRACT(manual_field_data, '$.time') IS NOT NULL")
                          ->whereBetween(DB::raw("STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.time')), '%Y-%m-%dT%H:%i:%s.000000Z')"), [$start, $end]);
                    })->orWhereBetween('created_at', [$start, $end]);
                });
            }
        }

        if (!empty($this->filters['date_filter'])) {
            $filter = $this->filters['date_filter'];
            $dateRange = match ($filter) {
                '3_days' => [Carbon::now()->subDays(3)->startOfDay(), Carbon::now()->endOfDay()],
                '5_days' => [Carbon::now()->subDays(5)->startOfDay(), Carbon::now()->endOfDay()],
                '15_days' => [Carbon::now()->subDays(15)->startOfDay(), Carbon::now()->endOfDay()],
                '1_month' => [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->endOfDay()],
                '3_months' => [Carbon::now()->subMonths(3)->startOfDay(), Carbon::now()->endOfDay()],
                default => null,
            };

            if ($dateRange) {
                $query->where(function($query) use ($dateRange) {
                    $start = $dateRange[0]->toDateTimeString();
                    $end = $dateRange[1]->toDateTimeString();
                    $query->where(function($q) use ($start, $end) {
                        $q->whereRaw("JSON_EXTRACT(manual_field_data, '$.time') IS NOT NULL")
                          ->whereBetween(DB::raw("STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.time')), '%Y-%m-%dT%H:%i:%s.000000Z')"), [$start, $end]);
                    })->orWhereBetween('created_at', [$start, $end]);
                });
            }
        }

        // Field filters
        foreach (['login', 'deal', 'symbol'] as $field) {
            if (!empty($this->filters[$field])) {
                $value = $this->filters[$field];
                if (in_array($field, ['login', 'deal'])) {
                    $query->whereRaw("CAST(JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.\"$field\"')) AS UNSIGNED) = ?", [$value]);
                } else {
                    $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.\"$field\"')) LIKE ?", ["%$value%"]);
                }
            }
        }

        return $query->latest();
    }


    public function headings(): array
    {
        return [
            'Date', 
            'Transaction ID',
            'Type',
            'Account',
            'Amount',
            'Gateway',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->created_at,
            $transaction->tnx,
            $transaction->type ? $transaction->type->label() : 'Unknown',
            $transaction->target_id,
            $transaction->final_amount,
            $transaction->method,
            $transaction->status ? $transaction->status->label() : 'Unknown',
        ];
    }
}
