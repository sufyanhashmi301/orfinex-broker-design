<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SmtpFailureLog;
use App\Services\Smtp\SmtpFailureDetectionService;
use App\Traits\NotifyTrait;
use Illuminate\Http\Request;

class SmtpMonitoringController extends Controller
{
    use NotifyTrait;
    /**
     * Display SMTP failure logs
     */
    public function logs(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, failed, resent, today
        
        $query = SmtpFailureLog::with(['user', 'emailTemplateRelation']);
        
        // Apply filter
        if ($filter === 'failed') {
            $query->failed(); // Only non-resent logs
        } elseif ($filter === 'resent') {
            $query->resent(); // Only resent logs
        } elseif ($filter === 'today') {
            $query->whereDate('created_at', today());
        }
        
        $logs = $query->latest()->paginate(25);
        
        $stats = [
            'total' => SmtpFailureLog::count(),
            'failed' => SmtpFailureLog::failed()->count(),
            'resent' => SmtpFailureLog::resent()->count(),
            'today' => SmtpFailureLog::whereDate('created_at', today())->count(),
        ];
        
        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            $html = view('backend.smtp.monitoring.__logs_table', compact('logs'))->render();
            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }
        
        return view('backend.smtp.monitoring.logs', compact('logs', 'stats', 'filter'));
    }
    
    /**
     * Clear SMTP failure alert session
     * Supports both GET and POST methods for flexibility
     */
    public function clearAlert(Request $request, SmtpFailureDetectionService $service)
    {
        $service->clearFailureSession();
        
        // Return JSON for AJAX requests, redirect for direct access
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'SMTP failure alert cleared successfully'
            ]);
        }
        
        return redirect()->back()->with('success', 'SMTP failure alert cleared successfully');
    }
    
    /**
     * Test alert in web context (creates web session)
     */
    public function testAlert(SmtpFailureDetectionService $service)
    {
        try {
            $e = new \Exception('Test SMTP failure triggered from web interface');
            
            $failure = $service->logFailure($e, [
                'email' => 'test@example.com',
                'template_code' => 'web_test',
                'context' => 'Web-based test trigger',
            ]);
            
            $service->notifyAdmins($failure);
            
            return redirect()->back()->with('success', 'SMTP alert triggered! Check for the red toast alert.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to trigger alert: ' . $e->getMessage());
        }
    }
    
    /**
     * Debug session data
     */
    public function debugSession()
    {
        $isAdmin = false;
        if (auth()->check()) {
            $role = strtolower(auth()->user()->role);
            $isAdmin = in_array($role, ['admin', 'super-admin']);
        }
        
        $debug = [
            'auth' => [
                'logged_in' => auth()->check(),
                'user_id' => auth()->id(),
                'user_role' => auth()->check() ? auth()->user()->role : 'N/A',
                'is_admin' => $isAdmin,
            ],
            'session_raw' => [
                'smtp_failure_active' => session('smtp_failure_active'),
                'smtp_failure_message' => session('smtp_failure_message'),
                'smtp_failure_count' => session('smtp_failure_count'),
                'smtp_failure_timestamp' => session('smtp_failure_timestamp'),
                'smtp_failure_last_updated' => session('smtp_failure_last_updated'),
            ],
            'all_session_keys' => array_keys(session()->all()),
        ];
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    }
    
    /**
     * Show SMTP monitoring settings page
     */
    public function settings()
    {
        return view('backend.smtp.monitoring.settings');
    }
    
    /**
     * Resend failed email
     */
    public function resendEmail($id)
    {
        try {
            $log = SmtpFailureLog::find($id);
            
            if (!$log) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log not found'
                ], 404);
            }
            
            // Check if we have template code to resend
            if (!$log->email_template) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot resend: No template information available'
                ], 422);
            }
            
            // Check if we have recipient
            if (!$log->recipient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot resend: No recipient information available'
                ], 422);
            }
            
            // Extract shortcodes from dedicated column (preferred) or fallback to context
            $shortcodes = $log->shortcodes ?? [];
            
            // Fallback to context if shortcodes column is empty (for old records)
            if (empty($shortcodes) && $log->context && is_array($log->context)) {
                $shortcodes = $log->context['shortcodes'] ?? [];
            }
            
            // If no shortcodes found, warn but attempt anyway
            if (empty($shortcodes)) {
                \Log::warning('Resending email without shortcodes', [
                    'log_id' => $id,
                    'template' => $log->email_template,
                    'recipient' => $log->recipient
                ]);
            }
            
                // Attempt to resend the email using NotifyTrait with original shortcodes
                try {
                    $this->mailNotify($log->recipient, $log->email_template, $shortcodes);
                    
                    // Mark as resent with timestamp
                    $log->update([
                        'resent_at' => now(),
                    ]);
                    
                    // Check if all logs are now resent, clear session if so
                    $this->checkAndClearSessionIfAllResent();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Email resent successfully to ' . $log->recipient
                    ]);
                    
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to resend email: ' . $e->getMessage()
                    ], 500);
                }
            
        } catch (\Exception $e) {
            \Log::error('SMTP Log Resend Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resending the email'
            ], 500);
        }
    }
    
    /**
     * Delete individual log
     */
    public function deleteLog($id)
    {
        try {
            $log = SmtpFailureLog::find($id);
            
            if (!$log) {
                return response()->json([
                    'success' => false,
                    'message' => 'Log not found'
                ], 404);
            }
            
            $log->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Log deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('SMTP Log Deletion Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the log'
            ], 500);
        }
    }
    
    /**
     * Clear all logs
     */
    public function clearLogs(Request $request)
    {
        try {
            $count = SmtpFailureLog::count();
            
            if ($count === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No logs to clear'
                ], 422);
            }
            
            SmtpFailureLog::truncate();
            
            // Also clear the session since all logs are gone
            $detectionService = app(SmtpFailureDetectionService::class);
            $detectionService->clearFailureSession();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully cleared {$count} log(s)"
            ]);
            
        } catch (\Exception $e) {
            \Log::error('SMTP Logs Clear Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while clearing logs'
            ], 500);
        }
    }

    /**
     * Check if all logs are resent, clear session alert if so
     */
    protected function checkAndClearSessionIfAllResent()
    {
        $failedCount = SmtpFailureLog::failed()->count();
        
        if ($failedCount === 0) {
            $detectionService = app(SmtpFailureDetectionService::class);
            $detectionService->clearFailureSession();
            \Log::info('SMTP alert session cleared - all failures have been resent');
        }
    }
}

