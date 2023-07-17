<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\Prefix;

use Bengr\Routing\Attributes\Prefix;

#[Prefix('custom-prefix')]
class PrefixController
{
    public function index()
    {
        return 'prefix controller';
    }
}
