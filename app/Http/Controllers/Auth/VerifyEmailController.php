<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserReferred;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\NotifyTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerifyEmailController extends Controller
{
    use NotifyTrait;
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));

            //referral code
            event(new UserReferred($request->cookie('invite'), $request->user()));
            //send welcome email
            $user = $request->user();
            $shortcodes = [
                '[[full_name]]' => $user->first_name . ' ' . $user->last_name,
                '[[message]]' => '.New User added to our system.',
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];
            $this->mailNotify($user->email, 'new_user', $shortcodes);
            $this->pushNotify('new_user', $shortcodes, route('admin.user.edit', $user->id), $user->id);
            $this->smsNotify('new_user', $shortcodes, $user->phone);
    }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

    public function __invoke_code(Request $request)
    {

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:4',
        ]);

        if ($validator->fails()) {
            return back()->with('status', 'invalid-code');
        }
        $user = User::where('email', $request->user()->email)
            ->where('verification_code', $request->verification_code)
            ->first(['verification_code','verification_code_expires_at']);

        if (!$user || Carbon::now()->greaterThan($user->verification_code_expires_at)) {
            return back()->with('status', 'invalid-code');
        }
//        dd($request->all(),);


        if ($request->user()->markEmailAsVerified()) {

//            dd($user,$request->user());
            $request->user()->verification_code = null;
            $request->user()->verification_code_expires_at = null;
            $request->user()->save();

            event(new Verified($request->user()));

            //referral code
            event(new UserReferred($request->cookie('invite'), $request->user()));
            //send welcome email
            $user = $request->user();
            $shortcodes = [
                '[[full_name]]' => $user->first_name . ' ' . $user->last_name,
                '[[message]]' => '.New User added to our system.',
                '[[site_title]]' => setting('site_title', 'global'),
                '[[site_url]]' => route('home'),
            ];
            $this->mailNotify($user->email, 'new_user', $shortcodes);
            $this->pushNotify('new_user', $shortcodes, route('admin.user.edit', $user->id), $user->id);
            $this->smsNotify('new_user', $shortcodes, $user->phone);
    }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }

}
