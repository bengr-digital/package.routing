<?php

namespace Bengr\Routing\Registrars\Livewire;

use Bengr\Routing\Registrars\Livewire\PendingRoutes\PendingRoute;
use Bengr\Routing\Registrars\Livewire\PendingRoutes\PendingRouteFactory;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;
use Bengr\Routing\Registrars\Registrar;
use Bengr\Routing\Transformers\Transformer;
use Bengr\Routing\Facades\Routing;

class LivewireRegistrar extends Registrar
{
    protected array $paths;

    protected array $middleware;

    protected array $transformers;

    protected string $registeringPath;

    protected Collection | null $pendingRoutes = null;

    protected Collection | null $transformedRoutes = null;

    public function register(): void
    {
        $this->paths = $this->getConfig('paths') ?? [];
        $this->middleware = $this->getConfig('middleware') ?? [];
        $this->transformers = $this->getConfig('transformers') ?? [];

        foreach ($this->paths as $path) {

            $this->registerPath($path, $this->transformers, $this->middleware);
        }
    }

    public function registerPath(string $path, array $transformers, array $middleware)
    {
        $this->registeringPath = $path;

        $this->pendingRoutes = $this->convertToPendingRoutes($path, $middleware);

        $this->transformedRoutes = $this->transformRoutes($transformers);

        $this->registerRoutes();
    }

    public function convertToPendingRoutes(string $path, array $middleware): Collection
    {

        $files = (new Finder())->files()->depth(0)->name('*.php')->in($path);

        $routeFactory = new PendingRouteFactory(base_path(), '', $this->registeringPath, $middleware);

        $routes = collect($files)
            ->map(fn (\SplFileInfo $file) => $routeFactory->make($file))
            ->filter();


        collect((new Finder())->directories()->depth(0)->in($path))
            ->flatMap(function (\SplFileInfo $subPath) use ($middleware) {
                return $this->convertToPendingRoutes($subPath, $middleware);
            })
            ->filter()
            ->each(fn (PendingRoute $route) => $routes->push($route));

        return $routes;
    }

    public function transformRoutes($transformers): Collection
    {
        $routes = $this->pendingRoutes;

        $transformers = collect($transformers)
            ->map(fn (string $transformerClass): Transformer => app($transformerClass));

        foreach ($transformers as $transformer) {
            $routes = $transformer->transform($routes);
        }


        return $routes;
    }

    public function registerRoutes(): void
    {
        $this->transformedRoutes->each(function (PendingRoute $transformedRoute) {
            $route = Routing::getRouter()->addRoute($transformedRoute->methods, $transformedRoute->uri, $transformedRoute->fullyQualifiedClassName);
            $route->middleware($transformedRoute->middleware);

            $route->name($transformedRoute->name);
        });
    }
}
