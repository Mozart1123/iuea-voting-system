<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicCategoryController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All API endpoints for the voting system
*/

// Public endpoints (no auth required)
Route::prefix('categories')->group(function () {
    Route::get('/', [PublicCategoryController::class, 'index'])->name('categories.index');
    Route::get('{category}', [PublicCategoryController::class, 'show'])->name('categories.show');
});

// Authenticated user endpoints (requires Sanctum auth)
Route::middleware('auth:sanctum')->group(function () {
    
    // User profile
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });

    // Applications - Student endpoints
    Route::prefix('applications')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('applications.index');
        Route::post('/', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('check', [ApplicationController::class, 'checkApplication'])->name('applications.check');
        Route::get('stats', [ApplicationController::class, 'stats'])->name('applications.stats');
        Route::get('{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::delete('{application}', [ApplicationController::class, 'destroy'])->name('applications.destroy');
    });

    // Voting endpoints
    Route::prefix('votes')->group(function () {
        Route::post('/', [VoteController::class, 'store'])->name('votes.store');
        Route::get('history', [VoteController::class, 'history'])->name('votes.history');
    });

    // Results - available to authenticated users
    Route::get('categories/{category}/results', [VoteController::class, 'results'])->name('categories.results');

});

// Admin endpoints (requires Sanctum auth + admin middleware)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    
    // Admin categories management
    Route::prefix('categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('{category}', [AdminCategoryController::class, 'show'])->name('admin.categories.show');
        Route::put('{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
        Route::post('{category}/toggle-active', [AdminCategoryController::class, 'toggleActive'])->name('admin.categories.toggle');
    });

    // Admin applications management
    Route::prefix('applications')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'index'])->name('admin.applications.index');
        Route::get('statistics', [AdminApplicationController::class, 'statistics'])->name('admin.applications.stats');
        Route::get('{application}', [AdminApplicationController::class, 'show'])->name('admin.applications.show');
        Route::post('{application}/approve', [AdminApplicationController::class, 'approve'])->name('admin.applications.approve');
        Route::post('{application}/reject', [AdminApplicationController::class, 'reject'])->name('admin.applications.reject');
        Route::post('{application}/register', [AdminApplicationController::class, 'register'])->name('admin.applications.register');
        Route::delete('{application}', [AdminApplicationController::class, 'destroy'])->name('admin.applications.destroy');
    });
    // Export endpoints
    Route::prefix('export')->group(function () {
        Route::get('categories/{category}/results', [ExportController::class, 'resultsCSV'])->name('export.results');
        Route::get('applications', [ExportController::class, 'applicationsCSV'])->name('export.applications');
        Route::get('statistics', [ExportController::class, 'statisticsCSV'])->name('export.statistics');
    });


});
