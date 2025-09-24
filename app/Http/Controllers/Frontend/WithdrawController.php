<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\ForexAccountStatus;
use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Enums\TxnTargetType;
use App\Enums\AccountBalanceType;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ForexAccount;
use App\Models\Transaction;
use App\Models\WithdrawAccount;
use App\Models\WithdrawalSchedule;
use App\Models\WithdrawMethod;
use App\Models\UserOtp;
use App\Models\User;
use App\Rules\ForexLoginBelongsToUser;
use App\Services\ForexApiService;
use App\Services\OtpService;
use App\Services\UserAccountCreationService;
use App\Services\WalletService;
use App\Traits\ForexApiTrait;
use App\Traits\ImageUpload;
use App\Traits\NotifyTrait;
use App\Traits\Payment;
use Brick\Math\BigDecimal;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WithdrawHistoryExport;

use Session;
use Txn;
use Validator;

class WithdrawController extends Controller
{
    use ImageUpload, NotifyTrait, Payment, ForexApiTrait;

    protected $forexApiService, $otpService, $userAccountCreationService;

    public function __construct(ForexApiService $forexApiService,UserAccountCreationService $userAccountCreationService, OtpService $otpService)
    {
        $this->forexApiService = $forexApiService;
        $this->otpService = $otpService;
        $this->userAccountCreationService = $userAccountCreationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $accounts = WithdrawAccount::where('user_id', auth()->id())
            ->whereHas('method', function($query) {
                $query->where('status', true);
            })
            ->get();

        $withdrawAccountApproval = setting('withdraw_account_approval', 'withdraw_settings');

        return view('frontend::withdraw.account.index', compact('accounts', 'withdrawAccountApproval'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $accountId = get_hash($id);
            $account = WithdrawAccount::where('id', $accountId)
                ->where('user_id', auth()->id())
                ->with(['method'])
                ->first();

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => __('Account not found.')
                ]);
            }

            // Decode credentials
            $credentials = is_string($account->credentials) ? json_decode($account->credentials, true) : $account->credentials;
            $credentials = is_array($credentials) ? $credentials : [];

            // Generate HTML for account details
            $html = view('frontend::withdraw.account.show', compact('account', 'credentials'))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Error loading account details.')
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));
            return redirect()->back();
        }

        $user = Auth::user();
        $withdrawAccountOtpEnabled = (bool) setting('withdraw_account_otp', 'withdraw_settings');
        $twoFaEnabledForUser = (bool) setting('fa_verification', 'permission') && (bool) $user->two_fa;

        // If either OTP or GA is enabled, we require verification before creating the account
        if ($withdrawAccountOtpEnabled || $twoFaEnabledForUser) {
            // Check restriction status first
            if ($withdrawAccountOtpEnabled) {
                $accountStatus = $this->userAccountCreationService->canCreateWithdrawAccount($user);
                if (!$accountStatus['can_create']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $accountStatus['message'],
                        'is_restricted' => true,
                        'remaining_time' => $accountStatus['remaining_time'],
                        'formatted_time' => $accountStatus['formatted_time']
                    ], 400);
                }
            }

            // Store form data in session
            $formData = [
                'withdraw_method_id' => $request->input('withdraw_method_id'),
                'method_name' => $request->input('method_name'),
                'credentials' => $request->input('credentials'),
            ];
            Session::put('withdraw_account_form_data', $formData);

            $verificationMethod = $request->input('verification_method'); // 'otp' or 'ga'

            // Both enabled: follow selected method; default to OTP when unspecified
            if ($withdrawAccountOtpEnabled && $twoFaEnabledForUser) {
                if ($verificationMethod === 'ga') {
                    return response()->json([
                        'status' => 'success',
                        'show_ga' => true,
                        'message' => __('Please verify with your Google Authenticator to continue.')
                    ]);
                }

                // Default/OTP path: send OTP
                $otpResult = $this->userAccountCreationService->sendWithdrawAccountOtp($user, 5);
                if ($otpResult['status'] === 'error') {
                    return response()->json([
                        'status' => 'error',
                        'message' => $otpResult['message'],
                        'is_restricted' => $otpResult['message'] && (str_contains($otpResult['message'], 'restricted') || str_contains($otpResult['message'], 'Too many'))
                    ], 400);
                }

                $withdrawAccountApproval = setting('withdraw_account_approval', 'withdraw_settings');
                $otpMessage = $withdrawAccountApproval
                    ? __('OTP has been sent to your email. Please verify it to create your withdraw account. It will be reviewed by admin for approval.')
                    : __('OTP has been sent to your email. Please verify it to create your withdraw account.');

                return response()->json([
                    'status' => 'success',
                    'message' => $otpMessage,
                    'show_modal' => true
                ]);
            }

            // Only OTP enabled
            if ($withdrawAccountOtpEnabled && !$twoFaEnabledForUser) {
                $otpResult = $this->userAccountCreationService->sendWithdrawAccountOtp($user, 5);
                if ($otpResult['status'] === 'error') {
                    return response()->json([
                        'status' => 'error',
                        'message' => $otpResult['message'],
                        'is_restricted' => $otpResult['message'] && (str_contains($otpResult['message'], 'restricted') || str_contains($otpResult['message'], 'Too many'))
                    ], 400);
                }
                $withdrawAccountApproval = setting('withdraw_account_approval', 'withdraw_settings');
                $otpMessage = $withdrawAccountApproval
                    ? __('OTP has been sent to your email. Please verify it to create your withdraw account. It will be reviewed by admin for approval.')
                    : __('OTP has been sent to your email. Please verify it to create your withdraw account.');
                return response()->json([
                    'status' => 'success',
                    'message' => $otpMessage,
                    'show_modal' => true
                ]);
            }

            // Only GA enabled
            if ($twoFaEnabledForUser && !$withdrawAccountOtpEnabled) {
                return response()->json([
                    'status' => 'success',
                    'show_ga' => true,
                    'message' => __('Please verify with your Google Authenticator to continue.')
                ]);
            }
        }

        // Proceed with account creation
        $result = $this->createWithdrawAccount($request);
        
        // Reset resend attempts after successful account creation (when OTP is not required)
        $this->userAccountCreationService->resetResendAttempts(auth()->user());
        
        return $result;
    }

    /**
     * Show OTP verification page for withdraw account creation
     */
    public function showOtpVerification()
    {
        if (!Session::has('withdraw_account_form_data')) {
            return redirect()->route('user.withdraw.account.create');
        }
        
        $user = Auth::user();
        
        // Use the service to get OTP status
        $otpStatus = $this->userAccountCreationService->getOtpStatus($user);
        
        return view('frontend::withdraw.account.verify_otp', [
            'isRestricted' => $otpStatus['is_restricted'],
            'remainingTime' => $otpStatus['remaining_time'],
            'formattedTime' => $otpStatus['formatted_time'],
            'resendAttempts' => $otpStatus['resend_attempts']
        ]);
    }

    /**
     * Verify OTP for withdraw account creation
     */
    public function verifyAccountCreationOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 400);
        }

        $user = Auth::user();
        $otpInput = $request->input('verification_code');

        // Validate the OTP
        $otpValidationResult = $this->userAccountCreationService->validateWithdrawAccountOtp($user, $otpInput);

        if ($otpValidationResult['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $otpValidationResult['message'],
                'is_restricted' => $otpValidationResult['message'] && (str_contains($otpValidationResult['message'], 'restricted') || str_contains($otpValidationResult['message'], 'Too many'))
            ], 400);
        }

        // OTP is valid, proceed with account creation
        $formData = Session::get('withdraw_account_form_data');
        if (!$formData) {
            return response()->json([
                'status' => 'error',
                'message' => __('Form data not found. Please try again.')
            ], 400);
        }

        // Create a new request with the stored form data
        $newRequest = new Request($formData);
        
        try {
            // Create the account
            $this->createWithdrawAccount($newRequest);
            
            // Clear session data
            Session::forget('withdraw_account_form_data');
            
            // Reset resend attempts after successful account creation (when OTP is required)
            $this->userAccountCreationService->resetResendAttempts($user);
            
            // Determine appropriate success message based on settings
            $withdrawAccountApproval = setting('withdraw_account_approval', 'withdraw_settings');
            $successMessage = $withdrawAccountApproval 
                ? __('Account created successfully! It will be reviewed by admin for approval.')
                : __('Account created successfully!');
            
            return response()->json([
                'status' => 'success',
                'message' => $successMessage,
                'redirect' => route('user.withdraw.account.index')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to create account. Please try again.')
            ], 400);
        }
    }

    public function verifyGaForAccountCreation(Request $request)
    {
        $request->validate([
            'one_time_password' => 'required|digits:6',
        ]);

        $user = Auth::user();
        if (!$user || !$user->two_fa || empty($user->google2fa_secret)) {
            return response()->json([
                'status' => 'error',
                'message' => __('Two-factor authentication is not enabled for your account.'),
            ], 400);
        }

        $formData = Session::get('withdraw_account_form_data');
        if (!$formData) {
            return response()->json([
                'status' => 'error',
                'message' => __('Form data not found. Please try again.'),
            ], 400);
        }

        $google2fa = app('pragmarx.google2fa');
        $isValid = false;
        try {
            $isValid = (bool) $google2fa->verifyKey($user->google2fa_secret, $request->input('one_time_password'));
        } catch (\Throwable $e) {
            $isValid = false;
        }

        if (!$isValid) {
            return response()->json([
                'status' => 'error',
                'message' => __('Invalid authenticator code.'),
            ], 400);
        }

        $newRequest = new Request($formData);
        try {
            $this->createWithdrawAccount($newRequest);
            Session::forget('withdraw_account_form_data');

            $withdrawAccountApproval = setting('withdraw_account_approval', 'withdraw_settings');
            $successMessage = $withdrawAccountApproval
                ? __('Account created successfully! It will be reviewed by admin for approval.')
                : __('Account created successfully!');

            return response()->json([
                'status' => 'success',
                'message' => $successMessage,
                'redirect' => route('user.withdraw.account.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to create account. Please try again.'),
            ], 400);
        }
    }

    /**
     * Resend OTP for withdraw account creation
     */
    public function resendAccountCreationOtp()
    {
        $user = Auth::user();
        
        $otpResult = $this->userAccountCreationService->sendWithdrawAccountOtp($user, 5);
        
        if ($otpResult['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $otpResult['message'],
                'is_restricted' => $otpResult['message'] && (str_contains($otpResult['message'], 'restricted') || str_contains($otpResult['message'], 'Too many'))
            ], 400);
        }
        
        return response()->json([
            'status' => 'success',
            'message' => __('OTP has been resent successfully.')
        ]);
    }

    /**
     * Create withdraw account after OTP verification
     */
    private function createWithdrawAccount(Request $request)
    {
        $input = $request->all();

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {
            if (is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value']);
            }
        }

        // Determine status based on both settings
        $withdrawAccountApproval = setting('withdraw_account_approval', 'withdraw_settings');
        $withdrawAccountOtp = setting('withdraw_account_otp', 'withdraw_settings');
        
        // Logic for determining status:
        // 1. If withdraw_account_approval is OFF and withdraw_account_otp is OFF -> APPROVED
        // 2. If withdraw_account_approval is OFF and withdraw_account_otp is ON -> APPROVED (after OTP verification)
        // 3. If withdraw_account_approval is ON and withdraw_account_otp is OFF -> PENDING
        // 4. If withdraw_account_approval is ON and withdraw_account_otp is ON -> PENDING (after OTP verification)
        
        if ($withdrawAccountApproval) {
            // Manual approval required - always PENDING
            $status = WithdrawAccount::STATUS_PENDING;
        } else {
            // No manual approval required - always APPROVED
            $status = WithdrawAccount::STATUS_APPROVED;
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
            'status' => $status,
        ];

        $createdAccount = WithdrawAccount::create($data);

        // Prepare notifications and emails
        try {
            $user = auth()->user();
            $withdrawMethod = WithdrawMethod::find($input['withdraw_method_id']);
            $shortcodes = [
                '[[full_name]]' => $user->full_name,
                '[[method_name]]' => $input['method_name'],
                '[[currency]]' => optional($withdrawMethod)->currency ?? 'N/A',
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            // Resolve admin email (site email or first active admin)
            $adminEmail = setting('site_email', 'global');
            if (empty($adminEmail)) {
                $adminEmail = User::where('status', 1)
                    ->whereHas('roles', function($q) { $q->whereIn('name', ['Super-Admin', 'Admin']); })
                    ->value('email');
            }

            // If account requires manual approval, notify admin and user
            if ($status === WithdrawAccount::STATUS_PENDING) {
                // Email to user
                try { $this->mailNotify($user->email, 'withdraw_account_request_user', $shortcodes); } catch (\Exception $e) { /* silently ignore */ }
                // Email to admin
                if (!empty($adminEmail)) {
                    try { $this->mailNotify($adminEmail, 'withdraw_account_request', $shortcodes); } catch (\Exception $e) { /* silently ignore */ }
                }

                // Push notification to admin (only if a matching push template exists)
                try { $this->pushNotify('withdraw_account_request', $shortcodes, route('admin.withdraw.pending'), $user->id, 'withdraw'); } catch (\Exception $e) { /* silently ignore */ }
            }

            // If account is auto-approved, notify user with approval email
            if ($status === WithdrawAccount::STATUS_APPROVED) {
                $approvalTemplate = \App\Models\EmailTemplate::where('status', true)->where('code', 'withdraw_account_approval')->first();
                if ($approvalTemplate) {
                    try { $this->mailNotify($user->email, 'withdraw_account_approval', $shortcodes); } catch (\Exception $e) { /* silently ignore */ }
                }

                // Email to admin on direct approval (if admin template exists)
                if (!empty($adminEmail)) {
                    $adminApprovalTemplate = \App\Models\EmailTemplate::where('status', true)->where('code', 'withdraw_account_approval_admin')->first();
                    if ($adminApprovalTemplate) {
                        try { $this->mailNotify($adminEmail, 'withdraw_account_approval_admin', $shortcodes); } catch (\Exception $e) { /* silently ignore */ }
                    }
                }

                // Push notification to admin on approval
                try { $this->pushNotify('withdraw_account_approval', $shortcodes, route('admin.withdraw.pending'), $user->id, 'withdraw'); } catch (\Exception $e) { /* silently ignore */ }
            }
        } catch (\Exception $e) {
            // Swallow any notification errors to not block the flow
        }

        // Show appropriate success message based on status
        if ($status === WithdrawAccount::STATUS_APPROVED) {
            notify()->success(__('Successfully Withdraw Account Created'), 'success');
        } else {
            notify()->success(__('Withdraw Account Created Successfully. It will be reviewed by admin for approval.'), 'success');
        }

        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $withdrawAccount = WithdrawAccount::where('id', get_hash($id))
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$withdrawAccount) {
            notify()->error(__('Withdraw account not found.'), __('Error'));
            return redirect()->back();
        }

        // Check if account is being used in any pending withdrawals
        $pendingWithdrawals = Transaction::where('user_id', auth()->user()->id)
            ->whereIn('type', [TxnType::Withdraw, TxnType::WithdrawAuto])
            ->where('status', TxnStatus::Pending)
            ->whereJsonContains('manual_field_data', ['withdraw_account_id' => $withdrawAccount->id])
            ->count();

        if ($pendingWithdrawals > 0) {
            notify()->error(__('Cannot delete account with pending withdrawals.'), __('Error'));
            return redirect()->back();
        }

        $withdrawAccount->delete();

        notify()->success(__('Withdraw account deleted successfully.'), __('Success'));
        return redirect()->route('user.withdraw.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $withdrawMethods = WithdrawMethod::where('status', true)
            ->where(function ($query) {
                $query->whereJsonContains('country', auth()->user()->country)
                    ->orWhereJsonContains('country', 'All');
            })->get();


        return view('frontend::withdraw.account.create', compact('withdrawMethods'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $withdrawMethods = WithdrawMethod::where(function ($query) {
            $query->whereJsonContains('country', auth()->user()->country)
                ->orWhereJsonContains('country', 'All');
        })->get();
        $withdrawAccount = WithdrawAccount::where('id', get_hash($id))
            ->where('user_id', auth()->user()->id)
            ->where('status', WithdrawAccount::STATUS_APPROVED)
            ->first();
        if ($withdrawAccount) {
            return view('frontend::withdraw.account.edit', compact('withdrawMethods', 'withdrawAccount'));
        }
        return redirect()->back();


    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'withdraw_method_id' => 'required',
            'method_name' => 'required',
            'credentials' => 'required',
        ]);

        if ($validator->fails()) {
            notify()->error($validator->errors()->first(), __('Error'));

            return redirect()->back();
        }

        $input = $request->all();

        $withdrawAccount = WithdrawAccount::find($id);

        $oldCredentials = json_decode($withdrawAccount->credentials, true);

        $credentials = $input['credentials'];
        foreach ($credentials as $key => $value) {

            if (!isset($value['value'])) {
                $credentials[$key]['value'] = $oldCredentials[$key]['value'];
            }

            if (isset($value['value']) && is_file($value['value'])) {
                $credentials[$key]['value'] = self::imageUploadTrait($value['value'], $oldCredentials[$key]['value']);
            }
        }

        $data = [
            'user_id' => auth()->id(),
            'withdraw_method_id' => $input['withdraw_method_id'],
            'method_name' => $input['method_name'],
            'credentials' => json_encode($credentials),
            // Keep the existing status - don't change it on update
        ];

        $withdrawAccount->update($data);
        notify()->success(__('Successfully Withdraw Account Updated'), 'success');

        return redirect()->route('user.withdraw.account.index');

    }

    /**
     * @return string
     */
    public function withdrawMethod($id)
    {
        $withdrawMethod = WithdrawMethod::find($id);

        if ($withdrawMethod) {
            return view('frontend::withdraw.include.__account', compact('withdrawMethod'))->render();
        }

        return '';
    }

    /**
     * @return array
     */
    public function details($accountId, int $amount = 0)
    {

        $withdrawAccount = WithdrawAccount::where('id', $accountId)
            ->where('status', WithdrawAccount::STATUS_APPROVED)
            ->first();

        $credentials = json_decode($withdrawAccount->credentials, true);

        $currency = setting('site_currency', 'global');
        $method = $withdrawAccount->method;
        $charge = $method->charge;
        $name = $withdrawAccount->method_name;
        $processingTime = (int)$method->required_time > 0 ? 'Processing Time: ' . $withdrawAccount->method->required_time . ' ' . $withdrawAccount->method->required_time_format : 'This Is Automatic Method';

        $info = [
            'name' => $name,
            'charge' => $charge,
            'charge_type' => $withdrawAccount->method->charge_type,
            'range' => __('Minimum') . ' ' . $method->min_withdraw . ' ' . $currency . ' ' . __('and') . ' ' . __('Maximum') . ' ' . $method->max_withdraw . ' ' . $currency,
            'processing_time' => $processingTime,
            'rate' => $method->rate,
            'pay_currency' => $method->currency
        ];

        if ($withdrawAccount->method->charge_type != 'fixed') {
            $charge = ($charge / 100) * $amount;
        }
        $conversionRate = $method->currency != $currency ? $method->rate . ' ' . $method->currency : null;
        $html = view('frontend::withdraw.include.__details', compact('credentials', 'name', 'charge', 'conversionRate'))->render();

        return [
            'html' => $html,
            'info' => $info,
        ];
    }

    /**
     * @return string
     */
    public function withdrawNow(Request $request)
    {
//

        $validationResult = $this->validateWithdrawal($request);
        if (!$validationResult) {
            return redirect()->back()->withInput();
        }

        // Recent GA verification allows proceeding directly
        $gaSession = session('ga_verified_withdraw');
        if (is_array($gaSession) && ($gaSession['verified'] ?? false)) {
            $expiresAt = isset($gaSession['expires_at']) ? Carbon::parse($gaSession['expires_at']) : null;
            if ($expiresAt && Carbon::now()->lt($expiresAt)) {
                session()->forget('ga_verified_withdraw');
                return $this->processWithdrawal($validationResult);
            }
            session()->forget('ga_verified_withdraw');
        }

        $user = Auth::user();
        $withdrawOtpEnabled = (bool) setting('withdraw_otp', 'withdraw_settings');
        $twoFaEnabledForUser = (bool) setting('fa_verification', 'permission') && (bool) $user->two_fa;

        // Both OTP and GA enabled -> prompt on UI, do NOT send OTP yet (send after user selects Email OTP)
        if ($withdrawOtpEnabled && $twoFaEnabledForUser) {
            $withdrawalData = [
                'target_id' => $request->input('target_id'),
                'account_type' => $request->input('account_type'),
                'withdraw_account' => $request->input('withdraw_account'),
                'amount' => $request->input('amount'),
            ];

            Session::put('withdrawal_data', $withdrawalData);
            Session::put('withdraw_auth_required', true);
            Session::put('withdraw_auth_options', ['otp' => true, 'ga' => true]);

            notify()->info(__('Choose a verification method to continue.'), __('Verification Required'));
            return redirect()->back()->withInput();
        }

        // Only GA enabled -> prompt GA on UI
        if (!$withdrawOtpEnabled && $twoFaEnabledForUser) {
            $withdrawalData = [
                'target_id' => $request->input('target_id'),
                'account_type' => $request->input('account_type'),
                'withdraw_account' => $request->input('withdraw_account'),
                'amount' => $request->input('amount'),
            ];
            Session::put('withdrawal_data', $withdrawalData);
            Session::put('withdraw_auth_required', true);
            Session::put('withdraw_auth_options', ['otp' => false, 'ga' => true]);

            notify()->info(__('Authenticate with your Google Authenticator to continue.'), __('Verification Required'));
            return redirect()->back()->withInput();
        }

        // Only OTP enabled -> current behavior
        if ($withdrawOtpEnabled) {
            $transactionType = TxnType::Withdraw->value;
            $userOtp = UserOtp::where('user_id', $user->id)->where('type', $transactionType)->first();

            if ($userOtp && $userOtp->verified && $userOtp->expires_at > Carbon::now()) {
                $userOtp->delete();
                return $this->processWithdrawal($validationResult);
            } else {
                $withdrawalData = [
                    'target_id' => $request->input('target_id'),
                    'account_type' => $request->input('account_type'),
                    'withdraw_account' => $request->input('withdraw_account'),
                    'amount' => $request->input('amount'),
                ];

                Session::put('withdrawal_data', $withdrawalData);
                $transactionType = TxnType::Withdraw->value;
                $otpValidityMinutes = setting('withdraw_otp_expires', 'withdraw_settings');

                $this->otpService->sendOtp($user, $transactionType, $otpValidityMinutes);

                notify()->info(__('OTP has been sent. Please verify it to proceed.'), 'OTP Sent');
                return redirect()->back()->withInput();
            }
        }

        // None enabled -> proceed
        try {
            DB::beginTransaction();
            return $this->processWithdrawal($validationResult);
        } catch (Exception $e) {
            DB::rollBack();
            notify()->error(__('An error occurred while processing your withdrawal. Please try again later.'), 'Error');
            return redirect()->back()->withInput();
        }
    }
        public
        function resendOtp(Request $request)
        {
            $user = Auth::user();
            $transactionType = TxnType::Withdraw->value;
            $otpValidityMinutes = setting('withdraw_otp_expires', 'withdraw_settings');

        $this->otpService->sendOtp($user, $transactionType, $otpValidityMinutes); // Call the method to resend OTP

        return response()->json([
            'success' => true,
            'message' => 'OTP has been resent successfully.',
        ]);
    }

        public
        function verifyOtp(Request $request)
        {
            $user = Auth::user();
            $otpInput = $request->input('otp');
            $transactionType = TxnType::Withdraw->value;

        // Validate the OTP
        $otpValidationResult = $this->otpService->validateOtp($user, $transactionType, $otpInput);

        // If the OTP is invalid or expired, return an error
        if (isset($otpValidationResult['status']) && $otpValidationResult['status'] === 'error') {
            return response()->json($otpValidationResult, 400);
        }

        // Mark OTP as verified
        $userOtp = UserOtp::where('user_id', $user->id)->where('type', $transactionType)->first();
        $userOtp->verified = true;
        $userOtp->save();

        return response()->json([
            'status' => 'success',
            'message' => __('OTP successfully verified. Proceed with the transaction.')
        ], 200);
    }

        public
        function verifyGaForWithdraw(Request $request)
        {
            $request->validate([
                'one_time_password' => 'required|digits:6',
            ]);

            $user = Auth::user();
            if (!$user || !$user->two_fa || empty($user->google2fa_secret)) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Two-factor authentication is not enabled for your account.'),
                ], 400);
            }

            $google2fa = app('pragmarx.google2fa');
            $isValid = false;
            try {
                $isValid = (bool) $google2fa->verifyKey($user->google2fa_secret, $request->input('one_time_password'));
            } catch (\Throwable $e) {
                $isValid = false;
            }

            if (!$isValid) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Invalid authenticator code.'),
                ], 400);
            }

            session([
                'ga_verified_withdraw' => [
                    'verified' => true,
                    'expires_at' => Carbon::now()->addMinutes(2)->toDateTimeString(),
                ],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => __('Authenticator verified. Proceeding with withdrawal.'),
            ]);
        }

        public
        function validateWithdrawal(Request $request)
        {
            $user = Auth::user();
            $input = $request->all();

            if (!setting('user_withdraw', 'permission') || !$user->withdraw_status) {
                abort('403', __('Withdraw Disabled Now'));
            }

            if (!setting('withdraw_amount', 'kyc_permissions') && auth()->user()->kyc < kyc_required_completed_level())  {
                notify()->error('KYC Pending: Please complete your KYC verification to proceed with your withdrawal', __('Error'));
                return false;
            }
            // Check if today is a withdraw off day
            $withdrawOffDays = WithdrawalSchedule::where('status', 0)->pluck('name')->toArray();
            $date = Carbon::now();
            $today = $date->format('l');

            if (in_array($today, $withdrawOffDays)) {
                abort('403', __('Today is the off day for withdraw'));
            }

            // Check daily send limit for successful transactions only
            $pendingLimit = setting('pending_withdraw_limit', 'withdraw_settings');
            $pendingWithdraws = Transaction::where('user_id', $user->id)
                ->whereIn('type', [TxnType::WithdrawAuto, TxnType::Withdraw])
                ->where('status', TxnStatus::Pending)
                ->count();

            if ($pendingWithdraws >= $pendingLimit) {
                notify()->error(
                    __("You already have a pending withdrawal request. Please contact our support team at :email for assistance.", [
                        'email' => setting('support_email', 'global')
                    ]),
                    __('Error')
                );
                return false;
            }
            // Check daily send limit for successful transactions only
            $dailyLimit = setting('withdraw_day_limit', 'fee');
            $todayTransfers = Transaction::where('user_id', $user->id)
                ->whereIn('type', [TxnType::WithdrawAuto, TxnType::Withdraw])
                ->whereDate('created_at', today())
                ->count();

            if ($todayTransfers >= $dailyLimit) {
                notify()->error(__('You have reached the daily withdraw limit.'), __('Error'));
                return false;
            }
            // Add conditional validation based on the account type
            $validator = Validator::make($input, [
                'target_id' => ['required'],
                'account_type' => ['required'],
                'withdraw_account' => ['required'],
                'amount' => ['required', 'regex:/^[0-9]+(\.[0-9]{1,4})?$/'],
            ], [
                'target_id.required' => __('Kindly select the account to withdraw'),
            ]);

            if ($validator->fails()) {
                // Send back validation errors with old input
                notify()->error($validator->errors()->first(), 'Error');
                return false;
            }
            // Decrypt the hashed target_id
            $targetId = get_hash($input['target_id']);
            $targetType = TxnTargetType::Wallet->value;  // Default to wallet

        // Determine whether the target is a Forex account or wallet
        $accountType = $input['account_type'] ?? 'wallet';
        $isForexAccount = $accountType === 'forex';

        

        $amount = (float)$input['amount'];
        $withdrawAccount = WithdrawAccount::where('id', $input['withdraw_account'])
            ->where('user_id', $user->id)
            ->where('status', WithdrawAccount::STATUS_APPROVED)
            ->first();
            
        if (!$withdrawAccount) {
            notify()->error(__('Invalid or unapproved withdraw account.'), 'Error');
            return false;
        }
        
        $withdrawMethod = $withdrawAccount->method;

        // Check if the amount is within the allowed withdraw range
        if ($amount < $withdrawMethod->min_withdraw || $amount > $withdrawMethod->max_withdraw) {
            $currencySymbol = setting('currency_symbol', 'global');
            $message = __('Please withdraw the amount within the range') . ' ' . $currencySymbol . $withdrawMethod->min_withdraw . ' ' . __('to') . ' ' . $currencySymbol . $withdrawMethod->max_withdraw;
            notify()->error($message, 'Error');
            return false;
        }

        $charge = $withdrawMethod->charge_type == 'percentage' ? (($withdrawMethod->charge / 100) * $amount) : $withdrawMethod->charge;
        $totalAmount = BigDecimal::of($amount)->abs();
        $payAmount = ($amount * $withdrawMethod->rate) - ($charge * $withdrawMethod->rate);
        $type = $withdrawMethod->type == 'auto' ? TxnType::WithdrawAuto : TxnType::Withdraw;

        $wallet = null;

        // Validate Forex account ownership or Wallet ownership
        if ($isForexAccount) {
            // Validate Forex account ownership
            $forexAccount = ForexAccount::where('login', $targetId)
                ->where('user_id', $user->id)
                ->where('account_type', 'real') // Ensure it's a real account
                ->first();

            if (!$forexAccount) {
                notify()->error(__('The selected Forex account does not belong to you.'), 'Error');
                return false;
            }

            $scaledAmount = apply_cent_account_adjustment($targetId, $amount);
            $balance = $this->forexApiService->getValidatedBalance(['login' => $targetId]);
            

            if (BigDecimal::of($scaledAmount)->compareTo(BigDecimal::of($balance)) > 0) {
                notify()->error(__('Insufficient Balance in Your Forex Account'), 'Error');
                return false;
            }


            // Set the transaction target type to Forex
            $targetType = TxnTargetType::ForexWithdraw->value;
        } else {

            // Validate wallet ownership
            $wallet = get_user_account_by_wallet_id($targetId, $user->id);
            if (!$wallet) {
                notify()->error(__('The selected wallet does not belong to you.'), 'Error');
                return false;
            }

            $balance = BigDecimal::of($wallet->amount);
            if ($totalAmount->compareTo($balance) > 0) {
                notify()->error(__('Insufficient Balance in Your Wallet'), 'Error');
                return false;
            }

            if ($wallet->balance === AccountBalanceType::IB_WALLET) {
                $ibMinLimit = setting('min_ib_wallet_withdraw_limit', 'withdraw_settings');
                if ($amount < $ibMinLimit) {
                    notify()->error(__('You must withdraw at least :limit from IB Wallet.', [
                        'limit' => setting('currency_symbol', 'global') . $ibMinLimit
                    ]), 'Error');
                    return false;
                }
            }

            // Set the transaction target type to Wallet
            $targetType = TxnTargetType::Wallet->value;
        }

        return [
            'user' => $user,
            'isForexAccount' => $isForexAccount,
            'targetId' => $targetId,
            'wallet' => $wallet,
            'amount' => $amount,
            'charge' => $charge,
            'totalAmount' => $totalAmount,
            'type' => $type,
            'payAmount' => $payAmount,
            'withdrawMethod' => $withdrawMethod,
            'withdrawAccount' => $withdrawAccount,
            'targetType' => $targetType,
        ];
    }

        public
        function processWithdrawal(array $data)
        {
            $user = $data['user'];
            $isForexAccount = $data['isForexAccount'];
            $targetId = $data['targetId'];
            $wallet = $data['wallet'];
            $amount = $data['amount'];
            $charge = $data['charge'];
            $type = $data['type'];
            $payAmount = $data['payAmount'];
            $totalAmount = $data['totalAmount'];
            $withdrawMethod = $data['withdrawMethod'];
            $withdrawAccount = $data['withdrawAccount'];
            $targetType = $data['targetType'];

            $txnInfo = Txn::new(
                $amount, $charge, $totalAmount, $withdrawMethod->name,
                'Withdraw With ' . $withdrawAccount->method_name, $type,
                TxnStatus::None, $withdrawMethod->currency, $payAmount, $user->id, null,
                'User', json_decode($withdrawAccount->credentials, true), 'none',
                $targetId, $targetType
            );

            $isDeducted = false;

            // Apply deduction logic for both Forex and wallet accounts
            if (setting('withdraw_deduction', 'features')) {
                if ($isForexAccount) {
                    // Deduction logic for Forex
                    $comment = $withdrawMethod->name . '/' . substr($txnInfo->tnx, -7);
                    $data = [
                        'login' => $targetId,
                        'Amount' => apply_cent_account_adjustment($targetId, $totalAmount),
                        'type' => 2,  // Withdraw
                        'TransactionComments' => $comment
                    ];

                    // Simulate balance operation via Forex API
                    $withdrawResponse = $this->forexApiService->balanceOperation($data);
                    if ($withdrawResponse['success'] && 
                    ($withdrawResponse['result']['responseCode'] == 10009 || $withdrawResponse['result']['responseCode'] === 'MT_RET_REQUEST_DONE')
                ) {
                        $isDeducted = true; // Deduction applied
                        $updateResult = Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, null);
                        if (!$updateResult) {
                            DB::rollBack();
                            notify()->error('Failed to update transaction. Please try again.');
                            return redirect()->back();
                        }
                    } else {
                        // Mark the transaction as failed if deduction fails
                        Txn::update($txnInfo->tnx, TxnStatus::Failed, $txnInfo->user_id, __('Insufficient Withdrawable Balance'));
                        notify()->error(__('Insufficient Balance in Your account'), 'Error');
                        return redirect()->back()->withInput();
                    }
                } else {
                    // Wallet deduction logic
                    $walletService = new WalletService();
                    $ledgerBalance = $walletService->getLedgerBalance($wallet->id);

                    // Create ledger entry for the wallet deduction (Debit)
                    $ledger = $walletService->createDebitLedgerEntry($txnInfo, $ledgerBalance);

                    // Deduct the amount from the wallet
                    $wallet->amount = BigDecimal::of($wallet->amount)->minus(BigDecimal::of($txnInfo->amount));
                    $wallet->save();

                    $isDeducted = true;  // Mark deduction as applied for wallet

                    // Update transaction status
                    Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, null);
                }
            } else {
                // If deduction feature is disabled, mark the transaction as pending
                Txn::update($txnInfo->tnx, TxnStatus::Pending, $txnInfo->user_id, null);
            }

            // Ensure $txnInfo->manual_field_data is decoded as an array
            $manualFieldData = json_decode($txnInfo->manual_field_data, true);

            // If manual_field_data is null or not an array, initialize it as an empty array
            if (is_null($manualFieldData) || !is_array($manualFieldData)) {
                $manualFieldData = [];
            }

            // Add the 'Deduction Status' field to the array, formatted like the other fields
            $manualFieldData['Deduction Status'] = [
                'type' => 'text',
                'validation' => 'optional',
                'value' => $isDeducted ? __('Deducted') : __('Not Deducted')
            ];

            // Re-encode and save the updated manual_field_data
            $txnInfo->manual_field_data = json_encode($manualFieldData);
            $txnInfo->save();
            DB::commit();

            // Handle automatic withdrawals
            if ($withdrawMethod->type == 'auto') {
                $gatewayCode = $withdrawMethod->gateway->gateway_code;
                return self::withdrawAutoGateway($gatewayCode, $txnInfo);
            }

            // Notify user and admin
            $symbol = setting('currency_symbol', 'global');
            $notify = [
                'card-header' => __('Withdraw Money'),
                'title' => $symbol . $txnInfo->amount . ' ' . __('Withdraw Request Successful'),
                'p' => __('The Withdraw Request has been successfully sent'),
                'strong' => __('Transaction ID: ') . $txnInfo->tnx,
                'action' => route('user.withdraw.view'),
                'a' => __('Withdraw Request Again'),
                'view_name' => 'withdraw',
            ];
            Session::put('user_notify', $notify);

            $shortcodes = [
                '[[full_name]]' => $txnInfo->user->full_name,
                '[[txn]]' => $txnInfo->tnx,
                '[[method_name]]' => $withdrawMethod->name,
                '[[withdraw_amount]]' => $txnInfo->amount . setting('site_currency', 'global'),
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];

            // Send notifications
            $this->mailNotify($user->email, 'withdraw_request_user', $shortcodes);
            $this->mailNotify(setting('site_email', 'global'), 'withdraw_request', $shortcodes);
            $this->pushNotify('withdraw_request', $shortcodes, route('admin.withdraw.pending'), $user->id, 'withdraw');
            $this->smsNotify('withdraw_request', $shortcodes, $user->phone);

            if (session()->has('withdrawal_data')) {
                Session::forget('withdrawal_data');
            }

            return redirect()->route('user.notify');
        }

        public
        function WithdrawApiCall($login, $totalAmount)
        {
            $withdrawUrl = config('forextrading.withdrawUrl');
            $auth = config('forextrading.auth');

            $dataArray = [
                'Login' => $login,
                'Withdraw' => $totalAmount,
                'Comment' => "Withdraw/USD",

            ];
//        dd($userAccount,$dataArray);
            $withdrawResponse = $this->sendApiRequest($withdrawUrl, $dataArray);
//        dd($userAccount,$withdrawResponse);
            if ($withdrawResponse ? $withdrawResponse->status() == 200 && $withdrawResponse->object()->data == 0 : false) {
                return true;
            }

        }

        /**
         * @return Application|Factory|View
         */

        public
        function withdrawMethods()
        {
            $accounts = WithdrawAccount::with(['method', 'method.gateway'])
            ->where('user_id', auth()->id())
            ->get();

            return view('frontend::withdraw.index', compact('accounts'));
        }

        public function withdraw()
        {

            $gatewayCode = request()->get('gateway_code', '');
            $selectedAccount = get_hash($gatewayCode);

            $accounts = WithdrawAccount::with(['method', 'method.gateway'])
                ->where('user_id', \Auth::id())
                ->where('status', WithdrawAccount::STATUS_APPROVED)
                ->get();
                
            $accounts = $accounts->reject(function ($value, $key) {
                return !$value->method->status;
            });

            if (!$selectedAccount){
                notify()->error('Please select a valid withdraw method');
                return redirect()->back();
            }

            $forexAccounts = ForexAccount::with('schema')->traderType()
                ->where('user_id', auth()->id())
                ->where('account_type', 'real')
                ->where('status', ForexAccountStatus::Ongoing)
                ->orderBy('id', 'desc')
                ->get();

            return view('frontend::withdraw.now', compact('accounts', 'forexAccounts', 'selectedAccount'));
        }

        public
        function withdrawLog()
        {
            $withdraws = Transaction::search(request('query'), function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('status', '!=', \App\Enums\TxnStatus::None) // Exclude none status
                    ->where('type', TxnType::Withdraw)
                    ->when(request('date'), function ($query) {
                        $query->whereDay('created_at', '=', Carbon::parse(request('date'))->format('d'));
                    });
            })->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

            return view('frontend::withdraw.log', compact('withdraws'));
        }

        public
        function export(Request $request)
        {
            return Excel::download(new WithdrawHistoryExport($request), 'Withdraw-History.xlsx');
        }
    }
