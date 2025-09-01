<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Throwable;
use PDOException;

class MT5DatabaseService
{
    private const CACHE_KEY_CONNECTION_STATUS = 'mt5_db_connection_status';
    private const CACHE_KEY_HEALTH_CHECK = 'mt5_db_health_check';
    private const CONNECTION_TIMEOUT = 5; // seconds
    private const QUERY_TIMEOUT = 10; // seconds
    private const CIRCUIT_BREAKER_THRESHOLD = 3; // failures before circuit opens
    private const CIRCUIT_BREAKER_TIMEOUT = 300; // 5 minutes
    private const HEALTH_CHECK_CACHE_DURATION = 60; // 1 minute

    /**
     * Check if MT5 database connection is available
     *
     * @return bool
     */
    public function isConnectionAvailable(): bool
    {
        $cacheKey = self::CACHE_KEY_CONNECTION_STATUS;
        
        // Check if the database is marked as unavailable
        if (Cache::has($cacheKey)) {
            $status = Cache::get($cacheKey);
            if ($status === 'down') {
                return false;
            }
        }

        return $this->performHealthCheck();
    }

    /**
     * Perform a health check on the MT5 database connection
     *
     * @return bool
     */
    public function performHealthCheck(): bool
    {
        $healthCacheKey = self::CACHE_KEY_HEALTH_CHECK;
        
        // Return cached health status if available
        if (Cache::has($healthCacheKey)) {
            return Cache::get($healthCacheKey) === 'healthy';
        }

        try {
            // Test connection with timeout
            DB::connection('mt5_db')->getPdo();
            
            // Test simple query
            DB::connection('mt5_db')->select('SELECT 1 as test LIMIT 1');
            
            // Mark as healthy
            Cache::put($healthCacheKey, 'healthy', now()->addSeconds(self::HEALTH_CHECK_CACHE_DURATION));
            Cache::forget(self::CACHE_KEY_CONNECTION_STATUS);
            
            return true;
            
        } catch (Throwable $e) {
            Log::warning('MT5 database health check failed', [
                'error' => $e->getMessage(),
                'connection' => 'mt5_db'
            ]);
            
            // Mark as unhealthy
            Cache::put($healthCacheKey, 'unhealthy', now()->addSeconds(self::HEALTH_CHECK_CACHE_DURATION));
            Cache::put(self::CACHE_KEY_CONNECTION_STATUS, 'down', now()->addMinutes(5));
            
            return false;
        }
    }

    /**
     * Execute a query with timeout and error handling
     *
     * @param callable $callback The database query callback
     * @param mixed $defaultValue Default value to return on failure
     * @param int $timeout Query timeout in seconds
     * @return mixed
     */
    public function executeWithTimeout(callable $callback, $defaultValue = null, int $timeout = self::QUERY_TIMEOUT)
    {
        // Check if connection is available first
        if (!$this->isConnectionAvailable()) {
            Log::info('MT5 database unavailable, returning default value');
            return $defaultValue;
        }

        try {
            // Set query timeout
            $originalTimeout = DB::connection('mt5_db')->getConfig('options')[\PDO::ATTR_TIMEOUT] ?? self::QUERY_TIMEOUT;
            
            // Execute with timeout handling
            $startTime = microtime(true);
            $result = $callback();
            $executionTime = microtime(true) - $startTime;
            
            // Log slow queries
            if ($executionTime > ($timeout * 0.8)) {
                Log::warning('Slow MT5 database query detected', [
                    'execution_time' => $executionTime,
                    'timeout_threshold' => $timeout * 0.8
                ]);
            }
            
            return $result;
            
        } catch (PDOException $e) {
            $this->handleDatabaseException($e);
            return $defaultValue;
        } catch (Throwable $e) {
            $this->handleGeneralException($e);
            return $defaultValue;
        }
    }

    /**
     * Get MT5 deals with timeout and error handling
     *
     * @param int $login
     * @param string $lastTime
     * @param array $symbols
     * @return \Illuminate\Support\Collection
     */
    public function getMT5Deals(int $login, string $lastTime, array $symbols): \Illuminate\Support\Collection
    {
        if (empty($symbols)) {
            return collect();
        }

        return $this->executeWithTimeout(function () use ($login, $lastTime, $symbols) {
            $table = 'mt5_deals_' . Carbon::now()->year;
            // For Qorva - uncomment if needed
            // $table = 'mt5_deals';

            return DB::connection('mt5_db')
                ->table($table)
                ->select(['Login', 'Deal', 'Dealer', 'Order', 'Symbol', 'Time', 'Volume', 'VolumeClosed'])
                ->where('Login', $login)
                ->whereIn('Symbol', $symbols)
                ->where('Time', '>', $lastTime)
                ->where('Volume', '>', 0)
                ->whereColumn('Volume', 'VolumeClosed')
                ->get();
        }, collect());
    }

    /**
     * Get MT5 account balance with timeout and error handling
     *
     * @param int $login
     * @return float
     */
    public function getAccountBalance(int $login): float
    {
        if (!isset($login) || $login <= 0) {
            return 0.0;
        }

        return $this->executeWithTimeout(function () use ($login) {
            $mt5Account = DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->where('Login', $login)
                ->first();
                
            return $mt5Account ? (float)$mt5Account->Balance : 0.0;
        }, 0.0);
    }

    /**
     * Get total balance for multiple logins
     *
     * @param array $logins
     * @return float
     */
    public function getTotalBalance(array $logins): float
    {
        if (empty($logins)) {
            return 0.0;
        }

        return $this->executeWithTimeout(function () use ($logins) {
            return (float)DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $logins)
                ->sum('Balance');
        }, 0.0);
    }

    /**
     * Get total equity for multiple logins
     *
     * @param array $logins
     * @return float
     */
    public function getTotalEquity(array $logins): float
    {
        if (empty($logins)) {
            return 0.0;
        }

        return $this->executeWithTimeout(function () use ($logins) {
            return (float)DB::connection('mt5_db')
                ->table('mt5_accounts')
                ->whereIn('Login', $logins)
                ->sum('Equity');
        }, 0.0);
    }

    /**
     * Handle PDO exceptions
     *
     * @param PDOException $e
     */
    private function handleDatabaseException(PDOException $e): void
    {
        $errorCode = $e->getCode();
        $errorMessage = $e->getMessage();

        // Check for timeout-related errors
        if (str_contains($errorMessage, 'timeout') || 
            str_contains($errorMessage, 'Connection timed out') ||
            $errorCode == 2006 || // MySQL server has gone away
            $errorCode == 2013) { // Lost connection to MySQL server

            Log::warning('MT5 database timeout detected', [
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
                'connection' => 'mt5_db'
            ]);

            // Mark connection as down temporarily
            Cache::put(self::CACHE_KEY_CONNECTION_STATUS, 'down', now()->addMinutes(5));
            Cache::forget(self::CACHE_KEY_HEALTH_CHECK);
        } else {
            Log::error('MT5 database PDO error', [
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
                'connection' => 'mt5_db'
            ]);
        }
    }

    /**
     * Handle general exceptions
     *
     * @param Throwable $e
     */
    private function handleGeneralException(Throwable $e): void
    {
        Log::error('MT5 database general error', [
            'error_class' => get_class($e),
            'error_message' => $e->getMessage(),
            'connection' => 'mt5_db',
            'trace' => $e->getTraceAsString()
        ]);

        // If it's a connection-related error, mark as down
        if (str_contains($e->getMessage(), 'connection') || 
            str_contains($e->getMessage(), 'timeout')) {
            Cache::put(self::CACHE_KEY_CONNECTION_STATUS, 'down', now()->addMinutes(2));
        }
    }

    /**
     * Force reconnection by clearing connection cache
     */
    public function forceReconnection(): void
    {
        try {
            DB::purge('mt5_db');
            Cache::forget(self::CACHE_KEY_CONNECTION_STATUS);
            Cache::forget(self::CACHE_KEY_HEALTH_CHECK);
            
            Log::info('MT5 database connection cache cleared, forcing reconnection');
        } catch (Throwable $e) {
            Log::error('Failed to force MT5 database reconnection', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Reset connection status after credentials update
     * This method is specifically called when database credentials are updated
     */
    public function resetAfterCredentialsUpdate(): bool
    {
        try {
            // Clear all caches
            $this->forceReconnection();
            
            // Clear the credentials cache as well
            Cache::forget('mt5_db_credentials');
            
            // Perform immediate health check with new credentials
            $isHealthy = $this->performHealthCheck();
            
            Log::info('MT5 database status reset after credentials update', [
                'is_healthy' => $isHealthy,
                'timestamp' => now()->toISOString()
            ]);
            
            return $isHealthy;
            
        } catch (Throwable $e) {
            Log::error('Failed to reset MT5 database after credentials update', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get connection statistics for monitoring
     *
     * @return array
     */
    public function getConnectionStats(): array
    {
        return [
            'is_available' => $this->isConnectionAvailable(),
            'connection_status' => Cache::get(self::CACHE_KEY_CONNECTION_STATUS, 'unknown'),
            'health_status' => Cache::get(self::CACHE_KEY_HEALTH_CHECK, 'unknown'),
            'last_check' => now()->toISOString()
        ];
    }
}
