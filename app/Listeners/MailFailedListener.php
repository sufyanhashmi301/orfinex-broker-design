<?php

namespace App\Listeners;

use App\Services\Smtp\SmtpFailureDetectionService;
use Illuminate\Mail\Events\MessageFailed;
use Illuminate\Support\Facades\Log;
use Exception;

class MailFailedListener
{
    protected $detectionService;
    
    /**
     * Create the event listener.
     */
    public function __construct(SmtpFailureDetectionService $detectionService)
    {
        $this->detectionService = $detectionService;
    }
    
    /**
     * Handle the event.
     */
    public function handle(MessageFailed $event): void
    {
        try {
            // Extract recipient email
            $recipients = $event->message->getTo();
            $email = !empty($recipients) ? array_key_first($recipients) : 'unknown';
            
            // Log the failure
            $failure = $this->detectionService->logFailure($event->error, [
                'email' => $email,
                'subject' => $event->message->getSubject(),
                'context' => 'Laravel MessageFailed event',
            ]);
            
            // Notify admins
            $this->detectionService->notifyAdmins($failure);
            
        } catch (Exception $e) {
            // Fail silently - don't break the application
            Log::error('SMTP monitoring failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}

