<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;

class HandleDomainAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            $route->actions->each(function (PendingRouteAction $action) use ($route) {
                if ($pendingRouteAttribute = $route->getRouteAttribute()) {
                    $action->domain = $pendingRouteAttribute->domain;
                }

                if ($actionAttribute = $action->getRouteAttribute()) {
                    $action->domain = $actionAttribute->domain;
                }
            });
        });
    }
}
