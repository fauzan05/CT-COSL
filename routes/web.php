<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ToolstringController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WellstackController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Artisan;
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
    Route::post('/api/users', [UserController::class, 'storeUser'])->name('storeUser');
    Route::post('/api/users/{userId}/update-download-permission', [UserController::class, 'updateDownloadPermission'])->name('updateDownloadPermission');
    Route::put('/api/users/{id}', [UserController::class, 'updateUser'])->name('updateUser');

    // Toolstring reporting history
    Route::post('/api/toolstring-reporting-histories', [ToolstringController::class, 'storeReportingHistory'])->name('storeReportingHistory');
    Route::get('/api/toolstring-reporting-histories', [ToolstringController::class, 'getReportingHistories'])->name('getReportingHistories');
    Route::get('/api/toolstring-reporting-histories/{id}', [ToolstringController::class, 'getReportingHistory'])->name('getReportingHistory');
    Route::put('/api/toolstring-reporting-histories/{id}', [ToolstringController::class, 'updateReportingHistory'])->name('updateReportingHistory');
    Route::delete('/api/toolstring-reporting-histories', [ToolstringController::class, 'deleteReportingHistory'])->name('deleteReportingHistory');

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
        Route::get('/wellstack-reporting-histories/export-pdf/{templateId}', [WellstackController::class, 'exportReportingHistoryPdf'])->name('exportWellstackReportingHistoryPdf');
    });
    
    // Thread management
    Route::post('/api/threads', [ThreadController::class, 'storeThread'])->name('storeThread');
    Route::get('/api/threads/{id}/sizes', [ThreadController::class, 'getThreadSizesById'])->name('searchThreadById');
    Route::get('/api/threads/no-paginate', [ThreadController::class, 'getThreadsNoPaginate'])->name('getThreadsNoPaginate');
    Route::get('/api/threads', [ThreadController::class, 'getThreads'])->name('getThreads');
    Route::put('/api/threads/{id}', [ThreadController::class, 'updateThread'])->name('updateThread');
    Route::delete('/api/threads', [ThreadController::class, 'deleteThread'])->name('deleteThread');
    Route::get('/api/threads/{id}', [ThreadController::class, 'getThread'])->name('getThread');

    // Wellstack type management
    Route::post('/api/wellstack-types', [WellstackController::class, 'storeType'])->name('storeWellstackType');
    Route::get('/api/wellstack-types', [WellstackController::class, 'getTypes'])->name('getWellstackTypes');
    Route::put('/api/wellstack-types/{id}', [WellstackController::class, 'updateType'])->name('updateWellstackType');
    Route::delete('/api/wellstack-types/{id}', [WellstackController::class, 'deleteType'])->name('deleteWellstackType');
    Route::get('/api/wellstack-types/{id}', [WellstackController::class, 'getType'])->name('getWellstackType');
    Route::get('/api/wellstack-types-search', [WellstackController::class, 'searchTypes'])->name('searchWellstackTypes');

    // Wellstack item management
    Route::post('/api/wellstack-items', [WellstackController::class, 'storeItem'])->name('storeWellstackItem');
    Route::get('/api/wellstack-items', [WellstackController::class, 'getItems'])->name('getWellstackItems');
    Route::put('/api/wellstack-items/{id}', [WellstackController::class, 'updateItem'])->name('updateWellstackItem');
    Route::delete('/api/wellstack-items', [WellstackController::class, 'deleteItem'])->name('deleteWellstackItem');
    Route::get('/api/wellstack-items/{id}', [WellstackController::class, 'getItem'])->name('getWellstackItem');
    Route::get('/api/wellstack-items-search', [WellstackController::class, 'searchItemByIdType'])->name('searchWellstackItemByIdType');

    // Wellstack reporting history
    Route::post('/api/wellstack-reporting-histories', [WellstackController::class, 'storeReportingHistory'])->name('storeWellstackReportingHistory');
    Route::get('/api/wellstack-reporting-histories', [WellstackController::class, 'getReportingHistories'])->name('getWellstackReportingHistories');
    Route::get('/api/wellstack-reporting-histories/{id}', [WellstackController::class, 'getReportingHistory'])->name('getWellstackReportingHistory');
    Route::put('/api/wellstack-reporting-histories/{id}', [WellstackController::class, 'updateReportingHistory'])->name('updateWellstackReportingHistory');
    Route::delete('/api/wellstack-reporting-histories', [WellstackController::class, 'deleteReportingHistory'])->name('deleteWellstackReportingHistory');

    // Wellstack reporting history details
    Route::post('/api/wellstack-reporting-history-details', [WellstackController::class, 'storeReportingHistoryDetail'])->name('storeWellstackReportingHistoryDetail');
    Route::put('/api/wellstack-reporting-history-details/update-positions', [WellstackController::class, 'updateReportingHistoryDetailPosition'])->name('updateWellstackReportingHistoryDetailPosition');
    Route::get('/api/wellstack-reporting-history-details/{templateId}', [WellstackController::class, 'getReportingHistoryDetails'])->name('getWellstackReportingHistoryDetails');
    Route::get('/api/wellstack-reporting-history-details/{id}', [WellstackController::class, 'getReportingHistoryDetail'])->name('getWellstackReportingHistoryDetail');
    Route::put('/api/wellstack-reporting-history-details/{id}', [WellstackController::class, 'updateReportingHistoryDetail'])->name('updateWellstackReportingHistoryDetail');
    Route::delete('/api/wellstack-reporting-history-details', [WellstackController::class, 'deleteReportingHistoryDetail'])->name('deleteWellstackReportingHistoryDetail');

    // Users
    Route::get('/api/users', [UserController::class, 'getUsers'])->name('getUsers');
    Route::get('/api/users/{id}', [UserController::class, 'getUser'])->name('getUser');
    Route::put('/api/users/{id}', [UserController::class, 'updateUser'])->name('updateUser');
    Route::delete('/api/users', [UserController::class, 'deleteUser'])->name('deleteUser');
    Route::post('/api/users', [UserController::class, 'storeUser'])->name('storeUser');

    Route::post('/api/check-username', [UserController::class, 'checkUsername'])->name('checkUsername');
    Route::post('/api/check-email', [UserController::class, 'checkEmail'])->name('checkEmail');
});

// Storage files routes - HARUS SEBELUM catch-all route
// Route untuk file gambar dengan struktur folder
Route::get('/storage/assets/images/{category}/{filename}', function ($category, $filename) {
    $path = storage_path('app/public/assets/images/' . $category . '/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('category', '.*')->where('filename', '.*');

// Route alternatif untuk backward compatibility
Route::get('/image/{filename}', function ($filename) {
    $path = storage_path('app/public/assets/images/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});

// Route untuk semua file storage lainnya jika diperlukan
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*');

// Catch-all for frontend SPA (protected) - HARUS DI AKHIR
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*')->middleware([AuthMiddleware::class]);