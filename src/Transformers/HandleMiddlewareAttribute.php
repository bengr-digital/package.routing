<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;

class HandleMiddlewareAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            $route->actions->each(function (PendingRouteAction $action) use ($route) {
                if ($pendingRouteAttribute = $route->getRouteAttribute()) {
                    $action->addMiddleware($pendingRouteAttribute->middleware);
                }

                if ($actionRouteAttribute = $action->getRouteAttribute()) {
                    $action->addMiddleware($actionRouteAttribute->middleware);
                }
            });
        });
    }
}
