<?php

namespace Bengr\Routing\Registrars;

class Registrar
{
    protected array $config = [];

    final public function __construct(array $config)
    {
        $this->config = $config;
        $this->register();
    }

    public static function make(array $config): static
    {
        return app(static::class, ['config' => $config]);
    }

    public function register(): void
    {
    }

    public function getConfig(?string $name = null)
    {
        if (!$name) return $this->config;

        return $this->config[$name] ?? null;
    }
}
