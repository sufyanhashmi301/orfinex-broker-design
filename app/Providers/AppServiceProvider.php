<?php

namespace App\Providers;

use App\Models\Theme;
use App\Models\Language;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application modules.
     *
     * @return void
     */
    public function register()
    {
        Paginator::defaultView('frontend::include.__pagination');
        
        // Register MT5DatabaseService as singleton
        $this->app->singleton(\App\Services\MT5DatabaseService::class, function ($app) {
            return new \App\Services\MT5DatabaseService();
        });

    }

    /**
     * Bootstrap any application modules.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        if(is_force_https()){
            URL::forceScheme('https');
        }

        // IMPORTANT: Always use UTC for database storage
        // The site_timezone setting is ONLY for display purposes
        // All database records are stored in UTC regardless of site_timezone setting
        config()->set([
            'app.timezone' => 'UTC', // Always UTC for database storage
            'app.debug' => setting('debug_mode', 'permission'),
            'app.locale' => Language::where('is_default', '=', true)->first('locale')->locale ?? 'en',
        ]);
        date_default_timezone_set('UTC'); // Always UTC for PHP default timezone

        Blade::directive('lasset', function ($expression) {
            $customLandingTheme = Theme::where('type', 'landing')->where('status', true)->first();
            if ($customLandingTheme) {
                return asset("landing_theme/$customLandingTheme->name/$expression");
            }
            return false;
        });



        Blade::directive('removeimg', function ($expression) {
            list($isHidden, $img_field) = explode(',', $expression);
            $isHidden = trim($isHidden);
            $img_field = trim($img_field);

            return "<?php \$isHidden = $isHidden; \$img_field = '$img_field'; ?>
            <div data-des=\"<?php echo \$img_field; ?>\" <?php if(!\$isHidden) echo 'hidden'; ?> class=\"close remove-img <?php echo \$img_field; ?>\"><i icon-name=\"x\"></i></div>";
        });


        // setting session expiry dynamically + SMTP failure alerts
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->session_expiry) {
                    config(['session.lifetime' => $user->session_expiry]);
                }
                
                // Share SMTP failure status with admin views
                // Support both 'admin' and 'Super-Admin' roles
                if (in_array(strtolower($user->role), ['admin', 'super-admin'])) {
                    $view->with('smtpFailureActive', session('smtp_failure_active', false));
                    $view->with('smtpFailureData', [
                        'message' => session('smtp_failure_message'),
                        'count' => session('smtp_failure_count'),
                        'timestamp' => session('smtp_failure_timestamp'),
                        'last_updated' => session('smtp_failure_last_updated'),
                    ]);
                }
            }
        });

    }
}
