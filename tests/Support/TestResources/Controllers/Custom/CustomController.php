<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\Custom;

use Bengr\Routing\Attributes\Route;

class CustomController
{
    #[Route(method: "PUT")]
    public function customHttpMethod()
    {
        return "custom method controller";
    }

    #[Route(uri: "custom-custom-uri")]
    public function customUri()
    {
        return "custom uri controller";
    }

    #[Route(fullUri: "custom-full-uri")]
    public function customFullUri()
    {
        return "Custom full uricontroller";
    }

    #[Route(name: "custom-custom-name")]
    public function customName()
    {
        return "Custom full uricontroller";
    }

    #[Route(middleware: "api")]
    public function customMiddleware()
    {
        return "Custom full uricontroller";
    }
}
