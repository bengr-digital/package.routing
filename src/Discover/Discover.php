<?php

namespace Bengr\Routing\Discover;

class Discover
{
    public static function register($config)
    {
        if (array_key_exists('controllers', $config)) {
            self::controllers($config['controllers']);
        }
    }

    public static function controllers($config)
    {
        collect($config['paths'])->each(function (string $path) use ($config) {
            new DiscoverControllers($path, $config['transformers'], $config['middleware']);
        });
    }
}
