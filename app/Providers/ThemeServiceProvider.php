<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $theme = site_theme(); // e.g. 'brokeret' or 'default'

        $themePath   = resource_path("views/frontend/{$theme}");
        $defaultPath = resource_path("views/frontend/default");

        // ✅ Register theme views under "frontend::"
        $this->loadViewsFrom($themePath, 'frontend');
        $this->loadViewsFrom($defaultPath, 'frontend'); // fallback

        // ✅ Register components for <x-frontend::card />
        Blade::anonymousComponentPath("{$themePath}/components", 'frontend');
        Blade::anonymousComponentPath("{$defaultPath}/components", 'frontend');

    }
}
