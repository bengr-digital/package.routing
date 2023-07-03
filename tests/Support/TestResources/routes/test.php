<?php

use Bengr\Routing\Tests\Support\TestResources\Controllers\Simple\SimpleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "home route";
});

Route::prefix('/users')->group(function () {
    Route::get('/', [SimpleController::class, 'index']);
    Route::get('/create', [SimpleController::class, 'create']);
    Route::post('/create', [SimpleController::class, 'save']);
    Route::get('/{user}', [SimpleController::class, 'detail']);
    Route::put('/{user}', [SimpleController::class, 'update']);
});
