<?php

namespace App\Console\Commands;

use App\Services\Smtp\SmtpFailureDetectionService;
use Exception;
use Illuminate\Console\Command;

class TestSmtpFailureAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smtp:test-failure-alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMTP failure detection and alert system';

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
        $this->info('🧪 Simulating SMTP failure...');
        
        // Create a simulated exception
        $exception = new Exception('TEST: Connection refused to SMTP server smtp.example.com:587');
        
        // Log the failure
        $failure = $this->detectionService->logFailure($exception, [
            'email' => 'test@example.com',
            'template_code' => 'test_notification',
            'context' => 'Test simulation via command',
            'type' => 'test',
        ]);
        
        $this->line('✅ Failure logged with ID: ' . $failure->id);
        
        // Notify admins (this triggers push notification)
        $this->info('📤 Sending push notification to admins...');
        $this->detectionService->notifyAdmins($failure);
        
        $this->newLine();
        $this->info('✅ Test complete!');
        $this->line('📊 Check:');
        $this->line('   1. Admin dashboard for alert button');
        $this->line('   2. Browser notification (if admin is logged in)');
        $this->line('   3. Notification center bell icon');
        $this->line('   4. SMTP logs page: /admin/smtp/monitoring/logs');
        
        return 0;
    }
}

