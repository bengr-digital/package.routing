<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\WithMiddleware;

use Bengr\Routing\Attributes\Route;

class WithMiddlewareController
{
    #[Route(middleware: ['web', 'auth', 'throttle'])]
    public function index()
    {
        return "with middleware controller";
    }
}
