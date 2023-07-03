<?php

namespace Bengr\Routing\Discover;

use Bengr\Routing\Facades\Routing;
use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Bengr\Routing\PendingRoutes\PendingRouteFactory;
use Bengr\Routing\Transformers\Transformer;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

class DiscoverControllers
{
    protected string $basePath = '';

    protected string $rootNamespace;

    protected $path;

    protected $transformers;

    protected $middleware;

    protected ?string $registeringPath = null;

    protected Collection | null $transformedRoutes = null;

    protected Collection | null $pendingRoutes = null;

    public function __construct($path, $transformers, $middleware)
    {
        $this->path = $path;
        $this->transformers = $transformers;
        $this->middleware = $middleware;
        $this->basePath = Routing::getBasePath() ?? base_path();
        $this->rootNamespace = Routing::getRootNamespace() ?? '';

        $this->useRootNamespace($this->rootNamespace)
            ->useBasePath($this->basePath)
            ->registerPath($this->path, $this->transformers, $this->middleware);
    }

    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function useBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function registerPath(string $path, mixed $transformers, mixed $middleware): void
    {
        $this->registeringPath = $path;

        $this->pendingRoutes = $this->convertToPendingRoutes($path, $middleware);

        $this->transformedRoutes = $this->transformRoutes($transformers, $middleware);

        $this->registerRoutes();
    }

    public function convertToPendingRoutes(string $path, $middleware): Collection
    {
        $files = (new Finder())->files()->depth(0)->name('*.php')->in($path);

        $routeFactory = new PendingRouteFactory($this->basePath, $this->rootNamespace, $this->registeringPath, $middleware);

        $routes = collect($files)
            ->map(fn (\SplFileInfo $file) => $routeFactory->make($file))
            ->filter();

        collect((new Finder())->directories()->depth(0)->in($path))
            ->flatMap(function (\SplFileInfo $subPath) use ($middleware) {
                return $this->convertToPendingRoutes($subPath, $middleware);
            })
            ->filter()
            /** @phpstan-ignore-next-line */
            ->each(fn (PendingRoute $route) => $routes->push($route));

        return $routes;
    }

    public function transformRoutes($transformers, $middleware): Collection
    {
        $routes = $this->convertToPendingRoutes($this->registeringPath, $middleware);

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
            $transformedRoute->actions->each(function (PendingRouteAction $action) {
                $route = Routing::getRouter()->addRoute($action->methods, $action->uri, $action->action());
                $route->middleware($action->middleware);

                $route->name($action->name);

                if (count($action->wheres)) {
                    $route->setWheres($action->wheres);
                }

                if ($action->domain) {
                    $route->domain($action->domain);
                }
            });
        });
    }
}
