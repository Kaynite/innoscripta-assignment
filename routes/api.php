<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login')->middleware('guest', 'throttle:5,1');
Route::post('register', [AuthController::class, 'register'])->name('register')->middleware('guest', 'throttle:5,1');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
Route::post('forgot-password', [ResetPasswordController::class, 'sendCode'])->name('password.forgot')->middleware('guest', 'throttle:5,1');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset')->middleware('guest', 'throttle:5,1');
Route::get('user', [UserController::class, 'me'])->name('users.me')->middleware('auth:sanctum');
Route::put('user', [UserController::class, 'update'])->name('users.update')->middleware('auth:sanctum');

Route::middleware('throttle:30,1')->group(function () {
    Route::apiResource('articles', ArticleController::class)->only(['index', 'show']);
    Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    Route::get('feed', FeedController::class)->name('feed')->middleware('auth:sanctum');
});
