<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\JobTrackerController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ToolstringController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WellstackController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\CheckDocumentAccess;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function() {
    return view('auth');
})->name('auth-blank')->middleware(RedirectIfAuthenticated::class);
Route::get('/login', function () {
    return view('auth');
})->name('auth')->middleware(RedirectIfAuthenticated::class);
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
    Route::put('/api/toolstring-items-restore', [ToolstringController::class, 'restoreItem'])->name('restoreItem');
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
    Route::delete('/api/users', [UserController::class, 'deleteUser'])->name('deleteUser');

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
        // Job Tracker Export PDF
        Route::get('/job-tracker-form/export-pdf/{formId}', [JobTrackerController::class, 'exportJobTrackerPdf'])->name('exportJobTrackerPdf');
    });

    Route::get('/documents/{filename}', [DocumentController::class, 'show'])
            ->middleware([CheckDocumentAccess::class])
            ->where('filename', '.*');
    
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
    Route::put('/api/wellstack-items-restore', [WellstackController::class, 'restoreItem'])->name('restoreWellstackItem');
    Route::post('/api/wellstack-items', [WellstackController::class, 'storeItem'])->name('storeWellstackItem');
    Route::get('/api/wellstack-items', [WellstackController::class, 'getItems'])->name('getWellstackItems');
    Route::put('/api/wellstack-items/{id}', [WellstackController::class, 'updateItem'])->name('updateWellstackItem');
    Route::delete('/api/wellstack-items', [WellstackController::class, 'deleteItem'])->name('deleteWellstackItem');
    Route::get('/api/wellstack-items/{id}', [WellstackController::class, 'getItem'])->name('getWellstackItem');
    Route::get('/api/wellstack-items-search', [WellstackController::class, 'searchItemByIdType'])->name('searchWellstackItemByIdType');
    // restore wellstack item

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

    // Job Tackers
    Route::post('/api/job-trackers', [JobTrackerController::class, 'storeJobTracker'])->name('storeJobTracker');
    Route::get('/api/job-trackers', [JobTrackerController::class, 'getJobTrackers'])->name('getJobTrackers');
    Route::get('/api/job-trackers/{id}', [JobTrackerController::class, 'getJobTracker'])->name('getJobTracker');
    Route::put('/api/job-trackers/{id}', [JobTrackerController::class, 'updateJobTracker'])->name('updateJobTracker');
    Route::delete('/api/job-trackers', [JobTrackerController::class, 'deleteJobTracker'])->name('deleteJobTracker');

    // Job Tracker Job Description
    Route::get('/api/job-tracker-master/job-descriptions', [JobTrackerController::class, 'getJobDescriptions'])->name('getJobDescriptions');
    Route::post('/api/job-tracker-master/job-descriptions', [JobTrackerController::class, 'storeJobDescription'])->name('storeJobDescription');
    Route::put('/api/job-tracker-master/job-descriptions/{id}', [JobTrackerController::class, 'updateJobDescription'])->name('updateJobDescription');
    Route::delete('/api/job-tracker-master/job-descriptions/{id}', [JobTrackerController::class, 'deleteJobDescription'])->name('deleteJobDescription');

    // Job Tracker Customers
    Route::get('/api/job-tracker-master/customers', [JobTrackerController::class, 'getCustomers'])->name('getCustomers');
    Route::post('/api/job-tracker-master/customers', [JobTrackerController::class, 'storeCustomer'])->name('storeCustomer');
    Route::put('/api/job-tracker-master/customers/{id}', [JobTrackerController::class, 'updateCustomer'])->name('updateCustomer');
    Route::delete('/api/job-tracker-master/customers/{id}', [JobTrackerController::class, 'deleteCustomer'])->name('deleteCustomer');

    // Job Tracker BJ District
    Route::get('/api/job-tracker-master/cosl-bases', [JobTrackerController::class, 'getCOSLBases'])->name('getCOSLBases');
    Route::post('/api/job-tracker-master/cosl-bases', [JobTrackerController::class, 'storeCOSLBase'])->name('storeCOSLBase');
    Route::put('/api/job-tracker-master/cosl-bases/{id}', [JobTrackerController::class, 'updateCOSLBase'])->name('updateCOSLBase');
    Route::delete('/api/job-tracker-master/cosl-bases/{id}', [JobTrackerController::class, 'deleteCOSLBase'])->name('deleteCOSLBase');

    // Job Tracker Field Location
    Route::get('/api/job-tracker-master/field-locations', [JobTrackerController::class, 'getFieldLocations'])->name('getFieldLocations');
    Route::post('/api/job-tracker-master/field-locations', [JobTrackerController::class, 'storeFieldLocation'])->name('storeFieldLocation');
    Route::put('/api/job-tracker-master/field-locations/{id}', [JobTrackerController::class, 'updateFieldLocation'])->name('updateFieldLocation');
    Route::delete('/api/job-tracker-master/field-locations/{id}', [JobTrackerController::class, 'deleteFieldLocation'])->name('deleteFieldLocation');

    // Job Tracker Field Type
    Route::get('/api/job-tracker-master/field-types', [JobTrackerController::class, 'getFieldTypes'])->name('getFieldTypes');
    Route::post('/api/job-tracker-master/field-types', [JobTrackerController::class, 'storeFieldType'])->name('storeFieldType');
    Route::put('/api/job-tracker-master/field-types/{id}', [JobTrackerController::class, 'updateFieldType'])->name('updateFieldType');
    Route::delete('/api/job-tracker-master/field-types/{id}', [JobTrackerController::class, 'deleteFieldType'])->name('deleteFieldType');

    // Job Tracker Well Status
    Route::get('/api/job-tracker-master/well-statuses', [JobTrackerController::class, 'getWellStatuses'])->name('getWellStatuses');
    Route::post('/api/job-tracker-master/well-statuses', [JobTrackerController::class, 'storeWellStatus'])->name('storeWellStatus');
    Route::put('/api/job-tracker-master/well-statuses/{id}', [JobTrackerController::class, 'updateWellStatus'])->name('updateWellStatus');
    Route::delete('/api/job-tracker-master/well-statuses/{id}', [JobTrackerController::class, 'deleteWellStatus'])->name('deleteWellStatus');

    // Job Tracker Well Type
    Route::get('/api/job-tracker-master/well-types', [JobTrackerController::class, 'getWellTypes'])->name('getWellTypes');
    Route::post('/api/job-tracker-master/well-types', [JobTrackerController::class, 'storeWellType'])->name('storeWellType');
    Route::put('/api/job-tracker-master/well-types/{id}', [JobTrackerController::class, 'updateWellType'])->name('updateWellType');
    Route::delete('/api/job-tracker-master/well-types/{id}', [JobTrackerController::class, 'deleteWellType'])->name('deleteWellType');

    // Job Tracker Wellhead X-Over
    Route::get('/api/job-tracker-master/wellhead-x-overs', [JobTrackerController::class, 'getWellheadXOvers'])->name('getWellheadXOvers');
    Route::post('/api/job-tracker-master/wellhead-x-overs', [JobTrackerController::class, 'storeWellheadXOver'])->name('storeWellheadXOver');
    Route::put('/api/job-tracker-master/wellhead-x-overs/{id}', [JobTrackerController::class, 'updateWellheadXOver'])->name('updateWellheadXOver');
    Route::delete('/api/job-tracker-master/wellhead-x-overs/{id}', [JobTrackerController::class, 'deleteWellheadXOver'])->name('deleteWellheadXOver');

    // Job Tracker Casing Liner Size
    Route::get('/api/job-tracker-master/casing-liner-sizes', [JobTrackerController::class, 'getCasingLinerSizes'])->name('getCasingLinerSizes');
    Route::post('/api/job-tracker-master/casing-liner-sizes', [JobTrackerController::class, 'storeCasingLinerSize'])->name('storeCasingLinerSize');
    Route::put('/api/job-tracker-master/casing-liner-sizes/{id}', [JobTrackerController::class, 'updateCasingLinerSize'])->name('updateCasingLinerSize');
    Route::delete('/api/job-tracker-master/casing-liner-sizes/{id}', [JobTrackerController::class, 'deleteCasingLinerSize'])->name('deleteCasingLinerSize');

    // Job Tracker Completion Size
    Route::get('/api/job-tracker-master/completion-sizes', [JobTrackerController::class, 'getCompletionSizes'])->name('getCompletionSizes');
    Route::post('/api/job-tracker-master/completion-sizes', [JobTrackerController::class, 'storeCompletionSize'])->name('storeCompletionSize');
    Route::put('/api/job-tracker-master/completion-sizes/{id}', [JobTrackerController::class, 'updateCompletionSize'])->name('updateCompletionSize');
    Route::delete('/api/job-tracker-master/completion-sizes/{id}', [JobTrackerController::class, 'deleteCompletionSize'])->name('deleteCompletionSize');

    // Job Tracker Nozzle Type
    Route::get('/api/job-tracker-master/nozzle-types', [JobTrackerController::class, 'getNozzleTypes'])->name('getNozzleTypes');
    Route::post('/api/job-tracker-master/nozzle-types', [JobTrackerController::class, 'storeNozzleType'])->name('storeNozzleType');
    Route::put('/api/job-tracker-master/nozzle-types/{id}', [JobTrackerController::class, 'updateNozzleType'])->name('updateNozzleType');
    Route::delete('/api/job-tracker-master/nozzle-types/{id}', [JobTrackerController::class, 'deleteNozzleType'])->name('deleteNozzleType');

    // Job Tracker Max BHA OD
    Route::get('/api/job-tracker-master/max-bha-ods', [JobTrackerController::class, 'getMaxBHAODs'])->name('getMaxBHAODs');
    Route::post('/api/job-tracker-master/max-bha-ods', [JobTrackerController::class, 'storeMaxBHAOD'])->name('storeMaxBHAOD');
    Route::put('/api/job-tracker-master/max-bha-ods/{id}', [JobTrackerController::class, 'updateMaxBHAOD'])->name('updateMaxBHAOD');
    Route::delete('/api/job-tracker-master/max-bha-ods/{id}', [JobTrackerController::class, 'deleteMaxBHAOD'])->name('deleteMaxBHAOD');

    // Job Tracker Control Cabin
    Route::get('/api/job-tracker-master/control-cabins', [JobTrackerController::class, 'getControlCabins'])->name('getControlCabins');
    Route::post('/api/job-tracker-master/control-cabins', [JobTrackerController::class, 'storeControlCabin'])->name('storeControlCabin');
    Route::put('/api/job-tracker-master/control-cabins/{id}', [JobTrackerController::class, 'updateControlCabin'])->name('updateControlCabin');
    Route::delete('/api/job-tracker-master/control-cabins/{id}', [JobTrackerController::class, 'deleteControlCabin'])->name('deleteControlCabin');

    // Job Tracker Power Pack
    Route::get('/api/job-tracker-master/power-packs', [JobTrackerController::class, 'getPowerPacks'])->name('getPowerPacks');
    Route::post('/api/job-tracker-master/power-packs', [JobTrackerController::class, 'storePowerPack'])->name('storePowerPack');
    Route::put('/api/job-tracker-master/power-packs/{id}', [JobTrackerController::class, 'updatePowerPack'])->name('updatePowerPack');
    Route::delete('/api/job-tracker-master/power-packs/{id}', [JobTrackerController::class, 'deletePowerPack'])->name('deletePowerPack');

    // Job Tracker Power Reel
    Route::get('/api/job-tracker-master/power-reels', [JobTrackerController::class, 'getPowerReels'])->name('getPowerReels');
    Route::post('/api/job-tracker-master/power-reels', [JobTrackerController::class, 'storePowerReel'])->name('storePowerReel');
    Route::put('/api/job-tracker-master/power-reels/{id}', [JobTrackerController::class, 'updatePowerReel'])->name('updatePowerReel');
    Route::delete('/api/job-tracker-master/power-reels/{id}', [JobTrackerController::class, 'deletePowerReel'])->name('deletePowerReel');

    // Job Tracker CJ Injector
    Route::get('/api/job-tracker-master/cj-injectors', [JobTrackerController::class, 'getCJInjectors'])->name('getCJInjectors');
    Route::post('/api/job-tracker-master/cj-injectors', [JobTrackerController::class, 'storeCJInjector'])->name('storeCJInjector');
    Route::put('/api/job-tracker-master/cj-injectors/{id}', [JobTrackerController::class, 'updateCJInjector'])->name('updateCJInjector');
    Route::delete('/api/job-tracker-master/cj-injectors/{id}', [JobTrackerController::class, 'deleteCJInjector'])->name('deleteCJInjector');

    // Job Tracker BOP
    Route::get('/api/job-tracker-master/bops', [JobTrackerController::class, 'getBOPs'])->name('getBOPs');
    Route::post('/api/job-tracker-master/bops', [JobTrackerController::class, 'storeBOP'])->name('storeBOP');
    Route::put('/api/job-tracker-master/bops/{id}', [JobTrackerController::class, 'updateBOP'])->name('updateBOP');
    Route::delete('/api/job-tracker-master/bops/{id}', [JobTrackerController::class, 'deleteBOP'])->name('deleteBOP');

    // Job Tracker CT Size
    Route::get('/api/job-tracker-master/ct-sizes', [JobTrackerController::class, 'getCTSizes'])->name('getCTSizes');
    Route::post('/api/job-tracker-master/ct-sizes', [JobTrackerController::class, 'storeCTSize'])->name('storeCTSize');
    Route::put('/api/job-tracker-master/ct-sizes/{id}', [JobTrackerController::class, 'updateCTSize'])->name('updateCTSize');
    Route::delete('/api/job-tracker-master/ct-sizes/{id}', [JobTrackerController::class, 'deleteCTSize'])->name('deleteCTSize');

    // Job Tracker CT Grade
    Route::get('/api/job-tracker-master/ct-grades', [JobTrackerController::class, 'getCTGrades'])->name('getCTGrades');
    Route::post('/api/job-tracker-master/ct-grades', [JobTrackerController::class, 'storeCTGrade'])->name('storeCTGrade');
    Route::put('/api/job-tracker-master/ct-grades/{id}', [JobTrackerController::class, 'updateCTGrade'])->name('updateCTGrade');
    Route::delete('/api/job-tracker-master/ct-grades/{id}', [JobTrackerController::class, 'deleteCTGrade'])->name('deleteCTGrade');

    // Job Tracker WT
    Route::get('/api/job-tracker-master/wts', [JobTrackerController::class, 'getWTs'])->name('getWTs');
    Route::post('/api/job-tracker-master/wts', [JobTrackerController::class, 'storeWT'])->name('storeWT');
    Route::put('/api/job-tracker-master/wts/{id}', [JobTrackerController::class, 'updateWT'])->name('updateWT');
    Route::delete('/api/job-tracker-master/wts/{id}', [JobTrackerController::class, 'deleteWT'])->name('deleteWT');

    // Job Tracker CT String
    Route::get('/api/job-tracker-master/ct-strings', [JobTrackerController::class, 'getCTStrings'])->name('getCTStrings');
    Route::post('/api/job-tracker-master/ct-strings', [JobTrackerController::class, 'storeCTString'])->name('storeCTString');
    Route::put('/api/job-tracker-master/ct-strings/{id}', [JobTrackerController::class, 'updateCTString'])->name('updateCTString');
    Route::delete('/api/job-tracker-master/ct-strings/{id}', [JobTrackerController::class, 'deleteCTString'])->name('deleteCTString');

    // Job Tracker N2 Converter
    Route::get('/api/job-tracker-master/n2-converters', [JobTrackerController::class, 'getN2Converters'])->name('getN2Converters');
    Route::post('/api/job-tracker-master/n2-converters', [JobTrackerController::class, 'storeN2Converter'])->name('storeN2Converter');
    Route::put('/api/job-tracker-master/n2-converters/{id}', [JobTrackerController::class, 'updateN2Converter'])->name('updateN2Converter');
    Route::delete('/api/job-tracker-master/n2-converters/{id}', [JobTrackerController::class, 'deleteN2Converter'])->name('deleteN2Converter');

    // Jo Tracker N2 Tank
    Route::get('/api/job-tracker-master/n2-tanks', [JobTrackerController::class, 'getN2Tanks'])->name('getN2Tanks');
    Route::post('/api/job-tracker-master/n2-tanks', [JobTrackerController::class, 'storeN2Tank'])->name('storeN2Tank');
    Route::put('/api/job-tracker-master/n2-tanks/{id}', [JobTrackerController::class, 'updateN2Tank'])->name('updateN2Tank');
    Route::delete('/api/job-tracker-master/n2-tanks/{id}', [JobTrackerController::class, 'deleteN2Tank'])->name('deleteN2Tank');

    // Job Tracker Container
    Route::get('/api/job-tracker-master/containers', [JobTrackerController::class, 'getContainers'])->name('getContainers');
    Route::post('/api/job-tracker-master/containers', [JobTrackerController::class, 'storeContainer'])->name('storeContainer');
    Route::put('/api/job-tracker-master/containers/{id}', [JobTrackerController::class, 'updateContainer'])->name('updateContainer');
    Route::delete('/api/job-tracker-master/containers/{id}', [JobTrackerController::class, 'deleteContainer'])->name('deleteContainer');

    // Job Tracker Injector Goosneck
    Route::get('/api/job-tracker-master/injector-goosnecks', [JobTrackerController::class, 'getInjectorGoosnecks'])->name('getInjectorGoosnecks');
    Route::post('/api/job-tracker-master/injector-goosnecks', [JobTrackerController::class, 'storeInjectorGoosneck'])->name('storeInjectorGoosneck');
    Route::put('/api/job-tracker-master/injector-goosnecks/{id}', [JobTrackerController::class, 'updateInjectorGoosneck'])->name('updateInjectorGoosneck');
    Route::delete('/api/job-tracker-master/injector-goosnecks/{id}', [JobTrackerController::class, 'deleteInjectorGoosneck'])->name('deleteInjectorGoosneck');

    // Job Tracker Miscellaneous Tool
    Route::get('/api/job-tracker-master/miscellaneous-tools', [JobTrackerController::class, 'getMiscellaneousTools'])->name('getMiscellaneousTools');
    Route::post('/api/job-tracker-master/miscellaneous-tools', [JobTrackerController::class, 'storeMiscellaneousTool'])->name('storeMiscellaneousTool');
    Route::put('/api/job-tracker-master/miscellaneous-tools/{id}', [JobTrackerController::class, 'updateMiscellaneousTool'])->name('updateMiscellaneousTool');
    Route::delete('/api/job-tracker-master/miscellaneous-tools/{id}', [JobTrackerController::class, 'deleteMiscellaneousTool'])->name('deleteMiscellaneousTool');

    // Job Tracker CT Supervisor
    Route::get('/api/job-tracker-master/ct-supervisors', [JobTrackerController::class, 'getCTSupervisors'])->name('getCTSupervisors');
    Route::post('/api/job-tracker-master/ct-supervisors', [JobTrackerController::class, 'storeCTSupervisor'])->name('storeCTSupervisor');
    Route::put('/api/job-tracker-master/ct-supervisors/{id}', [JobTrackerController::class, 'updateCTSupervisor'])->name('updateCTSupervisor');
    Route::delete('/api/job-tracker-master/ct-supervisors/{id}', [JobTrackerController::class, 'deleteCTSupervisor'])->name('deleteCTSupervisor');

    // Job Tracker CT Personnel
    Route::get('/api/job-tracker-master/ct-personnels', [JobTrackerController::class, 'getCTPersonnels'])->name('getCTPersonnels');
    Route::post('/api/job-tracker-master/ct-personnels', [JobTrackerController::class, 'storeCTPersonnel'])->name('storeCTPersonnel');
    Route::put('/api/job-tracker-master/ct-personnels/{id}', [JobTrackerController::class, 'updateCTPersonnel'])->name('updateCTPersonnel');
    Route::delete('/api/job-tracker-master/ct-personnels/{id}', [JobTrackerController::class, 'deleteCTPersonnel'])->name('deleteCTPersonnel');

    // Job Tracker Nitrogen Supervisor
    Route::get('/api/job-tracker-master/nitrogen-supervisors', [JobTrackerController::class, 'getNitrogenSupervisors'])->name('getNitrogenSupervisors');
    Route::post('/api/job-tracker-master/nitrogen-supervisors', [JobTrackerController::class, 'storeNitrogenSupervisor'])->name('storeNitrogenSupervisor');
    Route::put('/api/job-tracker-master/nitrogen-supervisors/{id}', [JobTrackerController::class, 'updateNitrogenSupervisor'])->name('updateNitrogenSupervisor');
    Route::delete('/api/job-tracker-master/nitrogen-supervisors/{id}', [JobTrackerController::class, 'deleteNitrogenSupervisor'])->name('deleteNitrogenSupervisor');

    // Job Tracker Nitrogen Personnel
    Route::get('/api/job-tracker-master/nitrogen-personnels', [JobTrackerController::class, 'getNitrogenPersonnels'])->name('getNitrogenPersonnels');
    Route::post('/api/job-tracker-master/nitrogen-personnels', [JobTrackerController::class, 'storeNitrogenPersonnel'])->name('storeNitrogenPersonnel');
    Route::put('/api/job-tracker-master/nitrogen-personnels/{id}', [JobTrackerController::class, 'updateNitrogenPersonnel'])->name('updateNitrogenPersonnel');
    Route::delete('/api/job-tracker-master/nitrogen-personnels/{id}', [JobTrackerController::class, 'deleteNitrogenPersonnel'])->name('deleteNitrogenPersonnel');

    // Documents
    Route::get('/api/documents', [DocumentController::class, 'getDocuments'])->name('getDocuments');
    Route::get('/api/documents/{id}', [DocumentController::class, 'getDocument'])->name('getDocument');
    Route::post('/api/documents', [DocumentController::class, 'storeDocument'])->name('storeDocument');
    Route::put('/api/documents/{id}', [DocumentController::class, 'updateDocument'])->name('updateDocument');
    Route::delete('/api/documents', [DocumentController::class, 'deleteDocument'])->name('deleteDocument');
    
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