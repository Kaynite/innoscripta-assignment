<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
Route::get('user', [UserController::class, 'me'])->name('users.me')->middleware('auth:sanctum');
Route::put('user', [UserController::class, 'update'])->name('users.update')->middleware('auth:sanctum');

Route::apiResource('articles', ArticleController::class)->only(['index', 'show']);
Route::apiResource('authors', AuthorController::class)->only(['index', 'show']);
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::get('feed', FeedController::class)->name('feed')->middleware('auth:sanctum');
