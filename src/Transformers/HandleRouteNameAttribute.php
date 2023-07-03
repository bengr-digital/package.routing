<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;

class HandleRouteNameAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            $route->actions->each(function (PendingRouteAction $action) {
                if (!$routeAttribute = $action->getRouteAttribute()) {
                    return;
                }

                if (!$name = $routeAttribute->name) {
                    return;
                }

                $action->name = $name;
            });
        });
    }
}
