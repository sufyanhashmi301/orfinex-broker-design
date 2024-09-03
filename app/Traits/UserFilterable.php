<?php
namespace App\Traits;

use App\Enums\ForexAccountStatus;
use App\Models\ForexAccount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait UserFilterable
{
    public function scopeApplyFilters(Builder $query, $filters)
    {
        if (!empty($filters['global_search'])) {
            $search = $filters['global_search'];
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $filterConditions = [
            'phone' => 'like',
            'country' => 'like',
            'status' => '=',
            'created_at' => '=',
            'tag' => 'like',
        ];

        foreach ($filterConditions as $field => $operator) {
            if (!empty($filters[$field])) {
                if ($field === 'created_at') {
                    $query->whereDate($field, $filters[$field]);
                } else {
                    $query->where($field, $operator, "%{$filters[$field]}%");
                }
            }
        }

        return $query;
    }

    public function scopeApplyBalanceStatusFilter(Builder $query, $balanceStatus)
    {
        if ($balanceStatus !== null) {
            $realForexAccounts = ForexAccount::where('status', ForexAccountStatus::Ongoing)->pluck('login');

            $balanceCondition = $balanceStatus == 1 ? '>' : '=';
            $balanceValue = $balanceStatus == 1 ? 0 : 0;

            $userIds = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $realForexAccounts)
                ->where('Balance', $balanceCondition, $balanceValue)
                ->pluck('Login');

            $userIds = ForexAccount::whereIn('login', $userIds)->pluck('user_id');

            $query->whereIn('id', $userIds);
        }

        return $query;
    }
}
