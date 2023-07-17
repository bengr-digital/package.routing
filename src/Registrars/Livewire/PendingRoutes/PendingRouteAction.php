<?php

namespace Bengr\Routing\Registrars\Livewire\PendingRoutes;

use Bengr\Routing\Attributes\Attribute;
use Bengr\Routing\Attributes\Route;
use Bengr\Routing\Attributes\Where;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class PendingRouteAction
{
    public \ReflectionMethod $method;

    public string $uri;

    public ?string $name = null;

    public ?string $domain = null;

    public array $methods = [];

    public array $middleware = [];

    public array $wheres = [];

    public array $action;

    public function __construct(\ReflectionMethod $method, string $controllerClass, $middleware)
    {
        $this->method = $method;

        $this->middleware = $middleware;

        $this->uri = $this->discoverUri();

        $this->methods = $this->discoverHttpMethods();

        $this->action = [$controllerClass, $method->name];
    }

    public function discoverUri(): string
    {
        $modelParameters = collect($this->method->getParameters())->where(function (\ReflectionParameter $parameter) {
            return is_a($parameter->getType()?->getName(), Model::class, true);
        });

        $uri = '';

        $uri = $modelParameters->map(function (\ReflectionParameter $parameter) {
            $uri = '';

            if ($parameter->getPosition() === 0 || $parameter->getPosition() === 1) {
                $uri = "{{$parameter->getName()}}";

                if (!in_array($this->method->getName(), $this->commonControllerMethodNames())) {
                    $uri .= '/' . Str::kebab($this->method->getName());
                }
            } else {
                $uri .= "{{$parameter->getName()}}";
            }

            return $uri;
        })->implode('/');


        if (!$modelParameters->count() && !in_array($this->method->getName(), $this->commonControllerMethodNames())) {
            $uri .= Str::kebab($this->method->getName());
        }


        return $uri;
    }

    protected function discoverHttpMethods(): array
    {
        return match ($this->method->getName()) {
            'index', 'detail' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'upload' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
            default => ['GET'],
        };
    }

    protected function commonControllerMethodNames(): array
    {
        return [
            '__invoke', 'index', 'detail',
            'create', 'update', 'delete', 'upload'
        ];
    }

    public function addMiddleware(array|string $middleware): self
    {
        $middleware = Arr::wrap($middleware);

        $allMiddleware = array_merge($middleware, $this->middleware);

        $this->middleware = array_unique($allMiddleware);

        return $this;
    }

    public function addWhere(Where $whereAttribute): self
    {
        $this->wheres[$whereAttribute->param] = $whereAttribute->constraint;

        return $this;
    }

    public function action(): string|array
    {
        return $this->action[1] === '__invoke'
            ? $this->action[0]
            : $this->action;
    }

    public function getRouteAttribute(): ?Route
    {
        return $this->getAttribute(Route::class);
    }

    public function getAttribute(string $attributeClass): ?Attribute
    {
        $attributes = $this->method->getAttributes($attributeClass, \ReflectionAttribute::IS_INSTANCEOF);

        if (!count($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
