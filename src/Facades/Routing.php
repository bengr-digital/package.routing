<?php

namespace Bengr\Routing\Facades;

use Bengr\Routing\RoutingManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void configure()
 * @method static \Illuminate\Support\Collection | null routes()
 * @method static \Bengr\Routing\RoutingManager useBasePath(string $basePath)
 * @method static \Bengr\Routing\RoutingManager useRootNamespace(string $rootNamespace)
 * @method static void registerPath(string $path, mixed $transformers, mixed $middleware)
 * @method static \Illuminate\Support\Collection convertToPendingRoutes(string $basePath)
 * @method static \Illuminate\Support\Collection transformRoutes(mixed $transformers, mixed $middleware)
 * @method static void registerRoutes()
 *
 * @see \Bengr\Routing\RoutingManager
 */

class Routing extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RoutingManager::class;
    }
}
