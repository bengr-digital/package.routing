<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\NoDiscoverOnMethod;

use Bengr\Routing\Attributes\NoDiscover;

class NoDiscoverOnMethodController
{
    #[NoDiscover]
    public function index()
    {
        return 'no discover on method controller';
    }

    public function discoverThisOne()
    {
        return 'no discover on method controller';
    }
}
