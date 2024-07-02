<?php

declare(strict_types=1);

use App\Http\Controllers\Quotes\KanyeQuotesController;
use App\Http\Controllers\Quotes\RefreshKanyeQuotesController;
use App\Http\Middleware\EnsureApiKeyIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(EnsureApiKeyIsValid::class)->group(function () {
    Route::get('/quotes/kanye-west', KanyeQuotesController::class);
    Route::get('/quotes/refresh/kanye-west', RefreshKanyeQuotesController::class);
});
