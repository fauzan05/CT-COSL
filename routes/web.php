<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/api/login', [AuthController::class, 'postLogin'])->name('login');

// SPA route
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');