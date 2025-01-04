<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to News Aggregator API, to get started, visit the documentation at '.url('/docs'),
    ]);
});
