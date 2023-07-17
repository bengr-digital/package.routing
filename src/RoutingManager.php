<?php

namespace Bengr\Routing;

use Bengr\Routing\Base\Base;
use Bengr\Routing\Discover\Discover;
use Bengr\Routing\Registrars\Livewire\LivewireRegistrar;
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

    public function getRegistrars(): array
    {
        $registrarNames = is_array(config('routing.default')) ? config('routing.default') : array(config('routing.default'));
        $registrars = [];

        foreach ($registrarNames as $registrarName) {
            if (!key_exists($registrarName, config('routing.registrars'))) {
                throw new \Exception("registrar `{$registrarName}` does not exist inside of `routing.registrars`");
            }
            $registrars[$registrarName] = config('routing.registrars')[$registrarName];
        }

        return $registrars;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getDiscoverPaths(array $registrar): array
    {
        return count($this->discoverPaths) ? $this->discoverPaths : $registrar['paths'];
    }

    public function getDiscoverMiddleware(array $registrar): array
    {
        return count($this->discoverMiddleware) ? $this->discoverMiddleware : $registrar['middleware'];
    }

    public function getDiscoverTransformers(array $registrar): array
    {
        return count($this->discoverTransformers) ? $this->discoverTransformers : $registrar['transformers'];
    }

    public function getBaseGroups(array $registrar): array
    {
        return count($this->baseGroups) ? $this->baseGroups : $registrar['groups'];
    }

    public function registerRoutes()
    {
        foreach ($this->getRegistrars() as $registrar) {
            switch ($registrar['driver']) {
                case 'discover':
                    Discover::register($this->getDiscoverPaths($registrar), $this->getDiscoverMiddleware($registrar), $this->getDiscoverTransformers($registrar));
                    break;
                case 'base':
                    Base::register($this->getBaseGroups($registrar));
                    break;
                case 'livewire':
                    LivewireRegistrar::make($registrar);
                    break;
                default:
                    return;
            }
        }
    }
}
