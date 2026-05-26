<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Middleware\DocumentAccessMiddleware;

Route::middleware(['auth'])->prefix('documents')->name('documents.')->group(function () {
    
    Route::post('/upload', [DocumentController::class, 'upload'])->name('upload');
    
    Route::get('/', [DocumentController::class, 'index'])->name('index');
    
    Route::middleware([DocumentAccessMiddleware::class])->group(function () {
        Route::get('/{document}/view', [DocumentController::class, 'view'])->name('view');
        Route::get('/{document}/download', [DocumentController::class, 'download'])->name('download');
        Route::delete('/{document}', [DocumentController::class, 'delete'])->name('delete');
        Route::post('/{document}/share', [DocumentController::class, 'share'])->name('share');
        Route::get('/{document}/logs', [DocumentController::class, 'logs'])->name('logs');
    });
});