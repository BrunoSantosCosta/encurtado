<?php
use App\Http\Controllers\UrlController;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/', [UrlController::class, 'shorten'])->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/{shortUrl}', [UrlController::class, 'redirect']);
