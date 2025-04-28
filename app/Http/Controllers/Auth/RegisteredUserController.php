<?php

namespace App\Http\Controllers\Auth;

use Txn;
use Session;
use Carbon\Carbon;
use App\Models\KYC;
use App\Models\Page;
use App\Models\User;
use App\Enums\TxnType;
use App\Models\Ranking;
use App\Models\Setting;
use App\Enums\TxnStatus;
use App\Rules\Recaptcha;
use Illuminate\View\View;
use App\Models\MultiLevel;
use App\Traits\NotifyTrait;
use App\Events\UserReferred;
use Illuminate\Http\Request;
use App\Enums\KycStatusEnums;
use App\Models\UserAffiliate;
use App\Models\LoginActivities;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    use NotifyTrait;

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

    /**
     * Handle an incoming registration request.
     *
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function store(Request $request)
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
            'level' => ['sometimes', 'exists:multi_levels,id']
        ]);

        // Email Verification
        if(!$this->verifyEmail($request->input('email'))) {
            notify()->error('Email address is not valid');
            return redirect()->back();
        }

        $input = $request->all();
        $multiLevel = $request->level;
        $location = getLocation();
        $phone = $isPhone ? ($isCountry ? explode(':', $input['country'])[1] : $location->dial_code).' '.$input['phone'] : $location->dial_code.' ';
        $country = $isCountry ? explode(':', $input['country'])[0] : $location->name;

        $rank = Ranking::find(1);

        $user = User::create([
            'ranking_id' => $rank->id,
            'rankings' => json_encode([$rank->id]),
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'username' => $isUsername ? $input['username'] : $input['first_name'].$input['last_name'].rand(1000, 9999),
            'country' => $country,
            'phone' => $phone,
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'email_verified_at' => env('EMAIL_VERIFICATION', false) ? null : Carbon::now(),
        ]);

        // Assign referring id
        $referrer = UserAffiliate::where('referral_link', $request->referral);
        if($referrer->exists()){
            $user->referred_by = $referrer->first()->user->id;
            $user->save();
        }

        if ($rank->bonus > 0) {
            Txn::new($rank->bonus, 0, $rank->bonus, 'system', 'Ranking Bonus From '.$rank->ranking, TxnType::Bonus, TxnStatus::Success, null, null, $user->id);
            $user->increment('profit_balance', $rank->bonus);
        }

        $shortcodes = [
            '[[full_name]]' => $input['first_name'].' '.$input['last_name'],
            '[[message]]' => '.New User added our system.',
            '[[site_title]]' => setting('site_title', 'global'),
            '[[site_url]]' => route('home'),
        ];

        // KYC
        if(!$user->kyc) {
            $kyc = new KYC();
            $kyc->user_id = $user->id;
            $kyc->method = '';
            $kyc->status = KycStatusEnums::UNVERIFIED;
            $kyc->save();
        }

        // notify method call
        $this->mailNotify($user->email, 'new_user', $shortcodes);
        $this->pushNotify('new_user', $shortcodes, route('admin.user.edit', $user->id), $user->id);
        $this->smsNotify('new_user', $shortcodes, $user->phone);

        if(!$multiLevel){
            $multiLevel = null;
        }

        Auth::login($user);
        LoginActivities::add();

        return redirect(RouteServiceProvider::HOME);
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
//        dd('s');
        $location = getLocation();
        $legal_links = Setting::where('name', 'LIKE', '%legal_%')->where('name', 'LIKE', '%_signup%')->get();

        return view('frontend::auth.register', compact('location', 'googleReCaptcha', 'data', 'legal_links'));
    }
}
