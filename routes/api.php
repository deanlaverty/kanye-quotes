<?php

declare(strict_types=1);

use App\Http\Controllers\Quotes\KanyeQuotesController;
use App\Http\Middleware\EnsureApiKeyIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/quotes/kanye-west', KanyeQuotesController::class)
    ->middleware(EnsureApiKeyIsValid::class);
