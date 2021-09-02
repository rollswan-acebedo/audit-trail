<?php

namespace Rollswan\AuditTrail\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Rollswan\AuditTrail\Middleware\AuditTrailMiddleware;

class AuditTrailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('audit-trail', [AuditTrailMiddleware::class]);

        // Make the configuration files publishable
        $this->publishes(
            [
                __DIR__ . '/../config/audit-trail.php' => config_path('audit-trail.php')
            ],
            'config'
        );
      
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
