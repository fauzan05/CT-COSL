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
    // Toolstring management
    Route::post('/api/toolstring-categories', [ToolstringController::class, 'storeCategory'])->name('storeCategory');
    Route::get('/api/toolstring-categories', [ToolstringController::class, 'getCategories'])->name('getCategories');
    Route::put('/api/toolstring-categories/{id}', [ToolstringController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/api/toolstring-categories/{id}', [ToolstringController::class, 'deleteCategory'])->name('deleteCategory');
    Route::get('/api/toolstring-categories/{id}', [ToolstringController::class, 'getCategory'])->name('getCategory');

    // Toolstring items management
    Route::post('/api/toolstring-items', [ToolstringController::class, 'storeItem'])->name('storeItem');
    Route::get('/api/toolstring-items', [ToolstringController::class, 'getItems'])->name('getItems');
    Route::put('/api/toolstring-items/{id}', [ToolstringController::class, 'updateItem'])->name('updateItem');
    Route::delete('/api/toolstring-items', [ToolstringController::class, 'deleteItem'])->name('deleteItem');

    // User management
    Route::get('/api/current-user', [AuthController::class, 'currentUser'])->name('currentUser');
    Route::post('/api/logout', [AuthController::class, 'logout'])->name('logout');
});

// Storage files should not be routed to SPA
Route::get('/image/{filename}', function ($filename) {
    $path = base_path('storage/app/private/assets/images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});


// Catch-all for frontend SPA (protected)
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->middleware([AuthMiddleware::class]);

