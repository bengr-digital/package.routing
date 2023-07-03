<?php

namespace Bengr\Routing;

use Bengr\Routing\Base\Base;
use Bengr\Routing\Discover\Discover;
use Illuminate\Routing\Router;

class RoutingManager
{
    private Router $router;

    protected ?string $basePath = null;

    protected ?string $rootNamespace = null;

    protected ?string $registrar = null;

    protected array $discoverPaths = [];

    protected array $discoverMiddleware = [];

    protected array $discoverTransformers = [];

    protected array $baseGroups = [];

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->basePath = base_path();
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

    public function registrar(string $registrar): self
    {
        $this->registrar = $registrar;

        return $this;
    }

    public function discoverPaths(array $paths): self
    {
        $this->discoverPaths = $paths;

        return $this;
    }

    public function discoverMiddleware(array $middleware): self
    {
        $this->discoverMiddleware = $middleware;

        return $this;
    }

    public function discoverTransformers(array $transformers): self
    {
        $this->discoverTransformers = $transformers;

        return $this;
    }

    public function baseGroups(array $groups): self
    {
        $this->baseGroups = $groups;

        return $this;
    }

    public function getBasePath(): ?string
    {
        return $this->basePath;
    }

    public function getRootNamespace(): ?string
    {
        return $this->rootNamespace;
    }

    public function getRegistrar(): array
    {
        $registrarName = $this->registrar ?? config('routing.default');

        if (!key_exists($registrarName, config('routing.registrars'))) {
            throw new \Exception("registrar `{$registrarName}` does not exist inside of `routing.registrars`");
        }

        return config('routing.registrars')[$registrarName];
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getDiscoverPaths(): array
    {
        return count($this->discoverPaths) ? $this->discoverPaths : $this->getRegistrar()['paths'];
    }

    public function getDiscoverMiddleware(): array
    {
        return count($this->discoverMiddleware) ? $this->discoverMiddleware : $this->getRegistrar()['middleware'];
    }

    public function getDiscoverTransformers(): array
    {
        return count($this->discoverTransformers) ? $this->discoverTransformers : $this->getRegistrar()['transformers'];
    }

    public function getBaseGroups(): array
    {
        return count($this->baseGroups) ? $this->baseGroups : $this->getRegistrar()['groups'];
    }

    public function registerRoutes()
    {
        switch ($this->getRegistrar()['driver']) {
            case 'discover':
                Discover::register($this->getDiscoverPaths(), $this->getDiscoverMiddleware(), $this->getDiscoverTransformers());
                break;
            case 'base':
                Base::register($this->getBaseGroups());
                break;
            default:
                return;
        }
    }
}
