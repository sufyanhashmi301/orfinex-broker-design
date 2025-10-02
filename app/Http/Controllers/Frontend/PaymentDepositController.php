<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaymentDepositQuestion;
use App\Models\PaymentDepositRequest;
use App\Traits\NotifyTrait;
use App\Traits\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PaymentDepositController extends Controller
{
    use NotifyTrait, ImageUpload;

    /**
     * Display payment deposit request form
     */
    public function index()
    {
        // Check if payment deposit requests are enabled
        if (setting('deposit_account_mode', 'features') !== 'request_deposit_accounts') {
            abort(404, 'Payment deposit requests are not available.');
        }
        
        $user = auth()->user();
        
        // Get user's latest request
        $latestRequest = PaymentDepositRequest::forUser($user->id)->latest()->first();
        
        // Get active questions
        $depositQuestions = PaymentDepositQuestion::active()->get();
        
        // Ensure all fields are arrays
        $depositQuestions->each(function ($question) {
            if (!is_array($question->fields)) {
                $question->fields = is_string($question->fields) ? json_decode($question->fields, true) : [];
            }
            $question->fields = $question->fields ?: [];
        });

        // Get previously used dates for suggestions
        $previouslyUsedDates = $this->getPreviouslyUsedDates($depositQuestions);

        return view('frontend::payment-deposit.index', compact('latestRequest', 'depositQuestions', 'previouslyUsedDates'));
    }

    /**
     * Store payment deposit request
     */
    public function store(Request $request)
    {
        // Check if payment deposit requests are enabled
        if (setting('deposit_account_mode', 'features') !== 'request_deposit_accounts') {
            abort(404, 'Payment deposit requests are not available.');
        }
        
        // Rate limiting - 3 attempts per hour per user
        $key = 'payment-deposit-request:' . auth()->id();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            
            Log::warning('Payment deposit request rate limit exceeded', [
                'user_id' => auth()->id(),
                'ip' => $request->ip(),
                'available_in' => $seconds
            ]);
            
            throw ValidationException::withMessages([
                'rate_limit' => "Too many payment deposit requests. Please try again in " . 
                    gmdate('H:i:s', $seconds) . "."
            ]);
        }

        DB::beginTransaction();
        
        try {
            $user = auth()->user();
            
            // Check if user has pending request
            $pendingRequest = PaymentDepositRequest::forUser($user->id)
                ->pending()
                ->first();

            if ($pendingRequest) {
                DB::rollback();
                RateLimiter::hit($key, 3600);
                
                throw ValidationException::withMessages([
                    'pending_request' => 'You already have a pending payment deposit request. Please wait for admin review.'
                ]);
            }

            // Get validation rules dynamically
            $validationRules = $this->getValidationRules();
            
            // Validate request
            $validator = Validator::make($request->all(), $validationRules);
            
            if ($validator->fails()) {
                DB::rollback();
                RateLimiter::hit($key, 3600);
                
                Log::warning('Payment deposit request validation failed', [
                    'user_id' => auth()->id(),
                    'errors' => $validator->errors(),
                    'ip' => $request->ip()
                ]);
                
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            // Sanitize form data
            $sanitizedData = $this->sanitizeFormData($request->input('fields', []), $request);
            
            // Create payment deposit request
            $depositRequest = PaymentDepositRequest::create([
                'user_id' => $user->id,
                'fields' => $sanitizedData,
                'status' => PaymentDepositRequest::STATUS_PENDING,
                'submitted_at' => now()
            ]);

            // Send notifications
            $this->sendRequestNotifications($user, $depositRequest);

            DB::commit();
            
            // Clear rate limiter on successful submission
            RateLimiter::clear($key);
            
            Log::info('Payment deposit request submitted successfully', [
                'user_id' => $user->id,
                'request_id' => $depositRequest->id,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'reload' => true,
                'modal' => true,
                'success' => __("Payment deposit request has been successfully submitted. Admin will review your request and provide bank details within 24-48 hours.")
            ]);

        } catch (ValidationException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            RateLimiter::hit($key, 3600);
            
            Log::error('Payment deposit request submission failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to submit payment deposit request. Please try again later.'
            ], 500);
        }
    }

    /**
     * View specific request details
     */
    public function show($id)
    {
        // Check if payment deposit requests are enabled
        if (setting('deposit_account_mode', 'features') !== 'request_deposit_accounts') {
            abort(404, 'Payment deposit requests are not available.');
        }
        
        $user = auth()->user();
        
        $request = PaymentDepositRequest::forUser($user->id)
            ->with('approvedBy')
            ->findOrFail($id);

        return view('frontend::payment-deposit.show', compact('request'));
    }

    /**
     * Get validation rules dynamically based on active questions
     */
    private function getValidationRules(): array
    {
        $rules = [
            'fields' => 'required|array|min:1',
            'agreement' => 'required|accepted' // Agreement checkbox
        ];

        $depositQuestions = PaymentDepositQuestion::active()->get();
        
        foreach ($depositQuestions as $question) {
            $fields = json_decode($question->fields, true);
            
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
                        break;
                        
                    case 'date':
                        $fieldRules[] = 'date';
                        $fieldRules[] = 'date_format:Y-m-d';
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
                        
                    case 'file':
                        $fieldRules[] = 'file';
                        $fieldRules[] = 'max:5120'; // 5MB max
                        $fieldRules[] = 'mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,txt';
                        break;
                }

                $rules[$fieldName] = implode('|', $fieldRules);
            }
        }

        return $rules;
    }

    /**
     * Sanitize form data and handle file uploads
     */
    private function sanitizeFormData(array $formData, Request $request): array
    {
        $sanitized = [];
        
        // Get active questions to check field types
        $depositQuestions = PaymentDepositQuestion::active()->get();
        $fileFields = [];
        
        // Identify file fields
        foreach ($depositQuestions as $question) {
            $fields = json_decode($question->fields, true);
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    if ($field['type'] === 'file') {
                        $fileFields[] = $field['name'];
                    }
                }
            }
        }
        
        foreach ($formData as $key => $value) {
            // Check if this is a file field
            if (in_array($key, $fileFields) && $request->hasFile("fields.{$key}")) {
                // Handle file upload
                $uploadedFile = $request->file("fields.{$key}");
                try {
                    $sanitized[$key] = $this->paymentDepositFileUploadTrait($uploadedFile);
                } catch (\Exception $e) {
                    Log::error('File upload failed for field: ' . $key, [
                        'error' => $e->getMessage(),
                        'user_id' => auth()->id()
                    ]);
                    throw ValidationException::withMessages([
                        "fields.{$key}" => ['File upload failed: ' . $e->getMessage()],
                    ]);
                }
            } elseif (is_array($value)) {
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
     * Send request notifications with error handling
     */
    private function sendRequestNotifications($user, $depositRequest): void
    {
        try {
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[email]]' => $user->email,
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
                '[[status]]' => 'Pending',
                '[[request_date]]' => now()->format('Y-m-d H:i:s'),
                '[[request_id]]' => $depositRequest->id
            ];

            // Notify user
            $this->mailNotify($user->email, 'payment_deposit_request', $shortcodes);
            
            // Notify admin
            $adminEmail = setting('site_email', 'global');
            if ($adminEmail) {
                $this->mailNotify($adminEmail, 'payment_deposit_request_admin', $shortcodes);
            }
            
            // Push notification to admin dashboard
            $this->pushNotify(
                'payment_deposit_request', 
                $shortcodes, 
                route('admin.payment-deposit.pending.list'), 
                $user->id
            );
            
        } catch (\Exception $e) {
            // Log notification failure but don't fail the request
            Log::warning('Payment deposit request notifications failed', [
                'user_id' => $user->id,
                'request_id' => $depositRequest->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get previously used dates from existing payment deposit requests
     */
    private function getPreviouslyUsedDates($depositQuestions): array
    {
        $previousDates = [];
        
        // Get field names that are date type
        $dateFieldNames = [];
        foreach ($depositQuestions as $question) {
            foreach ($question->fields as $field) {
                if ($field['type'] === 'date') {
                    $dateFieldNames[] = $field['name'];
                }
            }
        }
        
        if (empty($dateFieldNames)) {
            return $previousDates;
        }
        
        // Get all approved requests with date fields
        $requests = PaymentDepositRequest::where('status', PaymentDepositRequest::STATUS_APPROVED)
            ->whereNotNull('fields')
            ->get();
            
        foreach ($requests as $request) {
            if (is_array($request->fields)) {
                foreach ($request->fields as $fieldName => $value) {
                    if (in_array($fieldName, $dateFieldNames) && !empty($value)) {
                        // Validate date format
                        try {
                            $date = \Carbon\Carbon::createFromFormat('Y-m-d', $value);
                            if (!isset($previousDates[$fieldName])) {
                                $previousDates[$fieldName] = [];
                            }
                            if (!in_array($date->format('Y-m-d'), $previousDates[$fieldName])) {
                                $previousDates[$fieldName][] = $date->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            // Skip invalid dates
                            continue;
                        }
                    }
                }
            }
        }
        
        // Sort dates in descending order (newest first)
        foreach ($previousDates as $fieldName => $dates) {
            rsort($previousDates[$fieldName]);
            // Limit to last 10 dates per field
            $previousDates[$fieldName] = array_slice($previousDates[$fieldName], 0, 10);
        }
        
        return $previousDates;
    }
}
