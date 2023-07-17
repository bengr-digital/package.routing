<?php

namespace Bengr\Routing\Registrars\Livewire\Transformers;

use Bengr\Routing\Registrars\Livewire\PendingRoutes\PendingRoute;
use Bengr\Routing\Transformers\Transformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class HandleRouteNameAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            if (!$routeAttribute = $route->getRouteAttribute()) {
                $route->name = Str::of($route->uri)
                    ->trim('/')
                    ->replace('/', '.');

                return;
            }

            if (!$name = $routeAttribute->name) {
                $route->name = Str::of($route->uri)
                    ->trim('/')
                    ->replace('/', '.');

                return;
            }

            $route->name = $name;
        });
    }
}
