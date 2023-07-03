<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\PendingRoutes\PendingRoute;
use Illuminate\Support\Collection;

class HandlePrefixAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes
            ->each(function (PendingRoute $route) {
                if (!$routePrefix = $route->getPrefixAttribute()) {
                    return;
                }

                if (!$prefix = $routePrefix->prefix) {
                    return;
                }

                $route->uri = trim($prefix, '/');
            });
    }
}
