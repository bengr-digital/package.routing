<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\NoDiscover;

use Bengr\Routing\Attributes\NoDiscover;

#[NoDiscover]
class NoDiscoverController
{
    public function index()
    {
        return 'no discover controller';
    }
}
