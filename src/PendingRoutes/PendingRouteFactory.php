<?php

namespace Bengr\Routing\PendingRoutes;

use Illuminate\Support\Str;

class PendingRouteFactory
{
    public function __construct(public string $basePath, public string $rootNamespace, public string $registeringPath, public $middlewares)
    {
    }

    public function make(\SplFileInfo $fileInfo): ?PendingRoute
    {
        $fullyQualifiedClassName = $this->getFullyQualifiedClassName($fileInfo);

        if (!class_exists($fullyQualifiedClassName)) return null;

        $class = new \ReflectionClass($fullyQualifiedClassName);

        if ($class->isAbstract()) return null;

        $actions = collect($class->getMethods())
            ->filter(function (\ReflectionMethod $method) {
                return $method->isPublic();
            })
            ->map(function (\ReflectionMethod $method) use ($fullyQualifiedClassName) {
                return new PendingRouteAction($method, $fullyQualifiedClassName, $this->middlewares);
            });

        $uri = $this->discoverUri($class);

        return new PendingRoute($fileInfo, $class, $uri, $fullyQualifiedClassName, $actions);
    }

    protected function discoverUri(\ReflectionClass $class): string
    {
        $parts = Str::of($class->getFileName())
            ->after(str_replace('/', DIRECTORY_SEPARATOR, $this->registeringPath))
            ->beforeLast('Controller')
            ->explode(DIRECTORY_SEPARATOR);

        return collect($parts)
            ->filter()
            ->unique()
            ->reject(function (string $part) {
                return strtolower($part) === 'index';
            })
            ->map(fn (string $part) => Str::of($part)->kebab() . 's')
            ->implode('/');
    }

    public function getFullyQualifiedClassName(\SplFileInfo $fileInfo): string
    {
        $class = trim(Str::replaceFirst($this->basePath, '', (string)$fileInfo->getRealPath()), DIRECTORY_SEPARATOR);

        $class = str_replace(
            [DIRECTORY_SEPARATOR, 'App\\'],
            ['\\', app()->getNamespace()],
            ucfirst(Str::replaceLast('.php', '', $class))
        );

        return $this->rootNamespace . $class;
    }
}
