<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\IBStatus;
use App\Http\Controllers\Controller;
use App\Models\IbQuestion;
use App\Models\IbQuestionAnswer;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class IBControllerSecure extends Controller
{
    protected $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    /**
     * Secure IB request submission with rate limiting and enhanced validation
     */
    public function storeSecure(Request $request)
    {
        // Rate limiting - 3 attempts per hour per user
        $key = 'ib-request:' . auth()->id();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('IB request rate limit exceeded', [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'available_in' => $seconds
            ]);
            
            throw ValidationException::withMessages([
                'rate_limit' => "Too many IB requests. Please try again in " . 
                    gmdate('H:i:s', $seconds) . "."
            ]);
        }

        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            
            // Check if user is eligible for IB request
            if (!$this->isUserEligibleForIB($user)) {
                DB::rollback();
                RateLimiter::hit($key, 3600); // 1 hour timeout
                
                throw ValidationException::withMessages([
                    'eligibility' => 'You are not eligible to submit an IB request at this time.'
                ]);
            }

            // Get validation rules dynamically
            $validationRules = $this->getSecureValidationRules();
            
            // Validate request
            $validator = Validator::make($request->all(), $validationRules);
            
            if ($validator->fails()) {
                DB::rollback();
                RateLimiter::hit($key, 3600);
                
                Log::warning('IB request validation failed', [
                    'user_id' => auth()->id(),
                    'errors' => $validator->errors(),
                    'ip' => $request->ip()
                ]);
                
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            // Sanitize form data
            $sanitizedData = $this->sanitizeIBFormData($request->input('fields', []));
            
            // Store or update IB question answer
            $ibQuestionAnswer = IbQuestionAnswer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'fields' => json_encode($sanitizedData),
                    'submitted_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]
            );

            // Update user status only if not already approved
            if ($user->ib_status !== IBStatus::APPROVED) {
                $originalStatus = $user->ib_status;
                $user->update([
                    'ib_status' => IBStatus::PENDING,
                    'ib_requested_at' => now()
                ]);

                // Create audit log
                $this->auditLogService->log([
                    'action' => 'ib_request_submitted',
                    'model' => 'User',
                    'model_id' => $user->id,
                    'changes' => [
                        'ib_status' => ['from' => $originalStatus, 'to' => IBStatus::PENDING]
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }

            // Send notifications with error handling
            $this->sendIBRequestNotifications($user);

            DB::commit();
            
            // Clear rate limiter on successful submission
            RateLimiter::clear($key);
            
            Log::info('IB request submitted successfully', [
                'user_id' => $user->id,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'reload' => true,
                'modal' => true,
                'success' => __("IB request has been successfully submitted. Admin will review your request within 24-48 hours.")
            ]);

        } catch (ValidationException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            RateLimiter::hit($key, 3600);
            
            Log::error('IB request submission failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to submit IB request. Please try again later.'
            ], 500);
        }
    }

    /**
     * Check if user is eligible for IB request
     */
    private function isUserEligibleForIB(User $user): bool
    {
        // Check if user is already approved
        if ($user->ib_status === IBStatus::APPROVED) {
            return false;
        }

        // Check if user has a referrer (can't be IB if referred by someone)
        if (isset($user->ref_id)) {
            return false;
        }

        // Check KYC requirements
        if (!setting('master_ib_request', 'kyc_permissions') && 
            $user->kyc < kyc_required_completed_level()) {
            return false;
        }

        // Check if referral system is enabled
        if (!setting('sign_up_referral', 'permission')) {
            return false;
        }

        // Check if user was recently rejected (cooling off period)
        if ($user->ib_status === IBStatus::REJECTED && 
            $user->updated_at->diffInDays(now()) < 30) {
            return false;
        }

        return true;
    }

    /**
     * Get secure validation rules with enhanced security
     */
    private function getSecureValidationRules(): array
    {
        $rules = [
            'fields' => 'required|array|min:1',
            'checkbox' => 'required|accepted' // Agreement checkbox
        ];

        $ibQuestions = IbQuestion::where('status', true)->get();
        
        foreach ($ibQuestions as $ibQuestion) {
            $fields = json_decode($ibQuestion->fields, true);
            
            if (!is_array($fields)) {
                continue;
            }

            foreach ($fields as $field) {
                $fieldName = "fields.{$field['name']}";
                $fieldRules = [];

                // Base validation
                if ($field['validation'] === 'required') {
                    $fieldRules[] = 'required';
                } else {
                    $fieldRules[] = 'nullable';
                }

                // Type-specific validation
                switch ($field['type']) {
                    case 'text':
                        $fieldRules[] = 'string';
                        $fieldRules[] = 'max:500'; // Prevent large inputs
                        $fieldRules[] = 'regex:/^[a-zA-Z0-9\s\-\.\,\(\)]+$/'; // Safe characters only
                        break;
                        
                    case 'checkbox':
                        $fieldRules[] = 'array';
                        $fieldRules[] = 'max:10'; // Limit selections
                        if (isset($field['options'])) {
                            $fieldRules[] = 'in:' . implode(',', $field['options']);
                        }
                        break;
                        
                    case 'radio':
                    case 'dropdown':
                        $fieldRules[] = 'string';
                        if (isset($field['options'])) {
                            $fieldRules[] = 'in:' . implode(',', $field['options']);
                        }
                        break;
                }

                $rules[$fieldName] = implode('|', $fieldRules);
            }
        }

        return $rules;
    }

    /**
     * Sanitize IB form data to prevent XSS and injection attacks
     */
    private function sanitizeIBFormData(array $formData): array
    {
        $sanitized = [];
        
        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                // For checkbox arrays
                $sanitized[$key] = array_map(function($item) {
                    return strip_tags(trim($item));
                }, $value);
            } else {
                // For single values
                $sanitized[$key] = strip_tags(trim($value));
            }
        }
        
        return $sanitized;
    }

    /**
     * Send IB request notifications with error handling
     */
    private function sendIBRequestNotifications(User $user): void
    {
        try {
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => 'Pending',
                '[[request_date]]' => now()->format('Y-m-d H:i:s')
            ];

            // Notify user
            $this->mailNotify($user->email, 'user_ib_request', $shortcodes);
            
            // Notify admin
            $adminEmail = setting('site_email', 'global');
            if ($adminEmail) {
                $this->mailNotify($adminEmail, 'ib_request', $shortcodes);
            }
            
            // Push notification to admin dashboard
            $this->pushNotify(
                'ib_request', 
                $shortcodes, 
                route('admin.ib.pending.list'), 
                $user->id
            );
            
        } catch (\Exception $e) {
            // Log notification failure but don't fail the request
            Log::warning('IB request notifications failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display IB request status with security checks
     */
    public function showStatus()
    {
        $user = auth()->user();
        
        // Security: Ensure user can only view their own status
        $ibRequestData = IbQuestionAnswer::where('user_id', $user->id)->first();
        
        return view('user.ib-program.status', [
            'user' => $user,
            'ibRequestData' => $ibRequestData,
            'statusHistory' => $this->getIBStatusHistory($user->id)
        ]);
    }

    /**
     * Get IB status history for transparency
     */
    private function getIBStatusHistory(int $userId): array
    {
        return $this->auditLogService->getHistory([
            'model' => 'User',
            'model_id' => $userId,
            'actions' => ['ib_request_submitted', 'ib_member_approved', 'ib_member_rejected']
        ]);
    }
}
