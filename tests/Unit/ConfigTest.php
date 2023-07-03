<?php

namespace Bengr\Routing\Tests\Unit;

use Bengr\Routing\Tests\Support\TestResources\Controllers\Simple\SimpleController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\WithMiddleware\WithDefaultMiddlewareController;
use Bengr\Routing\Tests\Support\TestResources\Controllers\WithMiddleware\WithMiddlewareController;
use Bengr\Routing\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_discovering_routes_from_controllers_in_path()
    {
        config()->set('routing.registrars.discover.paths', [$this->getTestControllerPath('Simple')]);

        $this->registerDiscoveredRoutesFromConfig();

        $this->assertRegisteredRoutesCount(1);

        $this->assertRouteRegistered(
            controller: SimpleController::class
        );
    }

    public function test_discoverting_routes_from_controllers_in_path_with_middleware()
    {
        config()->set('routing.registrars.discover.paths', [$this->getTestControllerPath('WithMiddleware')]);

        $this->registerDiscoveredRoutesFromConfig();

        $this->assertRouteRegistered(
            controller: WithMiddlewareController::class,
            controllerMethod: 'index',
            middleware: ['web', 'auth', 'throttle']
        );
    }

    public function test_discoverting_routes_from_controllers_in_path_with_no_middleware()
    {
        config()->set('routing.registrars.discover.paths', [$this->getTestControllerPath('WithMiddleware')]);

        $this->registerDiscoveredRoutesFromConfig();

        $this->assertRouteRegistered(
            controller: WithDefaultMiddlewareController::class,
            controllerMethod: 'index',
            middleware: config('routing.registrars.discover.middleware')
        );
    }
}
