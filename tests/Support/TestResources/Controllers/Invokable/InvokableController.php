<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\Invokable;

class InvokableController
{
    public function __invoke()
    {
        return 'invokable controller';
    }
}
