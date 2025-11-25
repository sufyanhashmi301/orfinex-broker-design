<?php

namespace App\Http\Controllers\Auth;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Events\UserReferred;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\LoginActivities;
use App\Models\MultiLevel;
use App\Models\Page;
use App\Models\Ranking;
use App\Models\User;
use App\Models\ReferralLink;
use App\Models\CompanyFormSubmission;
use App\Providers\RouteServiceProvider;
use App\Rules\Recaptcha;
use App\Traits\NotifyTrait;
use App\Traits\ImageUpload;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Session;
use Txn;

class RegisteredUserController extends Controller
{
    use NotifyTrait, ImageUpload;

    /**
     * Handle an incoming registration request.
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        // Regular Registration Logic
        $this->validateRegularRegistration($request);
        $input = $request->all();
        // Email Verification
        if(!$this->verifyEmail($request->input('email'))) {
            notify()->error('Email address is not valid');
            return redirect()->back();
        }

        $schemaID = $request->schema ? decrypt($request->schema) : null;
        $location = getLocation();
        $phone = $this->formatPhone($input, $location);
        $country = $this->getCountry($input, $location);

        $rank = Ranking::find(1);

        $user = $this->createUser($input, $rank, $phone, $country);

        $this->applyBonuses($user, $rank);

        // Handle company registration submission if enabled and selected
        try {
            $companyFormEnabled = (bool) setting('company_form_status', 'company_register');
            $adminApproval = (bool) setting('company_form_admin_approval', 'company_register');
            if ($companyFormEnabled && $request->input('registration_type') === 'company') {
                $companyFieldsJson = setting('company_form_fields', 'company_register');
                $definitions = [];
                if (is_string($companyFieldsJson) && !empty($companyFieldsJson)) {
                    $decoded = json_decode($companyFieldsJson, true);
                    $definitions = is_array($decoded) ? $decoded : [];
                }

                $payload = [];
                foreach ($definitions as $def) {
                    $name = $def['name'] ?? '';
                    $type = $def['type'] ?? 'text';
                    $validation = $def['validation'] ?? 'nullable';
                    $safe = \Illuminate\Support\Str::slug($name, '_');

                    if ($type === 'file') {
                        $file = $request->file("company_form_files.$safe");
                        if ($validation === 'required' && !$file) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'company_form_files.' . $safe => __(':field is required', ['field' => $name])
                            ]);
                        }
                        if ($file) {
                            $ext = strtolower($file->getClientOriginalExtension());
                            $imageExt = in_array($ext, ['jpeg','jpg','png','gif','webp','svg']);
                            $stored = $imageExt ? $this->imageUploadTrait($file) : $this->paymentDepositFileUploadTrait($file);
                            $payload[$name] = $stored;
                        }
                    } else {
                        $val = $request->input("company_form.$safe");
                        if ($validation === 'required' && (is_null($val) || $val === '' || (is_array($val) && count($val) === 0))) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'company_form.' . $safe => __(':field is required', ['field' => $name])
                            ]);
                        }
                        $payload[$name] = $val;
                    }
                }

                if (!empty($payload)) {
                    CompanyFormSubmission::create([
                        'user_id' => $user->id,
                        'fields' => $payload,
                        'status' => $adminApproval ? 'pending' : 'approved',
                    ]);
                }
            }
        } catch (\Throwable $e) {
            \Log::warning('Company form submission handling failed: '.$e->getMessage());
            // continue registration flow regardless
        }
        $this->notifyUser($user, $input);
        $referralCode = $request->cookie('invite');
        $this->handleReferral($referralCode, $user);

        Auth::login($user);
        LoginActivities::add();

        return redirect(RouteServiceProvider::HOME);
    }
    private function verifyEmail($email) {
        // Verify email using Reoon API
        $response = Http::get("https://emailverifier.reoon.com/api/v1/verify", [
            'email' => $email,
            'key' => '0bJQtU3PUrl0b5UmDHile5iJXKpHb6PM',
            'mode' => 'quick'
        ]);
        $result = $response->json(); // Convert response to array
        if($result['status'] == 'valid') {
            return true;
        } else {
            return false;
        }
    }
    public function handleSocialRegistration($socialUser, $provider, $referralCode = null)
    {
        // Default rank
        $rank = Ranking::find(1);

        // Determine location
        $location = getLocation();
        $phone = $location->dial_code . ' ';
        $country = $location->name;

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'first_name' => $socialUser->getName(),
                'last_name' => $socialUser->getName(),
                'username' => $socialUser->getNickname() ?? 'user_' . rand(1000, 9999),
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'country' => $country,
                'phone' => $phone,
                'password' => Hash::make(Str::random(12)),
                'ranking_id' => $rank->id,
                'rankings' => json_encode([$rank->id]),
                'email_verified_at' => now(),
                'in_grace_period' => setting('grace_period', 'customer_misc') && !$socialUser->getEmail(),
            ]
        );

        // Update provider info if user already exists (in case they switch providers)
        if (!$user->wasRecentlyCreated) {
            $user->update([
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar() ?: $user->avatar,
            ]);
        }

        // If the user is newly created, handle bonuses, notifications, and referral
        if ($user->wasRecentlyCreated) {
            // Apply bonuses and notify
            $this->applyBonuses($user, $rank);
            $this->notifyUser($user, [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);

            // Handle referral logic
            if ($referralCode) {
                $this->handleReferral($referralCode, $user);
            }
        }

        // Log in the user
        Auth::login($user);

        // Log the activity
        LoginActivities::add();

        return redirect(RouteServiceProvider::HOME)->with('success', 'Logged in via ' . ucfirst($provider));
    }


    private function validateRegularRegistration(Request $request)
    {
        $isUsername = (bool) getPageSetting('username_show');
        $isCountry = (bool) getPageSetting('country_show');
        $isPhone = (bool) getPageSetting('phone_show');
        $isPhoneRestricted = (bool) setting('phone_number_restriction', 'permission');

        $phoneRules = [Rule::requiredIf($isPhone), 'string', 'max:255'];
        
        // Add unique validation if phone restriction is enabled (regardless of phone_show)
        if ($isPhoneRestricted) {
            $phoneRules[] = 'unique:users,phone';
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users'],
            'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
            'phone' => $phoneRules,
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => Rule::requiredIf(plugin_active('Google reCaptcha')), new Recaptcha(),
            'i_agree' => ['required'],
        ]);
    }
    private function createUser(array $input, $rank, $phone, $country)
    {
        return User::create([
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'username' => $input['username'] ?? $input['first_name'] . $input['last_name'] . rand(1000, 9999),
            'country' => $country,
            'phone' => $phone,
            'email' => $input['email'],
            'account_limit' => setting('forex_account_create_limit', 'forex_account_settings'),
            'password' => Hash::make($input['password']),
            'in_grace_period' => setting('grace_period', 'customer_misc', false),
        ]);
    }
    private function applyBonuses(User $user, $rank)
    {
        if ($rank->bonus > 0) {
            Txn::new($rank->bonus, 0, $rank->bonus, 'system', 'Ranking Bonus From ' . $rank->ranking, TxnType::Bonus, TxnStatus::Success, null, null, $user->id);
            $user->increment('profit_balance', $rank->bonus);
        }

        if (setting('referral_signup_bonus', 'permission') && (float) setting('signup_bonus', 'fee') > 0) {
            $signupBonus = (float) setting('signup_bonus', 'fee');
            $user->increment('profit_balance', $signupBonus);
            Txn::new($signupBonus, 0, $signupBonus, 'system', 'Signup Bonus', TxnType::SignupBonus, TxnStatus::Success, null, null, $user->id);
            Session::put('signup_bonus', $signupBonus);
        }
    }
    private function notifyUser(User $user, array $input)
    {
        $shortcodes = [
            '[[full_name]]' => $input['first_name'] . ' ' . $input['last_name'],
            '[[message]]' => '.New User added to our system.',
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];
        if (! setting('email_verification', 'permission')) {
            $this->mailNotify($user->email, 'new_user', $shortcodes);
            $this->pushNotify('new_user', $shortcodes, route('admin.user.edit', $user->id), $user->id);
            $this->smsNotify('new_user', $shortcodes, $user->phone);
        }

        // Notify admin(s) about new registration
        try {
            $rawAdminEmails = (string) setting('user_site_email', 'global');
            if (!empty($rawAdminEmails)) {
                $adminShortcodes = [
                    '[[full_name]]' => $input['first_name'] . ' ' . $input['last_name'],
                    '[[email]]' => $input['email'],
                    '[[site_title]]' => setting('site_title', 'global'),
                    '[[site_url]]' => route('home'),
                ];
                $emails = collect(preg_split('/[;,]/', $rawAdminEmails))
                    ->map(function ($e) { return trim($e); })
                    ->filter(function ($e) { return !empty($e); })
                    ->unique()
                    ->values();

                foreach ($emails as $email) {
                    $this->mailNotify($email, 'admin_new_user_registered_user', $adminShortcodes);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send admin new user registration email: ' . $e->getMessage());
        }
    }
    private function handleReferral($referralCode, User $user, $schemaID = null)
    {
        if (!$referralCode) {
            return;
        }

        // Check if the referral code belongs to an Admin (Staff)
        $admin = Admin::with('branches')->where('referral_code', $referralCode)->first();
        if ($admin) {
            // Attach the new user under the referring staff (admin)
            $admin->users()->attach($user->id);

            // Auto-assign branch if admin has a branch assigned
            if ($admin->branches && $admin->branches->count() > 0) {
                $adminBranchId = $admin->branches->first()->id;
                setUserBranchId($user->id, $adminBranchId);
            }

            // Send notification to Admin (Optional)
            $shortcodes = [
                '[[admin_name]]' => $admin->name,
                '[[child_full_name]]' => $user->first_name . ' ' . $user->last_name,
                '[[child_email]]' => $user->email,
            ];
            $this->mailNotify($admin->email, 'new_user_under_staff', $shortcodes);

            \Cookie::queue(\Cookie::forget('invite'));
            return;
        }

        $referralLink = ReferralLink::where('code', $referralCode)->first();
        if ($referralLink) {
            $referrer = User::with('user_metas')->find($referralLink->user_id);

            if ($referrer) {
                $user->ref_id = $referrer->id;
                $user->save();

                // Determine nearest approved IB ancestor and set child's master IB accordingly
                $nearestApprovedParent = app(\App\Services\UserIbNetworkService::class)->findNearestApprovedParentIb($user);
                if ($nearestApprovedParent && !is_null($nearestApprovedParent->ib_group_id)) {
                    $user->user_metas()->updateOrCreate(
                        ['meta_key' => 'is_part_of_master_ib'],
                        ['meta_value' => $nearestApprovedParent->ib_group_id]
                    );
                }

                // Auto-assign branch if referrer has a branch assigned
                $referrerBranchId = getUserBranchId($referrer->id, $referrer);
                if ($referrerBranchId) {
                    setUserBranchId($user->id, $referrerBranchId);
                }
            }
        }

        // If referral code is from a regular user
        event(new UserReferred($referralCode, $user, $schemaID));

        // Clear the referral cookie
        \Cookie::queue(\Cookie::forget('invite'));
    }

    private function formatPhone(array $input, $location)
    {
        $isPhone = (bool) getPageSetting('phone_show');
        $isCountry = (bool) getPageSetting('country_show');
        return $isPhone ? ($isCountry ? explode(':', $input['country'])[1] : $location->dial_code) . ' ' . $input['phone'] : $location->dial_code . ' ';
    }
    private function getCountry(array $input, $location)
    {
        $isCountry = (bool) getPageSetting('country_show');
        return $isCountry ? explode(':', $input['country'])[0] : $location->name;
    }



    /**
     * Display the registration view.
     *
     * @return View
     */
    public function create()
    {
//        dd('s');
        if (! setting('account_creation', 'permission')) {
            abort('403', 'User registration is closed now');
        }

        $page = Page::where('code', 'registration')->where('locale', app()->getLocale())->first();
        $data = json_decode($page->data, true);

        $googleReCaptcha = plugin_active('Google reCaptcha');
        $cloudflareTurnstile = plugin_active('Cloudflare Turnstile');

        $cloudflareTurnstileData = [];
        if ($cloudflareTurnstile && is_string($cloudflareTurnstile->data)) {
            $cloudflareTurnstileData = json_decode($cloudflareTurnstile->data, true) ?? [];
        }

        // Pass site_key separately for clean Blade usage
        $siteKey = $cloudflareTurnstileData['site_key'] ?? null;

        $location = getLocation();
//        dd($location);

        return view('frontend::auth.register', compact('location', 'googleReCaptcha', 'cloudflareTurnstile', 'cloudflareTurnstileData', 'siteKey', 'data'));
    }

    public function iframeRegister()
    {
        if (! setting('account_creation', 'permission')) {
            abort('403', 'User registration is closed now');
        }

        $page = Page::where('code', 'registration')->where('locale', app()->getLocale())->first();
        $data = json_decode($page->data, true);

        $googleReCaptcha = plugin_active('Google reCaptcha');
        $location = getLocation();

        return view('frontend::auth.iframe-register', compact('location', 'googleReCaptcha', 'data'));
    }
}
