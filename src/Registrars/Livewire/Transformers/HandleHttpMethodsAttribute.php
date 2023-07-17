<?php

namespace Bengr\Routing\Registrars\Livewire\Transformers;

use Bengr\Routing\Attributes\NoDiscover;
use Bengr\Routing\Registrars\Livewire\PendingRoutes\PendingRoute;
use Bengr\Routing\Transformers\Transformer;
use Illuminate\Support\Collection;

class HandleHttpMethodsAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            if (!$routeAttribute = $route->getRouteAttribute()) {
                return;
            }

            if (!$methods = $routeAttribute->methods) {
                return;
            }

            $route->methods = $methods;
        });
    }
}
