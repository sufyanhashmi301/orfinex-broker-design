<?php

namespace App\Services\Smtp;

use App\Models\SmtpFailureLog;
use App\Traits\NotifyTrait;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SmtpFailureDetectionService
{
    use NotifyTrait;
    
    /**
     * Log SMTP failure to database
     */
    public function logFailure(Exception $e, array $context = []): SmtpFailureLog
    {
        return SmtpFailureLog::create([
            'error_message' => $e->getMessage(),
            'error_code' => $e->getCode(),
            'email_template' => $context['template_code'] ?? null,
            'recipient' => $context['email'] ?? null,
            'shortcodes' => $context['shortcodes'] ?? null,
            'stack_trace' => $e->getTraceAsString(),
            'context' => $context,
        ]);
    }
    
    /**
     * Notify admins about SMTP failure
     */
    public function notifyAdmins(SmtpFailureLog $failure): void
    {
        // ALWAYS set/update session for toast alert
        if (!session('smtp_failure_active')) {
            $this->setFailureSession($failure);
        } else {
            $this->updateFailureSession();
        }
        
        // Check if we should send push notification (respects cooldown period)
        if (!$this->shouldAlert()) {
            return;
        }
        
        // Get recent failure count
        $count = SmtpFailureLog::recent()->count();
        
        // Select appropriate template based on failure count
        $threshold = setting('smtp_failure_threshold', 'smtp_monitoring') ?? 3;
        $template = $count >= $threshold 
            ? 'smtp_multiple_failures' 
            : 'smtp_failure_detected';
        
        // Prepare shortcodes
        $shortCodes = [
            '[[error_message]]' => substr($failure->error_message, 0, 100),
            '[[failure_count]]' => $count,
            '[[timestamp]]' => now()->format('Y-m-d H:i:s'),
        ];
        
        // Send push notification to admins
        try {
            $this->pushNotify(
                $template,
                $shortCodes,
                route('admin.smtp.monitoring.logs'),
                null,
                'error'
            );
            
            // Set cooldown to prevent alert spam
            $cooldown = setting('smtp_alert_cooldown', 'smtp_monitoring') ?? 1800;
            Cache::put('smtp_last_alert', now(), now()->addSeconds($cooldown));
            
        } catch (Exception $e) {
            Log::error('Failed to send SMTP failure notification', [
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Check if we should send alert (respects cooldown)
     */
    private function shouldAlert(): bool
    {
        // Check if monitoring is enabled (from database settings)
        $enabled = setting('smtp_monitoring_enabled', 'smtp_monitoring') ?? true;
        if (!$enabled) {
            return false;
        }
        
        $lastAlert = Cache::get('smtp_last_alert');
        
        if (!$lastAlert) {
            return true;
        }
        
        // Get cooldown from database settings
        $cooldown = setting('smtp_alert_cooldown', 'smtp_monitoring') ?? 1800;
        return $lastAlert->diffInSeconds(now()) >= $cooldown;
    }
    
    /**
     * Test SMTP connection
     */
    public function testSmtpConnection(): bool
    {
        try {
            $transport = app('mail.manager')->createSymfonyTransport(config('mail.mailers.smtp'));
            $transport->start();
            $transport->stop();
            
            // Clear failure session on successful connection
            $this->clearFailureSession();
            
            return true;
            
        } catch (Exception $e) {
            // Log the health check failure
            $this->logFailure($e, [
                'type' => 'health_check',
                'context' => 'Scheduled SMTP health monitoring',
            ]);
            
            return false;
        }
    }
    
    /**
     * Get recent failure count
     */
    public function getRecentFailureCount(): int
    {
        return SmtpFailureLog::recent()->count();
    }
    
    /**
     * Set session flag for SMTP failure alert
     */
    public function setFailureSession(SmtpFailureLog $failure): void
    {
        session([
            'smtp_failure_active' => true,
            'smtp_failure_message' => substr($failure->error_message, 0, 100),
            'smtp_failure_count' => SmtpFailureLog::recent()->count(),
            'smtp_failure_timestamp' => now()->toDateTimeString(),
            'smtp_failure_last_updated' => now()->toDateTimeString(),
        ]);
    }
    
    /**
     * Update session count without creating new alert
     */
    public function updateFailureSession(): void
    {
        if (session('smtp_failure_active')) {
            session([
                'smtp_failure_count' => SmtpFailureLog::recent()->count(),
                'smtp_failure_last_updated' => now()->toDateTimeString(),
            ]);
        }
    }
    
    /**
     * Clear session flag when SMTP recovers
     */
    public function clearFailureSession(): void
    {
        session()->forget([
            'smtp_failure_active',
            'smtp_failure_message',
            'smtp_failure_count',
            'smtp_failure_timestamp',
            'smtp_failure_last_updated',
        ]);
    }
    
    /**
     * Check if there's an active SMTP failure
     */
    public function hasActiveFailure(): bool
    {
        return session('smtp_failure_active', false);
    }
}

