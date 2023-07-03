<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\Attributes\Where;
use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;

class HandleWheresAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            $route->actions->each(function (PendingRouteAction $action) use ($route) {
                if ($pendingRouteWhereAttribute = $route->getAttribute(Where::class)) {
                    $action->addWhere($pendingRouteWhereAttribute);
                }

                if ($actionWhereAttribute = $action->getAttribute(Where::class)) {
                    $action->addWhere($actionWhereAttribute);
                }
            });
        });
    }
}
