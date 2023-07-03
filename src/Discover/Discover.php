<?php

namespace Bengr\Routing\Discover;

class Discover
{
    public static function register(array $paths = [], array $middleware = [], array $transformers = [])
    {
        collect($paths)->each(function (string $path) use ($middleware, $transformers) {
            new DiscoverControllers($path, $transformers, $middleware);
        });
    }
}
