<?php

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

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group([
    'prefix' => 'v1',
    'middleware' => 'apiDebugbar',
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('confirm-register', [AuthController::class, 'confirmRegister'])->name('confirm-register');
    Route::post('resend-register-email', [AuthController::class, 'resendRegisterEmail']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::get('verify-token-reset-password', [AuthController::class, 'verifyTokenResetPassword']);
    Route::group([
        'middleware' => ['auth:sanctum'],
    ], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
    });

    Route::get('products', [ProductController::class, 'get']);
    Route::post('products', [ProductController::class, 'create']);
    Route::put('products/{productId}', [ProductController::class, 'update']);
    Route::delete('products/{productId}', [ProductController::class, 'delete']);
});
