<?php

namespace Bengr\Routing\Base;

use Bengr\Routing\Exceptions\Base\FilePropertyNotFoundException;
use Illuminate\Support\Facades\Route;

class Base
{
    public static function register(array $groups = [])
    {
        collect($groups)->each(function (array $group) {
            if (!key_exists('file', $group)) {
                throw new FilePropertyNotFoundException('`file` property not found inside of group of `base` routing driver configuration');
            }

            Route::prefix($group['prefix'] ?? null)
                ->middleware($group['middleware'] ?? null)
                ->group($group['file']);
        });
        return;
    }
}
