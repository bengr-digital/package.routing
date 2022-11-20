<?php

namespace Bengr\Routing;

use Bengr\Routing\Discover\Discover;
use Bengr\Routing\PendingRoutes\PendingRoute;
use Bengr\Routing\PendingRoutes\PendingRouteAction;
use Bengr\Routing\PendingRoutes\PendingRouteFactory;
use Bengr\Routing\Transformers\Transformer;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Symfony\Component\Finder\Finder;

class Routing
{
    private Router $router;

    protected string $basePath;

    protected string $rootNamespace;

    protected string $registeringPath = '';

    protected Collection | null $transformedRoutes = null;

    public function __construct(Router $router)
    {
        $this->router = $router;

        $this->basePath = app()->path();
    }

    public function configure()
    {

        if (!array_key_exists(config('routing.default'), config('routing.registrars'))) return;

        $registrar = config('routing.registrars')[config('routing.default')];


        switch ($registrar['driver']) {
            case 'discover':
                Discover::register($registrar);
                break;
            default:
                return;
        }
    }

    public function routes()
    {
        return $this->transformedRoutes;
    }

    public function useBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function registerPath(string $path, $transformers, $middleware): void
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
                $route = $this->router->addRoute($action->methods, $action->uri, $action->action());
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
