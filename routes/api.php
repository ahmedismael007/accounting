<?php

use App\Http\Controllers\Central\Tenant\TenantController;
use App\Http\Controllers\Central\User\UserController;
    use App\Http\Middleware\SetLocaleFromHeaders;
    use Illuminate\Support\Facades\Route;

Route::middleware(['api', SetLocaleFromHeaders::class])->group(function () {
    Route::prefix('authentication')->group(function () {
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
        Route::middleware('auth:api')->get('logout', [UserController::class, 'logout']);
    });

    Route::middleware(['auth:api'])->group(function () {
        Route::apiResource('organizations', TenantController::class);
    });
});
