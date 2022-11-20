<?php

namespace Bengr\Routing\Discover;

use Bengr\Routing\Facades\Routing;

class DiscoverControllers
{
    protected string $basePath = '';

    protected string $rootNamespace;

    public function __construct($path, $transformers, $middleware)
    {
        $this->rootNamespace = '';
        $this->path = $path;
        $this->transformers = $transformers;
        $this->middleware = $middleware;
        $this->basePath = base_path();

        Routing::useRootNamespace($this->rootNamespace)
            ->useBasePath($this->basePath)
            ->registerPath($this->path, $this->transformers, $this->middleware);
    }

    public function useRootNamespace(string $rootNamespace): self
    {
        $this->rootNamespace = $rootNamespace;

        return $this;
    }

    public function useBasePath(string $basePath): self
    {
        $this->basePath = $basePath;

        return $this;
    }
}
