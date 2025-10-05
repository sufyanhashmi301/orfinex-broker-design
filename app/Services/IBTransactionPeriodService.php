<?php

namespace App\Services;

use Carbon\Carbon;

class IBTransactionPeriodService
{
    /**
     * Get the current 4-month period identifier
     * 
     * @param Carbon|null $date
     * @return string
     */
    public static function getCurrentPeriod($date = null)
    {
        $date = $date ?? Carbon::now();
        $year = $date->year;
        $month = $date->month;
        
        // Determine which 4-month period we're in
        if ($month >= 1 && $month <= 4) {
            return $year . '_q1'; // January-April
        } elseif ($month >= 5 && $month <= 8) {
            return $year . '_q2'; // May-August
        } else {
            return $year . '_q3'; // September-December
        }
    }
    
    /**
     * Get the next 4-month period identifier
     * 
     * @param string|null $currentPeriod
     * @return string
     */
    public static function getNextPeriod($currentPeriod = null)
    {
        $currentPeriod = $currentPeriod ?? self::getCurrentPeriod();
        
        [$year, $quarter] = explode('_', $currentPeriod);
        $year = (int) $year;
        
        switch ($quarter) {
            case 'q1':
                return $year . '_q2';
            case 'q2':
                return $year . '_q3';
            case 'q3':
                return ($year + 1) . '_q1';
            default:
                throw new \InvalidArgumentException("Invalid period format: {$currentPeriod}");
        }
    }
    
    /**
     * Get the previous 4-month period identifier
     * 
     * @param string|null $currentPeriod
     * @return string
     */
    public static function getPreviousPeriod($currentPeriod = null)
    {
        $currentPeriod = $currentPeriod ?? self::getCurrentPeriod();
        
        [$year, $quarter] = explode('_', $currentPeriod);
        $year = (int) $year;
        
        switch ($quarter) {
            case 'q1':
                return ($year - 1) . '_q3';
            case 'q2':
                return $year . '_q1';
            case 'q3':
                return $year . '_q2';
            default:
                throw new \InvalidArgumentException("Invalid period format: {$currentPeriod}");
        }
    }
    
    /**
     * Get table name for a specific period
     * 
     * @param string|null $period
     * @return string
     */
    public static function getTableName($period = null)
    {
        $period = $period ?? self::getCurrentPeriod();
        return 'ib_transactions_' . $period;
    }
    
    /**
     * Get the start and end dates for a period
     * 
     * @param string $period
     * @return array
     */
    public static function getPeriodDateRange($period)
    {
        [$year, $quarter] = explode('_', $period);
        $year = (int) $year;
        
        switch ($quarter) {
            case 'q1':
                return [
                    'start' => Carbon::create($year, 1, 1)->startOfDay(),
                    'end' => Carbon::create($year, 4, 30)->endOfDay()
                ];
            case 'q2':
                return [
                    'start' => Carbon::create($year, 5, 1)->startOfDay(),
                    'end' => Carbon::create($year, 8, 31)->endOfDay()
                ];
            case 'q3':
                return [
                    'start' => Carbon::create($year, 9, 1)->startOfDay(),
                    'end' => Carbon::create($year, 12, 31)->endOfDay()
                ];
            default:
                throw new \InvalidArgumentException("Invalid period format: {$period}");
        }
    }
    
    /**
     * Check if we should create the next period table
     * This checks if we're in the last month of current period
     * 
     * @param Carbon|null $date
     * @return bool
     */
    public static function shouldCreateNextPeriodTable($date = null)
    {
        $date = $date ?? Carbon::now();
        $month = $date->month;
        
        // Create next table in the last month of each period
        return in_array($month, [4, 8, 12]);
    }
    
    /**
     * Get all periods for a given year
     * 
     * @param int $year
     * @return array
     */
    public static function getYearPeriods($year)
    {
        return [
            $year . '_q1',
            $year . '_q2',
            $year . '_q3'
        ];
    }
    
    /**
     * Parse period string to get year and quarter number
     * 
     * @param string $period
     * @return array
     */
    public static function parsePeriod($period)
    {
        [$year, $quarter] = explode('_', $period);
        $quarterNumber = (int) str_replace('q', '', $quarter);
        
        return [
            'year' => (int) $year,
            'quarter' => $quarterNumber,
            'quarter_string' => $quarter
        ];
    }
    
    /**
     * Get period for a specific transaction date (used during migration)
     * 
     * @param Carbon $transactionDate
     * @return string
     */
    public static function getPeriodForTransaction($transactionDate)
    {
        if (!$transactionDate instanceof Carbon) {
            $transactionDate = Carbon::parse($transactionDate);
        }
        
        return self::getCurrentPeriod($transactionDate);
    }
    
    /**
     * Ensure quarter table exists for a given date
     * 
     * @param Carbon|string $date
     * @return bool
     */
    public static function ensureTableExistsForDate($date)
    {
        $period = self::getCurrentPeriod($date);
        $tableName = self::getTableName($period);
        
        if (\Illuminate\Support\Facades\Schema::hasTable($tableName)) {
            return true;
        }
        
        // Auto-create table if it doesn't exist
        return \App\Services\IBTransactionService::createIBTransactionTable4Month($period);
    }
    
    /**
     * Get human readable period name
     * 
     * @param string $period
     * @return string
     */
    public static function getPeriodName($period)
    {
        $parsed = self::parsePeriod($period);
        $year = $parsed['year'];
        $quarter = $parsed['quarter'];
        
        switch ($quarter) {
            case 1:
                return "Q1 {$year} (Jan-Apr)";
            case 2:
                return "Q2 {$year} (May-Aug)";
            case 3:
                return "Q3 {$year} (Sep-Dec)";
            default:
                return "Unknown Period";
        }
    }
}

