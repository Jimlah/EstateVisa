<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitorController;

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

Route::group(['middleware' => ['cors', 'json.response']], function () {
    // public routes
    Route::post('/login', [AuthController::class, 'login'])->name('login.api');
    Route::post('/register', [AuthController::class, 'register'])->name('register.api');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.api');
    Route::post('/reset-password', [AuthController::class, 'passwordReset'])->name('password.reset');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [VisitorController::class, 'logout'])->name('logout.api');

    Route::apiResource('visitors', VisitorController::class);
    Route::apiResource('profiles', ProfileController::class);
    Route::apiResource('users', UserController::class);
    // our routes to be protected will go in here
});
