<?php

namespace Bengr\Routing\Transformers;

use App\Http\Controllers\Controller as AppController;
use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class DeleteDefaultControllerMethodRoutes implements Transformer
{
    public array $methodsToDelete = [
        AppController::class,
        Controller::class,
    ];

    public function transform(Collection $routes): Collection
    {
        return $routes->each(function (PendingRoute $route) {
            $route->actions = $route
                ->actions
                ->reject(fn (PendingRouteAction $routeAction) => in_array(
                    $routeAction->method->class,
                    $this->methodsToDelete
                ));
        });
    }
}
