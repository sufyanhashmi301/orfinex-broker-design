<?php

namespace App\Console\Commands;

use App\Services\Smtp\SmtpFailureDetectionService;
use Illuminate\Console\Command;

class CheckSmtpHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smtp:health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check SMTP connection health and log failures';

    protected $detectionService;

    /**
     * Create a new command instance.
     */
    public function __construct(SmtpFailureDetectionService $detectionService)
    {
        parent::__construct();
        $this->detectionService = $detectionService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if monitoring is enabled from database settings
        $enabled = setting('smtp_monitoring_enabled', 'smtp_monitoring') ?? true;
        if (!$enabled) {
            $this->info('SMTP monitoring is disabled');
            return 0;
        }
        
        // Check if health checks are enabled
        $healthCheckEnabled = setting('smtp_health_check_enabled', 'smtp_monitoring') ?? false;
        if (!$healthCheckEnabled) {
            $this->info('SMTP health checks are disabled');
            return 0;
        }

        $this->info('Checking SMTP connection health...');

        $isHealthy = $this->detectionService->testSmtpConnection();

        if ($isHealthy) {
            $this->info('✅ SMTP connection healthy');
            return 0;
        }

        $this->error('❌ SMTP connection failed');
        $this->warn('Check logs for details');
        
        return 1;
    }
}

