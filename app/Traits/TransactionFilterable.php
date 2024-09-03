<?php
namespace App\Traits;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait TransactionFilterable
{
    public function scopeApplyFilters(Builder $query, $filters)
    {

        if (!empty($filters['email'])) {
            $query->whereHas('user', function ($query) use ($filters) {
                $query->where('email', $filters['email']);
            });
        }

        
        if (isset($filters['type']) && $filters['type'] !== '') {
            $typeEnum = TxnType::tryFrom($filters['type']);  // Convert string to enum
            if ($typeEnum) {
                $query->where('type', $typeEnum->value);
            }
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $statusEnum = TxnStatus::tryFrom($filters['status']);  // Convert string to enum
            if ($statusEnum) {
                $query->where('status', $statusEnum->value);
            }
        }

        if (!empty($filters['created_at'])) {
            $query->whereDate('created_at', $filters['created_at']);
        }

        return $query;
    }
}
