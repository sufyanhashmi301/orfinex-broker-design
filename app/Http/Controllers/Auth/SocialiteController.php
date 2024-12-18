<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Social;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to the social provider's login page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($provider)
    {
        $socialConfig = Social::where('driver', $provider)->where('status', 1)->first();

        if (!$socialConfig) {
            return redirect()->route('login')->with('error', 'Social provider not available or inactive.');
        }

        // Dynamically configure the provider
        $this->setSocialiteConfig($provider, $socialConfig);

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the social provider's callback.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        $socialConfig = Social::where('driver', $provider)->where('status', 1)->first();

        if (!$socialConfig) {
            return redirect()->route('login')->with('error', 'Social provider not available or inactive.');
        }

        // Dynamically configure the provider
        $this->setSocialiteConfig($provider, $socialConfig);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Delegate user handling to the registration controller
            return app(RegisteredUserController::class)->handleSocialRegistration($socialUser, $provider);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with ' . ucfirst($provider));
        }
    }

    /**
     * Dynamically configure the socialite provider.
     *
     * @param string $provider
     * @param Social $socialConfig
     * @return void
     */
    private function setSocialiteConfig($provider, $socialConfig)
    {
        Config::set("services.{$provider}.client_id", $socialConfig->client_id);
        Config::set("services.{$provider}.client_secret", $socialConfig->client_secret);
        Config::set("services.{$provider}.redirect", $socialConfig->redirect);
    }
}
