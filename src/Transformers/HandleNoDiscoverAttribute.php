<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\Attributes\NoDiscover;
use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;

class HandleNoDiscoverAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes
            ->reject(fn (PendingRoute $route) => $route->getAttribute(NoDiscover::class))
            ->each(function (PendingRoute $route) {
                $route->actions = $route
                    ->actions
                    ->reject(fn (PendingRouteAction $action) => $action->getAttribute(NoDiscover::class));
            });
    }
}
