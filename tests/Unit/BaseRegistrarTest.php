<?php

namespace Bengr\Routing\Tests\Unit;

use Bengr\Routing\Tests\TestCase;

/**
 * TODO implementing tests:
 * 
 * - add group and register routes in that group
 * 
 */

class BaseRegistrarTest extends TestCase
{
    public function test_registering_routes_in_file()
    {
        $this->routingManager
            ->registrar('base')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->baseGroups([
                [
                    'file' => $this->getTestRoutePath('test.php')
                ]
            ])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(6);

        $this->assertRouteRegistered(
            uri: '/',
            httpMethods: 'GET'
        );

        $this->assertRouteRegistered(
            uri: 'users',
            httpMethods: 'GET'
        );

        $this->assertRouteRegistered(
            uri: 'users/create',
            httpMethods: 'GET'
        );

        $this->assertRouteRegistered(
            uri: 'users/create',
            httpMethods: 'POST'
        );

        $this->assertRouteRegistered(
            uri: 'users/{user}',
            httpMethods: 'GET'
        );

        $this->assertRouteRegistered(
            uri: 'users/{user}',
            httpMethods: 'PUT'
        );
    }

    public function test_registering_routes_in_file_with_prefix()
    {
        $this->routingManager
            ->registrar('base')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->baseGroups([
                [
                    'prefix' => 'test',
                    'file' => $this->getTestRoutePath('test.php')
                ]
            ])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(6);

        $this->assertRouteRegistered(
            uri: 'test/users/create',
            httpMethods: 'GET'
        );
    }

    public function test_registering_routes_in_file_with_middleware()
    {
        $this->routingManager
            ->registrar('base')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->baseGroups([
                [
                    'middleware' => 'test',
                    'file' => $this->getTestRoutePath('test.php')
                ]
            ])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(6);

        $this->assertRouteRegistered(
            uri: 'users/create',
            httpMethods: 'GET',
            middleware: 'test'
        );
    }

    public function test_registering_routes_in_file_with_middleware_list()
    {
        $this->routingManager
            ->registrar('base')
            ->useBasePath($this->getTestPath())
            ->useRootNamespace($this->getTestNamespace())
            ->baseGroups([
                [
                    'middleware' => ['test', 'test2'],
                    'file' => $this->getTestRoutePath('test.php')
                ]
            ])
            ->registerRoutes();

        $this->assertRegisteredRoutesCount(6);

        $this->assertRouteRegistered(
            uri: 'users/create',
            httpMethods: 'GET',
            middleware: ['test', 'test2']
        );
    }
}
