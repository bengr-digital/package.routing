<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\WithMiddleware;

class WithDefaultMiddlewareController
{
    public function index()
    {
        return "with default middleware controller";
    }
}
