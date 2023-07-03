<?php

namespace Bengr\Routing\PendingRoutes;

use Bengr\Routing\Attributes\Attribute;
use Bengr\Routing\Attributes\Prefix;
use Bengr\Routing\Attributes\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PendingRoute
{
    public function __construct(
        public \SplFileInfo $fileInfo,
        public \ReflectionClass $class,
        public string $uri,
        public string $fullyQualifiedClassName,
        public Collection $actions,
    ) {
    }

    public function namespace(): string
    {
        return Str::beforeLast($this->fullyQualifiedClassName, '\\');
    }

    public function shortControllerName(): string
    {
        return Str::of($this->fullyQualifiedClassName)
            ->afterLast('\\')
            ->beforeLast('Controller');
    }

    public function childNamespace(): string
    {
        return $this->namespace() . '\\' . $this->shortControllerName();
    }

    public function getRouteAttribute(): ?Route
    {
        return $this->getAttribute(Route::class);
    }

    public function getPrefixAttribute(): ?Prefix
    {
        return $this->getAttribute(Prefix::class);
    }

    public function getAttribute(string $attributeClass): ?Attribute
    {
        $attributes = $this->class->getAttributes($attributeClass, \ReflectionAttribute::IS_INSTANCEOF);

        if (!count($attributes)) {
            return null;
        }

        return $attributes[0]->newInstance();
    }
}
