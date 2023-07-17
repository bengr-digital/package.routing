<?php

namespace Bengr\Routing\Tests\Support\TestResources\Controllers\WithModel;

use Bengr\Routing\Tests\Support\TestResources\Models\User;
use Illuminate\Support\Facades\Request;

class WithModelController
{
    public function withModel(Request $request, User $user)
    {
        return "with model controller";
    }
}
