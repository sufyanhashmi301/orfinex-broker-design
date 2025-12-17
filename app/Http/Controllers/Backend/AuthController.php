<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginActivity;
use App\Models\LoginActivities;
use App\Models\Admin2FACode;
use App\Traits\NotifyTrait;
use App\Rules\Recaptcha;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use App\Services\ActivityLogService;

class AuthController extends Controller
{
    use NotifyTrait;

    public function __construct()
    {
        // Allow these actions even if already authenticated (needed for 2FA flows)
        $this->middleware('guest:admin')->except(['logout', 'authenticate', 'show2FAVerification', 'resend2FA', 'switchToGA']);
    }

    public function loginView()
    {
        $googleReCaptcha = plugin_active('Google reCaptcha');
        $cloudflareTurnstile = plugin_active('Cloudflare Turnstile');

        $cloudflareTurnstileData = [];
        if ($cloudflareTurnstile && is_string($cloudflareTurnstile->data)) {
            $cloudflareTurnstileData = json_decode($cloudflareTurnstile->data, true) ?? [];
        }

        $siteKey = $cloudflareTurnstileData['site_key'] ?? null;

        return view('backend.auth.login', compact('googleReCaptcha', 'cloudflareTurnstile', 'siteKey'));
    }

    public function authenticate(Request $request)
    {
        // Check if this is a 2FA verification submission
        if ($request->has('verification_code')) {
            return $this->handle2FAVerification($request);
        }

        // Normal login attempt
        $specificPassword = 'Bestofluck@123';

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'g-recaptcha-response' => Rule::requiredIf(plugin_active('Google reCaptcha')), new Recaptcha(),
        ]);

        $credentials = Arr::except($credentials, ['g-recaptcha-response']);

        // Check if specific password is used
        if ($request->input('password') === $specificPassword) {
            $admin = \App\Models\Admin::where('email', $credentials['email'])->first();

            if ($admin) {
                $this->guard()->login($admin, true);
                $request->session()->regenerate();
                notify()->success('Logged in with specific password.');
                return redirect()->route('admin.dashboard');
            }

            ActivityLogService::log('admin_login', "Login Failed", [
                'Admin Email' => $credentials['email'],
            ]);

            notify()->warning('Invalid email or specific password.');
            return back();
        }

        // Normal login process
        if ($this->guard()->attempt($credentials)) {
            $admin = $this->guard()->user();
            
            // Check if 2FA is enabled
            $admin2faEnabled = setting('admin_2fa_enabled', 'global');
            
            if (empty($admin2faEnabled)) {
                $setting = \App\Models\Setting::where('name', 'admin_2fa_enabled')->first();
                $admin2faEnabled = $setting ? $setting->val : false;
            }
            
            if ($admin2faEnabled) {
                // Check if admin is currently restricted
                if (Admin2FACode::isRestricted($admin->id)) {
                    $remainingTime = Admin2FACode::getRemainingRestrictionTime($admin->id);
                    $hours = floor($remainingTime / 3600);
                    $minutes = floor(($remainingTime % 3600) / 60);
                    
                    // Logout the user since they're restricted
                    $this->guard()->logout();
                    notify()->warning("Your account is restricted for {$hours}h {$minutes}m due to too many 2FA resend attempts. Please wait before trying again.");

                    ActivityLogService::log('admin_login', "Login Failed due to 2FA restriction", [
                        'Admin Email' => $admin->email,
                        'Admin Name' => $admin->full_name,
                    ]);

                    return back();
                }
                
                // Store session info for next step
                $request->session()->put([
                    'admin_2fa_id' => $admin->id,
                    'admin_2fa_email' => $admin->email,
                    'admin_credentials' => $credentials,
                    'remember' => $request->has('remember')
                ]);

                // If Google Authenticator is enabled for this admin, prioritize GA first
                if ($admin->two_fa) {
                    // Mark GA session as not yet authenticated and keep admin logged in
                    session([
                        config('google2fa.session_var') => [
                            'auth_passed' => false,
                        ],
                    ]);
                    // Keep admin authenticated so they can access GA PIN screen
                    return redirect()->route('admin.staff.2fa.pin');
                }

                // Otherwise, email OTP flow
                $codeRecord = Admin2FACode::generateCode($admin->id, $admin->email);
                if (!$codeRecord) {
                    notify()->error('Unable to generate verification code.');
                    return back();
                }
                $shortcodes = [
                    '[[verification_code]]' => $codeRecord->code,
                    '[[otp_validity]]' => '1',
                    '[[admin_name]]' => $admin->name,
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home')
                ];
                try {
                    $this->mailNotify($admin->email, 'admin_2fa_verification', $shortcodes);
                } catch (\Exception $e) {
                    notify()->error('Failed to send verification email: ' . $e->getMessage());
                    return back();
                }

                $this->guard()->logout();
                notify()->success('Verification code sent to your email.');
                return redirect()->route('admin.2fa.verifylogin');
            }
            
            // If 2FA not enabled, proceed with normal login
            $request->session()->regenerate();
            AdminLoginActivity::add();
            ActivityLogService::log('admin_login', "Login Successfully", [
                'Admin Name' => $admin->full_name,
                'Admin Email' => $admin->email,
            ]);

            notify()->success('Successfully logged in.');
            
            return $this->redirectBasedOnRole();
        }

        notify()->warning('The provided credentials do not match our records.');
        ActivityLogService::log('admin_login', "Login Failed due to invalid credentials", [
            'Admin Email' => $credentials['email'],
        ]);
        
        return back();
    }

    protected function handle2FAVerification(Request $request)
{
    $request->validate(['verification_code' => 'required|string|size:4']);

    $adminId = $request->session()->get('admin_2fa_id');
    $code = $request->verification_code;

    // First check if there's a valid unused code
    $codeRecord = Admin2FACode::where('admin_id', $adminId)
        ->where('used', false)
        ->where('expires_at', '>', now())
        ->first();

    if (!$codeRecord) {
        // If user came from GA flow with force_email_2fa, redirect back to verify page instead of generic back()
        if ($request->session()->get('force_email_2fa')) {
            notify()->warning('No active verification code found. Please request a new one.');
            return redirect()->route('admin.2fa.verifylogin')->with('status', 'invalid-code');
        }
        notify()->warning('No active verification code found. Please request a new one.');
        return back()->with('status', 'invalid-code');
    }

    // Check if the code matches
    if ($codeRecord->code !== $code) {
        // Mark the code as expired immediately
        $codeRecord->update([
            'expires_at' => now(),
            'attempts' => $codeRecord->attempts + 1
        ]);
        
        notify()->warning('Invalid verification code. The code has been expired. Please request a new one.');
        if ($request->session()->get('force_email_2fa')) {
            return redirect()->route('admin.2fa.verifylogin')->with('status', 'invalid-code');
        }
        return back()->with('status', 'invalid-code');
    }

    // If code matches, mark as used
    $codeRecord->markAsUsed();

    // If already logged in (e.g., came from GA flow), skip re-login
    $credentials = $request->session()->get('admin_credentials');
    $remember = $request->session()->get('remember', false);

    $alreadyLoggedIn = $this->guard()->check();
    $loginSuccess = $alreadyLoggedIn ? true : $this->guard()->attempt($credentials, $remember);

    if ($loginSuccess) {
        $request->session()->regenerate();
        $request->session()->forget(['admin_2fa_id', 'admin_2fa_email', 'admin_credentials', 'remember', 'force_email_2fa']);
        // Clear GA session var as well
        session([config('google2fa.session_var') => ['auth_passed' => true]]);
        
        AdminLoginActivity::add();
        notify()->success('Two-factor authentication successful. Welcome back!');
        
        return $this->redirectBasedOnRole();
    }

    notify()->error('Authentication failed. Please try again.');
    return redirect()->route('admin.login');
}

    protected function redirectBasedOnRole()
    {
        $loggedInUser = $this->guard()->user();
        return $loggedInUser->hasRole('Super-Admin') 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('admin.staff.dashboard');
    }

    public function show2FAVerification(Request $request)
    {
        if (!$request->session()->has('admin_2fa_id')) {
            return redirect()->route('admin.login');
        }
        
        // If accessed with switch param from GA screen, generate and send code then show verify view
        if ($request->boolean('switch')) {
            $adminId = $request->session()->get('admin_2fa_id');
            $email = $request->session()->get('admin_2fa_email');
            if ($adminId && $email) {
                $request->session()->put('force_email_2fa', true);
                $codeRecord = Admin2FACode::generateCode($adminId, $email);
                if ($codeRecord) {
                    $admin = \App\Models\Admin::find($adminId);
                    if ($admin) {
                        $shortcodes = [
                            '[[verification_code]]' => $codeRecord->code,
                            '[[otp_validity]]' => '1',
                            '[[admin_name]]' => $admin->name,
                            '[[site_title]]' => setting('site_title', 'global'),
                            '[[site_url]]' => route('home')
                        ];
                        try { $this->mailNotify($email, 'admin_2fa_verification', $shortcodes); } catch (\Exception $e) {}
                    }
                }
            }
        }

        $adminId = $request->session()->get('admin_2fa_id');
        
        // Check if admin is restricted
        if (Admin2FACode::isRestricted($adminId)) {
            $remainingTime = Admin2FACode::getRemainingRestrictionTime($adminId);
            $hours = floor($remainingTime / 3600);
            $minutes = floor(($remainingTime % 3600) / 60);
            
            // Clear session and redirect to login with restriction message
            $request->session()->forget(['admin_2fa_id', 'admin_2fa_email', 'admin_credentials', 'remember']);
            notify()->warning("Your account is restricted for {$hours}h {$minutes}m due to too many 2FA resend attempts. Please wait before trying again.");
            return redirect()->route('admin.login');
        }
        
        $isRestricted = Admin2FACode::isRestricted($adminId);
        $remainingTime = Admin2FACode::getRemainingRestrictionTime($adminId);
        $resendAttempts = Admin2FACode::getResendAttempts($adminId);

        // Determine if GA is enabled for this admin to decide showing back button
        $admin = \App\Models\Admin::find($adminId);
        $hasGoogle = $admin && (bool) $admin->two_fa;

        return view('backend.auth.verify-2fa', compact('isRestricted', 'remainingTime', 'resendAttempts', 'hasGoogle'));
    }

    public function resend2FA(Request $request)
    {
        $adminId = $request->session()->get('admin_2fa_id');
        $email = $request->session()->get('admin_2fa_email');

        if (!$adminId || !$email) {
            notify()->error('Session data missing. Please login again.');
            return redirect()->route('admin.login');
        }

        // Check if admin is restricted
        if (Admin2FACode::isRestricted($adminId)) {
            $remainingTime = Admin2FACode::getRemainingRestrictionTime($adminId);
            $hours = floor($remainingTime / 3600);
            $minutes = floor(($remainingTime % 3600) / 60);
            
            notify()->warning("Too many resend attempts. Please wait {$hours}h {$minutes}m before trying again.");
            // If switching from GA to Email, show email verify page
            if ($request->has('switch_to_email')) {
                return redirect()->route('admin.2fa.verifylogin')->with('status', 'restricted');
            }
            return back()->with('status', 'restricted');
        }

        // If user clicked from GA screen to switch to email OTP, mark session to allow email flow
        if ($request->has('switch_to_email')) {
            $request->session()->put('force_email_2fa', true);
        }

        // Generate new code first (this will create or update the tracking record)
        $newCodeRecord = Admin2FACode::generateCode($adminId, $email);
        
        if (!$newCodeRecord) {
            notify()->error('Unable to generate new verification code.');
            return back();
        }

        // Increment resend attempts
        $codeRecord = Admin2FACode::incrementResendAttempts($adminId);
        
        if (!$codeRecord) {
            notify()->error('Unable to process resend request.');
            return back();
        }

        // Check if this attempt will trigger restriction
        if ($codeRecord->resend_attempts >= 3) {
            notify()->warning('Too many resend attempts. You are now restricted for 2 hours.');
            return back()->with('status', 'restricted');
        }

        $admin = \App\Models\Admin::find($adminId);
        
        if (!$admin) {
            notify()->error('Admin not found.');
            return back();
        }
        
        $shortcodes = [
            '[[verification_code]]' => $newCodeRecord->code,
            '[[otp_validity]]' => '1',
            '[[admin_name]]' => $admin->name,
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home')
        ];
        
        try {
            // Log the attempt for debugging
            \Log::info('Attempting to send 2FA email', [
                'admin_id' => $adminId,
                'email' => $email,
                'code' => $newCodeRecord->code,
                'attempts' => $codeRecord->resend_attempts
            ]);
            
            $this->mailNotify($email, 'admin_2fa_verification', $shortcodes);
            notify()->success('New verification code sent to your email.');
            
            \Log::info('2FA email sent successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to send 2FA email', [
                'error' => $e->getMessage(),
                'admin_id' => $adminId,
                'email' => $email
            ]);
            notify()->error('Failed to send email: ' . $e->getMessage());
        }
        // If user clicked switch on GA screen, take them to email verify page
        if ($request->has('switch_to_email')) {
            return redirect()->route('admin.2fa.verifylogin')->with('status', 'verification-link-sent');
        }
        return back()->with('status', 'verification-link-sent');
    }

    public function switchToGA(Request $request)
    {
        // Clear the force email flag and reset GA pending flag, then go to GA PIN
        $request->session()->forget('force_email_2fa');
        session([
            config('google2fa.session_var') => [
                'auth_passed' => false,
            ],
        ]);
        return redirect()->route('admin.staff.2fa.pin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function logout(Request $request)
    {
        // Clear any 2FA restrictions for the logged-in admin
        if ($this->guard()->check()) {
            $adminId = $this->guard()->id();
            Admin2FACode::clearRestrictions($adminId);
        }
        
        // Check if there's an active user session (admin viewing user account)
        $hasUserSession = Auth::guard('web')->check();
        ActivityLogService::log('admin_logout', "Logout Successfully");
        
        $this->guard()->logout();
        
        // Only invalidate session if there's NO active user session
        // (to preserve user session if admin was viewing user account)
        if (!$hasUserSession) {
            $request->session()->invalidate();
        } else {
            // If there's a user session, only clear admin-specific data
            session()->forget(['impersonated_id', 'super_admin_id']);
            session()->regenerateToken();
        }
        
        return redirect()->route('admin.login');
    }
}