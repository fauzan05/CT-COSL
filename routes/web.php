<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/api/login', [AuthController::class, 'postLogin'])->name('login');
Route::get('/api/current-user', [AuthController::class, 'currentUser'])->name('currentUser');

Route::get('/login', function () {
    return view('auth');
})->name('auth');

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
