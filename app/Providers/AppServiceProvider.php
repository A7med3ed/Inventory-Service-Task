<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Auto-register all module providers like UserServiceProvider, ProductServiceProvider, etc.
        foreach (glob(app_path('Modules/*/Providers/*ServiceProvider.php')) as $providerPath) {
            // Extract module and provider class names
            $moduleName = basename(dirname(dirname($providerPath))); // e.g., 'User'
            $providerFile = basename($providerPath, '.php');        // e.g., 'UserServiceProvider'
            $class = "App\\Modules\\{$moduleName}\\Providers\\{$providerFile}";
            if (class_exists($class)) {
                $this->app->register($class);
            }
        }
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
