<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\HouseTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersHouseController;
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
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout.api');

    Route::apiResource("estates", EstateController::class);
    Route::post('estates/{id}/disable', [EstateController::class, 'disable'])->name('estates.disable');
    Route::post('estates/{id}/enable', [EstateController::class, 'enable'])->name('estates.enable');

    Route::apiResource('users', UserController::class);
    Route::apiResource('profiles', ProfileController::class);
    Route::apiResource("houses", HouseController::class);
    Route::apiResource('house-types', HouseTypeController::class);
    Route::apiResource("users-house", UsersHouseController::class);

    Route::apiResource('visitors', VisitorController::class);
    // our routes to be protected will go in here
});