<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ThreadController;
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
    Route::post('/api/toolstring-types', [ToolstringController::class, 'storeType'])->name('storeType');
    Route::get('/api/toolstring-types', [ToolstringController::class, 'getTypes'])->name('getTypes');
    Route::put('/api/toolstring-types/{id}', [ToolstringController::class, 'updateType'])->name('updateType');
    Route::delete('/api/toolstring-types/{id}', [ToolstringController::class, 'deleteType'])->name('deleteType');
    Route::get('/api/toolstring-types/{id}', [ToolstringController::class, 'getType'])->name('getType');
    Route::get('/api/toolstring-types-search', [ToolstringController::class, 'searchTypes'])->name('searchTypes');

    // Toolstring items management
    Route::post('/api/toolstring-items', [ToolstringController::class, 'storeItem'])->name('storeItem');
    Route::get('/api/toolstring-items', [ToolstringController::class, 'getItems'])->name('getItems');
    Route::put('/api/toolstring-items/{id}', [ToolstringController::class, 'updateItem'])->name('updateItem');
    Route::delete('/api/toolstring-items', [ToolstringController::class, 'deleteItem'])->name('deleteItem');
    Route::get('/api/toolstring-items-search', [ToolstringController::class, 'searchItemByIdType'])->name('searchItemByIdType');
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
    Route::put('/api/toolstring-reporting-history-details/update-positions', [ToolstringController::class, 'updateReportingHistoryDetailPosition'])->name('updateReportingHistoryDetailPosition');
    Route::get('/api/toolstring-reporting-history-details/{templateId}', [ToolstringController::class, 'getReportingHistoryDetails'])->name('getReportingHistoryDetails');
    Route::get('/api/toolstring-reporting-history-details/{id}', [ToolstringController::class, 'getReportingHistoryDetail'])->name('getReportingHistoryDetail');
    Route::put('/api/toolstring-reporting-history-details/{id}', [ToolstringController::class, 'updateReportingHistoryDetail'])->name('updateReportingHistoryDetail');
    Route::delete('/api/toolstring-reporting-history-details', [ToolstringController::class, 'deleteReportingHistoryDetail'])->name('deleteReportingHistoryDetail');

    // Toolstring Export PDF
    Route::prefix('backend')->group(function () {
        Route::get('/toolstring-reporting-histories/export-pdf/{templateId}', [ToolstringController::class, 'exportReportingHistoryPdf'])->name('exportReportingHistoryPdf');
    });
    
    // Thread management
    Route::post('/api/threads', [ThreadController::class, 'storeThread'])->name('storeThread');
    Route::get('/api/threads/{id}/sizes', [ThreadController::class, 'getThreadSizesById'])->name('searchThreadById');
    Route::get('/api/threads/no-paginate', [ThreadController::class, 'getThreadsNoPaginate'])->name('getThreadsNoPaginate');
    Route::get('/api/threads', [ThreadController::class, 'getThreads'])->name('getThreads');
    Route::put('/api/threads/{id}', [ThreadController::class, 'updateThread'])->name('updateThread');
    Route::delete('/api/threads/{id}', [ThreadController::class, 'deleteThread'])->name('deleteThread');
    Route::get('/api/threads/{id}', [ThreadController::class, 'getThread'])->name('getThread');
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

