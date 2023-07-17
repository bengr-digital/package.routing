<?php

namespace Bengr\Routing\Registrars\Livewire\Transformers;

use Bengr\Routing\Registrars\Livewire\PendingRoutes\PendingRoute;
use Bengr\Routing\Transformers\Transformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HandleUriAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            if (!$routeAttribute = $route->getRouteAttribute()) {
                return;
            }

            if (!$uri = $routeAttribute->uri) {
                return;
            }

            $route->uri = $uri;
        });
    }
}
