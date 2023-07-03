<?php

namespace Bengr\Routing\Tests;

use Bengr\Routing\RoutingManager;
use Bengr\Routing\RoutingServiceProvider;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Arr;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected RoutingManager $routingManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->routingManager = $this->app->make(RoutingManager::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            RoutingServiceProvider::class,
        ];
    }

    public function getTestPath(string $directory = null): string
    {
        return realpath(__DIR__ . '/' . $directory);
    }

    public function getTestControllerPath(string $controllerPath): string
    {
        return $this->getTestPath('Support/TestResources/Controllers/' . $controllerPath);
    }

    public function getTestRoutePath(string $routePath): string
    {
        return $this->getTestPath('Support/TestResources/routes/' . $routePath);
    }

    public function getTestNamespace(): string
    {
        return 'Bengr\\Routing\\Tests\\';
    }

    protected function getRouteCollection(): RouteCollection
    {
        return $this->routingManager->getRouter()->getRoutes();
    }

    protected function registerDiscoveredRoutesFromConfig()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->registerRoutes();
    }

    public function assertRegisteredRoutesCount(int $expectedCount): self
    {
        $actualNumber = $this->getRouteCollection()->count();

        $this->assertEquals($expectedCount, $actualNumber);

        return $this;
    }

    public function assertRouteRegistered(
        string $controller = null,
        string $controllerMethod = null,
        string | array $httpMethods = null,
        string $uri = null,
        string | array $middleware = null,
        string $name = null
    ): self {
        if (!is_array($httpMethods)) {
            $httpMethods = Arr::wrap($httpMethods);
        }

        if (!is_array($middleware)) {
            $middleware = Arr::wrap($middleware);
        }

        $routeRegistered = collect($this->getRouteCollection()->getRoutes())
            ->contains(function (Route $route) use ($controller, $controllerMethod, $httpMethods, $uri, $middleware, $name) {
                if ($controller !== null) {
                    if ($controller !== ($route->getAction(0) ?? get_class($route->getController()))) {
                        return false;
                    }
                }

                if ($controllerMethod !== null) {
                    $actionMethod = ($route->getAction(1) ?? $route->getActionMethod());

                    if ($route->getActionMethod() === get_class($route->getController())) {
                        $actionMethod = '__invoke';
                    }

                    if ($controllerMethod !== $actionMethod) {
                        return false;
                    }
                }

                if ($httpMethods !== null) {
                    foreach ($httpMethods as $httpMethod) {
                        if (!in_array(strtoupper($httpMethod), $route->methods)) {
                            return false;
                        }
                    }
                }

                if ($uri !== null) {
                    if ($uri !== $route->uri()) {
                        return false;
                    }
                }

                if ($middleware !== null) {
                    if (array_diff($middleware, $route->middleware())) {
                        return false;
                    }
                }

                if ($name !== null) {
                    if ($name !== $route->getName()) {
                        return false;
                    }
                }

                return true;
            });

        $this->assertTrue($routeRegistered, 'The expected route was not registered');

        return $this;
    }
}
