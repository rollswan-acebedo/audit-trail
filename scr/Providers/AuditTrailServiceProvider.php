<?php

namespace Rollswan\AuditTrail\Providers;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Rollswan\AuditTrail\Middleware\AuditTrailMiddleware;

class AuditTrailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $source = realpath($raw = __DIR__ . '/../config/audit-trail.php') ?: $raw;

        $router->middlewareGroup('audit-trail', [AuditTrailMiddleware::class]);

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            // Make the configuration files publishable
            $this->publishes([$source => config_path('audit-trail.php')], 'audit-trail');
        }
      
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (file_exists(config_path('audit-trail.php'))) {
            $this->mergeConfigFrom(config_path('audit-trail.php'), 'audit-trail');
        } else {
            $this->mergeConfigFrom(__DIR__.'/config/audit-trail.php', 'audit-trail');
        }
    }
}
