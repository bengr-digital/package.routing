<?php

namespace Bengr\Routing\Tests\Unit;

use Bengr\Routing\Tests\Support\TestResources\Controllers\Custom\CustomController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\Invokable\InvokableController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\Nested\FirstNest\FirstNestController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\Nested\FirstNest\SecondNest\SecondNestController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\Nested\ZeroNestController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\NestedNonMatching\First\FirstNestController as NestedNonMatchingFirstNestController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\NestedNonMatching\First\Second\SecondNestController as NestedNonMatchingSecondNestController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\NestedNonMatching\ZeroNestController as NestedNonMatchingZeroNestController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\NoDiscoverOnMethod\NoDiscoverOnMethodController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\Prefix\PrefixController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\Simple\SimpleController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\Unified\UnifiedController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\WithModel\WithModelController;
use Bengr\Routing\Tests\TestCase;

class DiscoverRegistrarTest extends TestCase
{
    public function test_discovering_routes_from_simple_controller()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('Simple')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(1);

        $this->assertRouteRegistered(
            controller: SimpleController::class,
            controllerMethod: 'index',
            httpMethods: 'GET',
            uri: 'simples',
            name: 'simples'
        );
    }

    public function test_discovering_routes_from_controller_with_custom_route_attributes()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('Custom')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(5);

        $this->assertRouteRegistered(
            controller: CustomController::class,
            controllerMethod: 'customHttpMethod',
            httpMethods: 'PUT'
        );

        $this->assertRouteRegistered(
            controller: CustomController::class,
            controllerMethod: 'customUri',
            uri: 'customs/custom-custom-uri'
        );

        $this->assertRouteRegistered(
            controller: CustomController::class,
            controllerMethod: 'customFullUri',
            uri: 'custom-full-uri'
        );

        $this->assertRouteRegistered(
            controller: CustomController::class,
            controllerMethod: 'customName',
            name: 'custom-custom-name'
        );

        $this->assertRouteRegistered(
            controller: CustomController::class,
            controllerMethod: 'customMiddleware',
            middleware: 'api'
        );
    }

    public function test_discovering_routes_from_controller_with_prefix()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('Prefix')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(1);

        $this->assertRouteRegistered(
            controller: PrefixController::class,
            controllerMethod: 'index',
            httpMethods: 'GET',
            uri: 'custom-prefix',
        );
    }

    public function test_discovering_routes_from_invokable_controller()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('Invokable')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(1);

        $this->assertRouteRegistered(
            controller: InvokableController::class,
            controllerMethod: '__invoke',
            uri: 'invokables',
        );
    }

    public function test_discovering_routes_from_nested_controllers()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('Nested')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(3);

        $this->assertRouteRegistered(
            controller: ZeroNestController::class,
            controllerMethod: 'index',
            uri: 'zero-nests',
        );

        $this->assertRouteRegistered(
            controller: FirstNestController::class,
            controllerMethod: 'index',
            uri: 'first-nests',
        );

        $this->assertRouteRegistered(
            controller: SecondNestController::class,
            controllerMethod: 'index',
            uri: 'first-nests/second-nests',
        );
    }

    public function test_discovering_routes_from_non_matching_nested_controllers()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('NestedNonMatching')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(3);

        $this->assertRouteRegistered(
            controller: NestedNonMatchingZeroNestController::class,
            controllerMethod: 'index',
            uri: 'zero-nests',
        );

        $this->assertRouteRegistered(
            controller: NestedNonMatchingFirstNestController::class,
            controllerMethod: 'index',
            uri: 'firsts/first-nests',
        );

        $this->assertRouteRegistered(
            controller: NestedNonMatchingSecondNestController::class,
            controllerMethod: 'index',
            uri: 'firsts/seconds/second-nests',
        );
    }

    public function test_discovering_routes_from_controller_with_unified_names()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('Unified')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(5);

        $this->assertRouteRegistered(
            controller: UnifiedController::class,
            controllerMethod: 'index',
            httpMethods: 'GET',
            uri: 'unifieds',
        );

        $this->assertRouteRegistered(
            controller: UnifiedController::class,
            controllerMethod: 'create',
            httpMethods: 'POST',
            uri: 'unifieds',
        );

        $this->assertRouteRegistered(
            controller: UnifiedController::class,
            controllerMethod: 'update',
            httpMethods: 'PUT',
            uri: 'unifieds',
        );

        $this->assertRouteRegistered(
            controller: UnifiedController::class,
            controllerMethod: 'delete',
            httpMethods: 'DELETE',
            uri: 'unifieds',
        );

        $this->assertRouteRegistered(
            controller: UnifiedController::class,
            controllerMethod: 'default',
            httpMethods: 'GET',
            uri: 'unifieds/default',
        );
    }

    public function test_discovering_routes_from_controller_with_no_discover_attribute()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('NoDiscover')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(0);
    }

    public function test_discovering_routes_from_controller_with_no_discover_attribute_on_method()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('NoDiscoverOnMethod')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(1);

        $this->assertRouteRegistered(
            controller: NoDiscoverOnMethodController::class,
            controllerMethod: 'discoverThisOne',
            httpMethods: 'GET',
            uri: 'no-discover-on-methods/discover-this-one',
        );
    }

    public function test_discovering_routes_from_controller_with_model_parameters_on_method()
    {
        $this->routingManager
            ->registrar('discover')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->discoverPaths([$this->getTestControllerPath('WithModel')])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(1);

        $this->assertRouteRegistered(
            controller: WithModelController::class,
            controllerMethod: 'withModel',
            uri: 'with-models/{user}/with-model',
        );
    }
}
