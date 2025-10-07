<?php

namespace App\Services;

use App\Services\IBTransactionPeriodService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IBTransactionQueryService
{
    /**
     * Get IB transactions for a user from quarter tables (past 1 year)
     *
     * @param int $userId
     * @param array $filters
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getUserIBTransactions($userId, $filters = [])
    {
        // Check if any filters are applied
        $hasFilters = !empty($filters['date_filter']) || 
                     !empty($filters['created_at']) || 
                     !empty($filters['status']) || 
                     !empty($filters['type']) || 
                     !empty($filters['amount_min']) || 
                     !empty($filters['amount_max']) || 
                     !empty($filters['tnx']) || 
                     !empty($filters['description']) || 
                     !empty($filters['login']) || 
                     !empty($filters['deal']) || 
                     !empty($filters['order']) || 
                     !empty($filters['symbol']);
        
        // Get date range (default: past 3 months if no filters, otherwise past 1 year)
        $endDate = Carbon::now();
        if ($hasFilters) {
            $startDate = $endDate->copy()->subYear(); // 1 year for filtered searches
        } else {
            $startDate = $endDate->copy()->subMonths(3); // 3 months for default view
        }
        
        // Handle predefined date filter first
        if (!empty($filters['date_filter'])) {
            $today = Carbon::now();
            
            switch ($filters['date_filter']) {
                case '3_days':
                    $startDate = $today->copy()->subDays(3)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '5_days':
                    $startDate = $today->copy()->subDays(5)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '15_days':
                    $startDate = $today->copy()->subDays(15)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '1_month':
                    $startDate = $today->copy()->subMonth()->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '3_months':
                    $startDate = $today->copy()->subMonths(3)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
            }
        }
        
        // Override with custom date range if provided (takes priority over date_filter)
        if (!empty($filters['created_at'])) {
            $dates = explode(' to ', $filters['created_at']);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
            }
        }
        
        // Get all quarter tables that might contain data in this date range
        $quarterTables = self::getQuarterTablesForDateRange($startDate, $endDate);
        
        if (empty($quarterTables)) {
            // Return empty query if no tables found
            return DB::table('ib_transactions_template')->where('id', 0);
        }
        
        // Build union query for all relevant quarter tables
        $unionQuery = null;
        
        foreach ($quarterTables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            
            $query = DB::table($tableName)
                ->where('user_id', $userId)
                ->where('type', 'ib_bonus')
                ->whereBetween('created_at', [$startDate, $endDate]);
            
            // Apply filters to each table query
            $query = self::applyFilters($query, $filters);
            
            if ($unionQuery === null) {
                $unionQuery = $query;
            } else {
                $unionQuery = $unionQuery->union($query);
            }
        }
        
        // If no valid tables were found, return empty query
        if ($unionQuery === null) {
            return DB::table('ib_transactions_template')->where('id', 0);
        }
        
        return $unionQuery;
    }
    
    /**
     * Get all IB transactions from quarter tables with filters
     *
     * @param array $filters
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getAllIBTransactions($filters = [])
    {
        // Get date range (default: past 1 year)
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subYear();
        
        // Override with custom date range if provided
        if (!empty($filters['created_at'])) {
            $dates = explode(' to ', $filters['created_at']);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
            }
        }
        
        // Get all quarter tables that might contain data in this date range
        $quarterTables = self::getQuarterTablesForDateRange($startDate, $endDate);
        
        if (empty($quarterTables)) {
            // Return empty query if no tables found
            return DB::table('ib_transactions_template')->where('id', 0);
        }
        
        // Build union query for all relevant quarter tables
        $unionQuery = null;
        
        foreach ($quarterTables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            
            $query = DB::table($tableName)
                ->where('type', 'ib_bonus')
                ->whereBetween('created_at', [$startDate, $endDate]);
            
            // Apply filters to each table query
            $query = self::applyFilters($query, $filters);
            
            if ($unionQuery === null) {
                $unionQuery = $query;
            } else {
                $unionQuery = $unionQuery->union($query);
            }
        }
        
        // If no valid tables were found, return empty query
        if ($unionQuery === null) {
            return DB::table('ib_transactions_template')->where('id', 0);
        }
        
        return $unionQuery;
    }
    
    /**
     * Get quarter tables that might contain data for the given date range
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return array
     */
    private static function getQuarterTablesForDateRange($startDate, $endDate)
    {
        $tables = [];
        
        // Get all years in the date range
        $startYear = $startDate->year;
        $endYear = $endDate->year;
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $yearPeriods = IBTransactionPeriodService::getYearPeriods($year);
            
            foreach ($yearPeriods as $period) {
                $periodRange = IBTransactionPeriodService::getPeriodDateRange($period);
                
                // Check if this period overlaps with our date range
                if ($periodRange['start']->lte($endDate) && $periodRange['end']->gte($startDate)) {
                    $tableName = IBTransactionPeriodService::getTableName($period);
                    $tables[] = $tableName;
                }
            }
        }
        
        return $tables;
    }
    
    /**
     * Apply filters to the query
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Query\Builder
     */
    private static function applyFilters($query, $filters)
    {
        // Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // Type filter
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        // Amount range filter
        if (!empty($filters['amount_min'])) {
            $query->where('amount', '>=', $filters['amount_min']);
        }
        
        if (!empty($filters['amount_max'])) {
            $query->where('amount', '<=', $filters['amount_max']);
        }
        
        // Transaction number filter
        if (!empty($filters['tnx'])) {
            $query->where('tnx', 'like', '%' . $filters['tnx'] . '%');
        }
        
        // Description filter
        if (!empty($filters['description'])) {
            $query->where('description', 'like', '%' . $filters['description'] . '%');
        }
        
        // JSON field filters (login, deal, order, symbol)
        foreach (['login', 'deal', 'order', 'symbol'] as $field) {
            if (!empty($filters[$field])) {
                $value = $filters[$field];
                
                if (in_array($field, ['login', 'deal', 'order'])) {
                    // Numeric fields - exact match
                    $query->whereRaw("CAST(JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.\"$field\"')) AS UNSIGNED) = ?", [$value]);
                } else {
                    // Text fields - partial match
                    $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(manual_field_data, '$.\"$field\"')) LIKE ?", ["%$value%"]);
                }
            }
        }
        
        return $query;
    }
    
    /**
     * Get IB transactions summary for a user
     *
     * @param int $userId
     * @param array $filters
     * @return array
     */
    public static function getUserIBTransactionsSummary($userId, $filters = [])
    {
        // Check if any filters are applied (same logic as getUserIBTransactions)
        $hasFilters = !empty($filters['date_filter']) || 
                     !empty($filters['created_at']) || 
                     !empty($filters['status']) || 
                     !empty($filters['type']) || 
                     !empty($filters['amount_min']) || 
                     !empty($filters['amount_max']) || 
                     !empty($filters['tnx']) || 
                     !empty($filters['description']) || 
                     !empty($filters['login']) || 
                     !empty($filters['deal']) || 
                     !empty($filters['order']) || 
                     !empty($filters['symbol']);
        
        // Calculate the filter date range (same logic as getUserIBTransactions)
        $endDate = Carbon::now();
        if ($hasFilters) {
            $startDate = $endDate->copy()->subYear(); // 1 year for filtered searches
        } else {
            $startDate = $endDate->copy()->subMonths(3); // 3 months for default view
        }
        
        // Handle predefined date filter first
        if (!empty($filters['date_filter'])) {
            $today = Carbon::now();
            
            switch ($filters['date_filter']) {
                case '3_days':
                    $startDate = $today->copy()->subDays(3)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '5_days':
                    $startDate = $today->copy()->subDays(5)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '15_days':
                    $startDate = $today->copy()->subDays(15)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '1_month':
                    $startDate = $today->copy()->subMonth()->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
                case '3_months':
                    $startDate = $today->copy()->subMonths(3)->startOfDay();
                    $endDate = $today->copy()->endOfDay();
                    break;
            }
        }
        
        // Override with custom date range if provided (takes priority over date_filter)
        if (!empty($filters['created_at'])) {
            $dates = explode(' to ', $filters['created_at']);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();
            }
        }
        
        // Get all quarter tables that might contain data in this date range
        $quarterTables = self::getQuarterTablesForDateRange($startDate, $endDate);
        
        $query = self::getUserIBTransactions($userId, $filters);
        
        if (!$query) {
            return [
                'total_amount' => 0,
                'total_count' => 0,
                'filter_start_date' => $startDate,
                'filter_end_date' => $endDate,
            ];
        }
        
        // Get summary data - Use individual table queries to avoid union query issues with fromSub
        $totalAmount = 0;
        $totalCount = 0;
        
        foreach ($quarterTables as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            
            $tableQuery = DB::table($tableName)
                ->where('user_id', $userId)
                ->where('type', 'ib_bonus')
                ->whereBetween('created_at', [$startDate, $endDate]);
            
            // Apply filters to each table query
            $tableQuery = self::applyFilters($tableQuery, $filters);
            
            // Get count and sum for this table
            $tableCount = $tableQuery->count();
            $tableSum = $tableQuery->sum('final_amount');
            
            $totalCount += $tableCount;
            $totalAmount += $tableSum;
        }
            
        return [
            'total_amount' => $totalAmount ?? 0,
            'total_count' => $totalCount ?? 0,
            'filter_start_date' => $startDate,
            'filter_end_date' => $endDate,
        ];
    }
    
    /**
     * Get available quarter tables for dropdown/filter
     *
     * @return array
     */
    public static function getAvailableQuarterTables()
    {
        $tables = [];
        $currentYear = Carbon::now()->year;
        
        // Check tables for current year and previous 2 years
        for ($year = $currentYear - 2; $year <= $currentYear; $year++) {
            $periods = IBTransactionPeriodService::getYearPeriods($year);
            
            foreach ($periods as $period) {
                $tableName = IBTransactionPeriodService::getTableName($period);
                
                if (Schema::hasTable($tableName)) {
                    $count = DB::table($tableName)->count();
                    if ($count > 0) {
                        $periodName = IBTransactionPeriodService::getPeriodName($period);
                        $tables[$period] = $periodName . " ({$count} records)";
                    }
                }
            }
        }
        
        return $tables;
    }
}
