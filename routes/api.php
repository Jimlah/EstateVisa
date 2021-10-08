<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\HouseTypeController;
use App\Http\Controllers\EstateAdminController;
use App\Http\Controllers\EstateHouseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::macro(
    'resourceWithExtra',
    function ($name, $controller, $model) {
        Route::patch($name . '/{' . $model . ':id}/activate', [$controller, 'activate'])->name($name . '.activate');
        Route::patch($name . '/{' . $model . ':id}/deactivate', [$controller, 'deactivate'])->name($name . '.deactivate');
        Route::patch($name . '/{' . $model . ':id}/suspend', [$controller, 'suspend'])->name($name . '.suspend');
        Route::get($name . '/export', [$controller, 'export'])->name($name . '.export');
        Route::post($name . '/import', [$controller, 'import'])->name($name . '.import');
        Route::apiResource($name, $controller);
    }
);


Route::middleware(['json.response', 'cors'])->group(function () {

    Route::group([], function () {
        // public routes
        Route::post('/login', [AuthController::class, 'login'])
            ->middleware('isDeactivated')
            ->name('login.api');

        Route::post('/register', [AuthController::class, 'register'])->name('register.api');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.api');
        Route::post('/reset-password', [AuthController::class, 'passwordReset'])->name('password.reset');
    });


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.api');


    Route::middleware(['auth:api'])->group(function () {

        Route::get('/email/verify', function () {
            return response()
                ->json([
                    'message' => 'Verification email has been sent!',
                    'status' => "success"
                ]);
        })->middleware('auth')->name('verification.notice');

        Route::resourceWithExtra("estates", EstateController::class, 'estate');
        Route::resourceWithExtra("admins", AdminController::class, 'admin');
        Route::resourceWithExtra("estate-admins", EstateAdminController::class, 'estateAdmin');
        Route::apiResource('house-types', HouseTypeController::class);
        // Route::resource('houses', HouseController::class);
        Route::apiResource('estate-houses', EstateHouseController::class);

        // our routes to be protected will go in here
    });
});

Route::fallback(
    function () {
        return response()->json([
            'message' => 'Page Not Found.',
            'status' => 'error'
        ], 404);
    }
);
