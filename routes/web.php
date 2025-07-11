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
Route::get('/api/current-user', [AuthController::class, 'currentUser'])->name('currentUser');

// API routes that need auth
Route::middleware([AuthMiddleware::class])->group(function () {
    // Toolstring management
    Route::post('/api/toolstring-categories', [ToolstringController::class, 'storeCategory'])->name('storeCategory');
    Route::get('/api/toolstring-categories', [ToolstringController::class, 'getCategories'])->name('getCategories');
    Route::put('/api/toolstring-categories/{id}', [ToolstringController::class, 'updateCategory'])->name('updateCategory');
    Route::delete('/api/toolstring-categories/{id}', [ToolstringController::class, 'deleteCategory'])->name('deleteCategory');
    Route::get('/api/toolstring-categories/{id}', [ToolstringController::class, 'getCategory'])->name('getCategory');
    Route::get('/api/toolstring-categories-search', [ToolstringController::class, 'searchCategories'])->name('searchCategories');

    // Toolstring items management
    Route::post('/api/toolstring-items', [ToolstringController::class, 'storeItem'])->name('storeItem');
    Route::get('/api/toolstring-items', [ToolstringController::class, 'getItems'])->name('getItems');
    Route::put('/api/toolstring-items/{id}', [ToolstringController::class, 'updateItem'])->name('updateItem');
    Route::delete('/api/toolstring-items', [ToolstringController::class, 'deleteItem'])->name('deleteItem');
    Route::get('/api/toolstring-items-search', [ToolstringController::class, 'searchItemByIdCategory'])->name('searchItemByIdCategory');
    Route::get('/api/toolstring-item-dimensions/{itemId}', [ToolstringController::class, 'getItemDimensions'])->name('getItemDimensions');

    // User management
    Route::post('/api/logout', [AuthController::class, 'logout'])->name('logout');

    // Toolstring reporting history
    Route::post('/api/toolstring-reporting-histories', [ToolstringController::class, 'storeReportingHistory'])->name('storeReportingHistory');
    Route::get('/api/toolstring-reporting-histories', [ToolstringController::class, 'getReportingHistories'])->name('getReportingHistories');
    Route::get('/api/toolstring-reporting-histories/{id}', [ToolstringController::class, 'getReportingHistory'])->name('getReportingHistory');
    Route::put('/api/toolstring-reporting-histories/{id}', [ToolstringController::class, 'updateReportingHistory'])->name('updateReportingHistory');
    Route::delete('/api/toolstring-reporting-histories/{id}', [ToolstringController::class, 'deleteReportingHistory'])->name('deleteReportingHistory');

    // Toolstring reporting history details
    Route::post('/api/toolstring-reporting-history-details', [ToolstringController::class, 'storeReportingHistoryDetail'])->name('storeReportingHistoryDetail');
    Route::get('/api/toolstring-reporting-history-details/{templateId}', [ToolstringController::class, 'getReportingHistoryDetails'])->name('getReportingHistoryDetails');
    Route::get('/api/toolstring-reporting-history-details/{id}', [ToolstringController::class, 'getReportingHistoryDetail'])->name('getReportingHistoryDetail');
    Route::put('/api/toolstring-reporting-history-details/{id}', [ToolstringController::class, 'updateReportingHistoryDetail'])->name('updateReportingHistoryDetail');
    Route::delete('/api/toolstring-reporting-history-details', [ToolstringController::class, 'deleteReportingHistoryDetail'])->name('deleteReportingHistoryDetail');
    
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

