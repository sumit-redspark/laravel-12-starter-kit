<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Admin API v1 routes
Route::group(['prefix' => 'admin/v1'], function ()
{
    Route::post('login', [UserController::class, 'login']);

    Route::middleware('auth:api')->group(function ()
    {
        Route::post('user', [UserController::class, 'userDetails']);
    });
});
