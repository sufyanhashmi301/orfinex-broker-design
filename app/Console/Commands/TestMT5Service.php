<?php

namespace App\Console\Commands;

use App\Services\MT5DatabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TestMT5Service extends Command
{
    protected $signature = 'test:mt5-service';
    protected $description = 'Test MT5DatabaseService registration and functionality';

    public function handle()
    {
        $this->info('Testing MT5DatabaseService registration...');
        
        try {
            // Test service binding
            $isBound = app()->bound(MT5DatabaseService::class);
            $this->info("Service bound: " . ($isBound ? 'YES' : 'NO'));
            
            // Test service resolution
            $service = app(MT5DatabaseService::class);
            $this->info("Service resolved: " . (is_object($service) ? 'YES' : 'NO'));
            $this->info("Service class: " . get_class($service));
            
            // Test service methods
            $stats = $service->getConnectionStats();
            $this->info("Connection stats: " . json_encode($stats, JSON_PRETTY_PRINT));
            
            // Test health check
            $isHealthy = $service->isConnectionAvailable();
            $this->info("Connection available: " . ($isHealthy ? 'YES' : 'NO'));
            
            $this->info('✅ MT5DatabaseService test completed successfully');
            
        } catch (\Exception $e) {
            $this->error('❌ MT5DatabaseService test failed: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            Log::error('MT5DatabaseService test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
        
        return 0;
    }
}
