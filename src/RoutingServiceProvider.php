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

        $this->app->singleton('routing', function ($app) {
            return new Routing($app->router);
        });

        app('routing')->configure();
    }

    public function routesAreCached()
    {
        return $this->app instanceof CachesRoutes && $this->app->routesAreCached();
    }

    public function boot()
    {
    }
}
