<?php

namespace App\Console\Commands;

use App\Services\MT5DatabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MT5DatabaseHealthCheck extends Command
{
    protected $signature = 'mt5-db:health-check 
                           {--force : Force health check even if cached} 
                           {--reset : Reset circuit breaker status}';
    
    protected $description = 'Check MT5 database connection health and manage circuit breaker';

    protected MT5DatabaseService $mt5DatabaseService;

    public function __construct(MT5DatabaseService $mt5DatabaseService)
    {
        parent::__construct();
        $this->mt5DatabaseService = $mt5DatabaseService;
    }

    public function handle()
    {
        if ($this->option('reset')) {
            $this->resetCircuitBreaker();
            return 0;
        }

        $this->info('Checking MT5 database health...');

        $force = $this->option('force');
        
        if ($force) {
            $this->mt5DatabaseService->forceReconnection();
        }

        $isHealthy = $this->mt5DatabaseService->performHealthCheck();
        $stats = $this->mt5DatabaseService->getConnectionStats();

        $this->displayHealthStatus($isHealthy, $stats);

        if (!$isHealthy) {
            $this->error('MT5 database is unhealthy!');
            Log::warning('MT5 database health check failed', $stats);
            return 1;
        }

        $this->info('MT5 database is healthy.');
        return 0;
    }

    private function resetCircuitBreaker(): void
    {
        $this->mt5DatabaseService->forceReconnection();
        $this->info('Circuit breaker has been reset. Connection cache cleared.');
        
        // Perform immediate health check after reset
        $isHealthy = $this->mt5DatabaseService->performHealthCheck();
        $this->info($isHealthy ? 'Health check passed after reset.' : 'Health check still failing after reset.');
    }

    private function displayHealthStatus(bool $isHealthy, array $stats): void
    {
        $this->table(
            ['Metric', 'Status'],
            [
                ['Overall Health', $isHealthy ? '✅ Healthy' : '❌ Unhealthy'],
                ['Connection Available', $stats['is_available'] ? '✅ Yes' : '❌ No'],
                ['Connection Status', $this->formatStatus($stats['connection_status'])],
                ['Health Status', $this->formatStatus($stats['health_status'])],
                ['Last Check', $stats['last_check']],
            ]
        );
    }

    private function formatStatus(string $status): string
    {
        return match($status) {
            'healthy' => '✅ Healthy',
            'unhealthy' => '❌ Unhealthy',
            'down' => '🔴 Down',
            'up' => '🟢 Up',
            'unknown' => '❓ Unknown',
            default => "❓ {$status}"
        };
    }
}
