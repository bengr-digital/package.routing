<?php

namespace Bengr\Routing;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\ServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/routing.php', 'routing');

        if ($this->routesAreCached()) return;

        $this->app->singleton(RoutingManager::class, function ($app) {
            return new RoutingManager($app->router);
        });

        if ($this->app->environment() !== 'testing') {
            $this->app->make(RoutingManager::class)->registerRoutes();
        }
    }

    public function routesAreCached()
    {
        return $this->app instanceof CachesRoutes && $this->app->routesAreCached();
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/routing.php' => config_path('routing.php'),
        ], 'routing-config');
    }
}
