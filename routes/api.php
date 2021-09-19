<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\HouseTypeController;
use App\Http\Controllers\UsersHouseController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Bridge\User;

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
    'resourceAndStatus',
    function ($name, $controller, $model) {
        Route::patch($name . '/{' . $model . ':id}/activate', [$controller, 'activate'])->name($name . '.activate');
        Route::patch($name . '/{' . $model . ':id}/deactivate', [$controller, 'deactivate'])->name($name . '.deactivate');
        Route::patch($name . '/{' . $model . ':id}/suspend', [$controller, 'suspend'])->name($name . '.suspend');
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

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
        ->middleware(['signed'])
        ->name('verification.verify');


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.api');

    Route::post('/email/verification-notification', [AuthController::class, 'resendEmailVerify'])
        ->middleware(['auth:api', 'throttle:6,1'])
        ->name('verification.send');

    Route::middleware(['auth:api', 'verified', 'isDeactivated'])->group(function () {

        Route::get('/email/verify', function () {
            return response()
                ->json([
                    'message' => 'Verification email has been sent!',
                    'status' => "success"
                ]);
        })->middleware('auth')->name('verification.notice');

        Route::get('/estates/export', [EstateController::class, 'export'])->name('estates.export');
        Route::resourceAndStatus("estates", EstateController::class, 'estate');
        Route::get('/estates/import', [EstateController::class, 'import'])->name('estates.import');
        Route::resourceAndStatus("admins", AdminController::class, "admin");
        Route::resourceAndStatus("users-house", UsersHouseController::class, "usershouse");

        Route::apiResource('users', UserController::class);
        Route::apiResource('profiles', ProfileController::class);
        Route::apiResource("houses", HouseController::class);
        Route::apiResource('house-types', HouseTypeController::class);

        Route::apiResource('visitors', VisitorController::class);
        // our routes to be protected will go in here
    });
});
