<?php

namespace Bengr\Routing\Transformers;

use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Support\Collection;

class AddDefaultRouteName implements Transformer
{

    public function transform(Collection $routes): Collection
    {

        return $routes->each(function (PendingRoute $route) {
            $route->actions
                ->reject(fn (PendingRouteAction $action) => $action->name)
                /** @phpstan-ignore-next-line */
                ->each(fn (PendingRouteAction $action) => $action->name = $this->generateRouteName($action));
        });
    }

    protected function generateRouteName(PendingRouteAction $routeAction): string
    {
        return collect(explode('/', $routeAction->uri))
            ->reject(fn (string $segment) => str_starts_with($segment, '{'))
            ->join('.');
    }
}
