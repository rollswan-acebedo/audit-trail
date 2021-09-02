<?php

namespace Rollswan\AuditTrail\Providers;

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
        $router->middlewareGroup('audit-trail', [AuditTrailMiddleware::class]);
    }
    
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (file_exists(config_path('audit-trail.php'))) {
            $this->mergeConfigFrom(config_path('audit-trail.php'), 'AuditTrail');
        } else {
            $this->mergeConfigFrom(__DIR__ . '/../config/audit-trail.php', 'AuditTrail');
        }

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
