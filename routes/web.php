<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToolstringController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/login', function () {
    return view('auth');
})->name('auth');

Route::post('/api/login', [AuthController::class, 'postLogin'])->name('login');


// API routes that need auth
Route::middleware([AuthMiddleware::class])->group(function () {
    Route::post('/api/toolstring-categories', [ToolstringController::class, 'storeCategory'])->name('storeCategory');
    Route::get('/api/toolstring-categories', [ToolstringController::class, 'getCategories'])->name('getCategories');
    Route::put('/api/toolstring-categories/{id}', [ToolstringController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/api/toolstring-categories/{id}', [ToolstringController::class, 'deleteCategory'])->name('deleteCategory');
    Route::get('/api/current-user', [AuthController::class, 'currentUser'])->name('currentUser');
});


// Catch-all for frontend SPA (protected)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->middleware([AuthMiddleware::class]);

