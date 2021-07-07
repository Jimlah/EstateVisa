<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VisitorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    Route::post('/register',[AuthController::class, 'register'])->name('register.api');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.api');
    Route::post('/reset-password', [AuthController::class, 'passwordReset'])->name('password.reset');

    Route::apiResource('visitors', VisitorController::class);

});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'Auth\ApiAuthController@logout')->name('logout.api');
    // our routes to be protected will go in here
});
