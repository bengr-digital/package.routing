<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;

class AddControllerUriToActions implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            $route->actions->each(function (PendingRouteAction $action) use ($route) {
                $originalActionUri = $action->uri;

                $action->uri = $route->uri;

                if ($originalActionUri) {
                    $action->uri .= "/{$originalActionUri}";
                }
            });
        });
    }
}
