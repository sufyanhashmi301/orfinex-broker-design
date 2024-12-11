<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Social;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        $socialConfig = Social::getProviderConfig($provider);
//        dd($provider,$socialConfig);

        if (!$socialConfig) {
            abort(404, 'Social provider not found or inactive.');
        }

        // Dynamically configure Socialite
        config([
            "services.{$provider}.client_id" => $socialConfig->client_id,
            "services.{$provider}.client_secret" => $socialConfig->client_secret,
            "services.{$provider}.redirect" => $socialConfig->redirect,
        ]);

        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialConfig = Social::getProviderConfig($provider);

        if (!$socialConfig) {
            abort(404, 'Social provider not found or inactive.');
        }

        // Dynamically configure Socialite
        config([
            "services.{$provider}.client_id" => $socialConfig->client_id,
            "services.{$provider}.client_secret" => $socialConfig->client_secret,
            "services.{$provider}.redirect" => $socialConfig->redirect,
        ]);

        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Find or create the user
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]
        );

        Auth::login($user, true);
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
