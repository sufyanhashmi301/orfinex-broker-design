<?php

namespace App\Http\Controllers\Auth;

use App\Enums\TxnStatus;
use App\Enums\TxnType;
use App\Events\UserReferred;
use App\Http\Controllers\Controller;
use App\Models\LoginActivities;
use App\Models\MultiLevel;
use App\Models\Page;
use App\Models\Ranking;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Rules\Recaptcha;
use App\Traits\NotifyTrait;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;
use Session;
use Txn;

class RegisteredUserController extends Controller
{
    use NotifyTrait;

    /**
     * Handle an incoming registration request.
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        // Check if the request is from Socialite
        if ($request->has('provider')) {
            return $this->handleSocialRegistration($request);
        }

        // Regular Registration Logic
        $this->validateRegularRegistration($request);
        $input = $request->all();

        $schemaID = $request->schema ? decrypt($request->schema) : null;
        $location = getLocation();
        $phone = $this->formatPhone($input, $location);
        $country = $this->getCountry($input, $location);

        $rank = Ranking::find(1);

        $user = $this->createUser($input, $rank, $phone, $country);

        $this->applyBonuses($user, $rank);
        $this->notifyUser($user, $input);
        $this->handleReferral($request, $user, $schemaID);

        Auth::login($user);
        LoginActivities::add();

        return redirect(RouteServiceProvider::HOME);
    }

    private function handleSocialRegistration(Request $request)
    {
        try {
            $socialUser = Socialite::driver($request->provider)->stateless()->user();

            // Default rank
            $rank = Ranking::find(1);

            // Determine phone and country based on user's location
            $location = getLocation();
            $phone = $location->dial_code . ' ';
            $country = $location->name;

            // Find or create the user
            $user = User::updateOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'first_name' => $socialUser->getName(),
                    'last_name' => $socialUser->getName(),
                    'username' => $socialUser->getName() . rand(1000, 9999),
                    'provider_id' => $socialUser->getId(),
                    'provider_name' => $request->provider,
                    'avatar' => $socialUser->getAvatar(),
                    'ranking_id' => $rank->id,
                    'rankings' => json_encode([$rank->id]),
                    'email_verified_at' => Carbon::now(),
                    'country' => $country,
                    'phone' => $phone,
                    'password' => Hash::make(str_random(12)), // Random password
                ]
            );

            // Apply bonuses and notify user
            $this->applyBonuses($user, $rank);
            $this->notifyUser($user, [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ]);

            Auth::login($user);
            LoginActivities::add();

            return redirect(RouteServiceProvider::HOME)->with('success', 'Logged in successfully via ' . ucfirst($request->provider));
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Unable to authenticate using ' . ucfirst($request->provider));
        }
    }
    private function validateRegularRegistration(Request $request)
    {
        $isUsername = (bool) getPageSetting('username_show');
        $isCountry = (bool) getPageSetting('country_show');
        $isPhone = (bool) getPageSetting('phone_show');

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => [Rule::requiredIf($isUsername), 'string', 'max:255', 'unique:users'],
            'country' => [Rule::requiredIf($isCountry), 'string', 'max:255'],
            'phone' => [Rule::requiredIf($isPhone), 'string', 'max:255'],
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

        $this->mailNotify($user->email, 'new_user', $shortcodes);
        $this->pushNotify('new_user', $shortcodes, route('admin.user.edit', $user->id), $user->id);
        $this->smsNotify('new_user', $shortcodes, $user->phone);
    }
    private function handleReferral(Request $request, User $user, $schemaID)
    {
        event(new UserReferred($request->cookie('invite'), $user, $schemaID));
        \Cookie::forget('invite');
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
