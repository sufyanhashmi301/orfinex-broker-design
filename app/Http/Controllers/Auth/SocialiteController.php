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

        try {
            // LinkedIn requires OpenID Connect - use linkedin-openid driver
            if ($provider === 'linkedin') {
                // Use linkedin-openid driver which uses OpenID Connect instead of OAuth 2.0
                return Socialite::driver('linkedin-openid')->redirect();
            }
            
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            \Log::error('Social login redirect failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('login')->with('error', 'Social login redirect failed. Please check your configuration.');
        }
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

        // Check for error response from provider (especially LinkedIn)
        if (request()->has('error')) {
            $error = request()->get('error');
            $errorDescription = request()->get('error_description', 'Unknown error');
            
            \Log::error('Social login provider returned error', [
                'provider' => $provider,
                'error' => $error,
                'error_description' => $errorDescription
            ]);
            
            $errorMessage = 'Social login failed. Please try again.';
            if ($provider === 'linkedin') {
                if ($error === 'unauthorized_scope_error') {
                    $errorMessage = "LinkedIn login failed: OpenID Connect is required. Please enable 'Sign In with LinkedIn using OpenID Connect' product in your LinkedIn Developer Portal.";
                } else {
                    $errorMessage = "LinkedIn login failed: {$errorDescription}";
                }
            }
            
            return redirect()->route('login')->with('error', $errorMessage);
        }

        // Dynamically configure the provider
        $this->setSocialiteConfig($provider, $socialConfig);
        try {
            // LinkedIn requires OpenID Connect - use linkedin-openid driver
            if ($provider === 'linkedin') {
                // Use linkedin-openid driver which uses OpenID Connect instead of OAuth 2.0
                $socialUser = Socialite::driver('linkedin-openid')->stateless()->user();
            } else {
                $socialUser = Socialite::driver($provider)->stateless()->user();
            }
            
            $referralCode = request()->cookie('invite');

            // Pass referral code to handleSocialRegistration
            return app(RegisteredUserController::class)
                ->handleSocialRegistration($socialUser, $provider, $referralCode);
        } catch (\Exception $e) {
            \Log::error('Social login callback failed', [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'redirect_uri' => Config::get("services.{$provider}.redirect"),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Provide more specific error message for LinkedIn
            $errorMessage = 'Social login failed. Please try again.';
            if ($provider === 'linkedin') {
                $redirectUri = Config::get("services.linkedin-openid.redirect") ?? Config::get("services.{$provider}.redirect");
                if (str_contains($e->getMessage(), 'redirect_uri') || str_contains($e->getMessage(), 'unauthorized_scope')) {
                    $errorMessage = "LinkedIn login failed. Please ensure 'Sign In with LinkedIn using OpenID Connect' product is enabled in your LinkedIn Developer Portal and redirect URI '{$redirectUri}' is added in Authorized Redirect URLs.";
                } elseif (str_contains($e->getMessage(), 'code')) {
                    $errorMessage = "LinkedIn authorization failed. Please check your LinkedIn app configuration and ensure OpenID Connect is enabled.";
                }
            }
            
            return redirect()->route('login')->with('error', $errorMessage);
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
        // Use the actual request URL to ensure redirect URI matches exactly
        // This handles subdirectories and different domains correctly
        $scheme = request()->getScheme();
        $host = request()->getHttpHost();
        $path = request()->getBasePath(); // Gets the subdirectory path like '/brokeret'
        
        // Construct redirect URL with subdirectory if present
        $baseUrl = rtrim("{$scheme}://{$host}{$path}", '/');
        $redirectUrl = "{$baseUrl}/{$provider}/callback";

        // Log for debugging (remove in production if needed)
        \Log::info('Social login redirect URI configured', [
            'provider' => $provider,
            'redirect_uri' => $redirectUrl,
            'scheme' => $scheme,
            'host' => $host,
            'path' => $path,
            'full_url' => request()->fullUrl()
        ]);

        // For LinkedIn, configure both 'linkedin' and 'linkedin-openid' services
        // The linkedin-openid driver reads from 'linkedin-openid' config
        if ($provider === 'linkedin') {
            Config::set("services.linkedin-openid.client_id", $socialConfig->client_id);
            Config::set("services.linkedin-openid.client_secret", $socialConfig->client_secret);
            Config::set("services.linkedin-openid.redirect", $redirectUrl);
        }
        
        // Also set the regular provider config for other providers
        Config::set("services.{$provider}.client_id", $socialConfig->client_id);
        Config::set("services.{$provider}.client_secret", $socialConfig->client_secret);
        Config::set("services.{$provider}.redirect", $redirectUrl);
    }
}
