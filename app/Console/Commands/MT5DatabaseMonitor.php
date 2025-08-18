<?php

namespace App\Console\Commands;

use App\Services\MT5DatabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MT5DatabaseMonitor extends Command
{
    protected $signature = 'mt5-db:monitor 
                           {--interval=60 : Check interval in seconds}
                           {--max-failures=5 : Maximum failures before alerting}';
    
    protected $description = 'Continuously monitor MT5 database connection and log issues';

    protected MT5DatabaseService $mt5DatabaseService;

    public function __construct(MT5DatabaseService $mt5DatabaseService)
    {
        parent::__construct();
        $this->mt5DatabaseService = $mt5DatabaseService;
    }

    public function handle()
    {
        $interval = (int) $this->option('interval');
        $maxFailures = (int) $this->option('max-failures');
        $failureCount = 0;
        $lastStatus = null;

        $this->info("Starting MT5 database monitoring (interval: {$interval}s, max failures: {$maxFailures})");
        $this->info('Press Ctrl+C to stop monitoring...');

        while (true) {
            $isHealthy = $this->mt5DatabaseService->performHealthCheck();
            $stats = $this->mt5DatabaseService->getConnectionStats();
            $currentTime = now()->format('Y-m-d H:i:s');

            if (!$isHealthy) {
                $failureCount++;
                $this->error("[{$currentTime}] Health check failed (failure {$failureCount}/{$maxFailures})");
                
                if ($failureCount >= $maxFailures) {
                    $this->error("Maximum failures reached! Sending alert...");
                    $this->sendAlert($stats);
                    $failureCount = 0; // Reset counter after alerting
                }
            } else {
                if ($lastStatus === false) {
                    $this->info("[{$currentTime}] Database recovered - connection is healthy again");
                    Log::info('MT5 database recovered', $stats);
                }
                $failureCount = 0; // Reset failure count on success
            }

            $lastStatus = $isHealthy;

            // Display current status
            $statusEmoji = $isHealthy ? '✅' : '❌';
            $this->line("[{$currentTime}] {$statusEmoji} Status: " . ($isHealthy ? 'Healthy' : 'Unhealthy'));

            sleep($interval);
        }
    }

    private function sendAlert(array $stats): void
    {
        $message = 'MT5 Database Connection Alert: Multiple consecutive failures detected';
        
        Log::critical($message, [
            'stats' => $stats,
            'timestamp' => now()->toISOString(),
            'alert_type' => 'database_connection_failure'
        ]);

        // Here you could add additional alerting mechanisms:
        // - Send email notifications
        // - Send Slack/Discord webhooks
        // - Trigger monitoring system alerts
        // - Send SMS notifications
        
        $this->error('🚨 ALERT: ' . $message);
    }
}
