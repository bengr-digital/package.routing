<?php

namespace Bengr\Routing\Registrars\Livewire\Transformers;

use Bengr\Routing\Attributes\NoDiscover;
use Bengr\Routing\Registrars\Livewire\PendingRoutes\PendingRoute;
use Bengr\Routing\Transformers\Transformer;
use Illuminate\Support\Collection;

class HandleNoDiscoverAttribute implements Transformer
{

    public function transform(Collection $routes): Collection
    {
        return $routes->reject(fn (PendingRoute $route) => $route->getAttribute(NoDiscover::class));
    }
}
