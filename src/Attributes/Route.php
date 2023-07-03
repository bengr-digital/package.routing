<?php

namespace Bengr\Routing\Attributes;

use Illuminate\Routing\Router;
use Illuminate\Support\Arr;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class Route implements Attribute
{
    public array $methods;

    public array $middleware;

    /**
     * @param array<int, string>|string $method
     * @param string|null $uri
     * @param string|null $fullUri
     * @param string|null $name
     * @param array<int, class-string>|string $middleware
     */
    public function __construct(
        array | string $method = [],
        public ?string $uri = null,
        public ?string $fullUri = null,
        public ?string $name = null,
        array | string $middleware = [],
        public ?string $domain = null,
    ) {
        $methods = Arr::wrap($method);

        $this->methods = collect($methods)
            ->map(fn (string $method) => strtoupper($method))
            ->filter(fn (string $method) => in_array($method, Router::$verbs))
            ->toArray();


        $this->middleware = Arr::wrap($middleware);
    }
}
