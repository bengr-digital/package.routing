<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HandleUriAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            $route->actions->each(function (PendingRouteAction $action) {
                if (!$routeAttribute = $action->getRouteAttribute()) return;
                if (!$routeAttributeUri = $routeAttribute->uri) return;
                if ($routeAttributeUri === '/') return;

                $baseUri = Str::beforeLast($action->uri, '/');
                $action->uri = $baseUri . '/' . trim($routeAttributeUri, '/');
            });
        });
    }
}
