<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicCategoryController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public routes - no authentication required
Route::get('/categories', [PublicCategoryController::class, 'index']);
Route::get('/categories/{category}', [PublicCategoryController::class, 'show']);

// Protected routes - authentication required
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    });

    // Student applications routes
    Route::prefix('/applications')->group(function () {
        Route::get('/', [ApplicationController::class, 'index']);
        Route::post('/', [ApplicationController::class, 'store']);
        Route::get('/{application}', [ApplicationController::class, 'show']);
        Route::delete('/{application}', [ApplicationController::class, 'destroy']);
        Route::post('/check', [ApplicationController::class, 'checkApplication']);
        Route::get('/stats', [ApplicationController::class, 'stats']);
    });

    // Alias for backwards compatibility
    Route::get('/my-applications', [ApplicationController::class, 'index']);
});

// Admin routes - admin authentication required
Route::middleware(['auth:sanctum', 'admin'])->prefix('/admin')->group(function () {
    
    // Admin category management
    Route::prefix('/categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index']);
        Route::post('/', [AdminCategoryController::class, 'store']);
        Route::get('/{category}', [AdminCategoryController::class, 'show']);
        Route::put('/{category}', [AdminCategoryController::class, 'update']);
        Route::patch('/{category}', [AdminCategoryController::class, 'update']);
        Route::delete('/{category}', [AdminCategoryController::class, 'destroy']);
        Route::patch('/{category}/toggle-active', [AdminCategoryController::class, 'toggleActive']);
    });

    // Admin application management
    Route::prefix('/applications')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'index']);
        Route::get('/statistics', [AdminApplicationController::class, 'statistics']);
        Route::get('/{application}', [AdminApplicationController::class, 'show']);
        Route::patch('/{application}/approve', [AdminApplicationController::class, 'approve']);
        Route::patch('/{application}/reject', [AdminApplicationController::class, 'reject']);
        Route::patch('/{application}/register', [AdminApplicationController::class, 'register']);
        Route::delete('/{application}', [AdminApplicationController::class, 'destroy']);
    });
});

// Example endpoint to test authentication
Route::get('/test-auth', function (Request $request) {
    if ($request->user()) {
        return response()->json([
            'success' => true,
            'message' => 'You are authenticated',
            'user' => $request->user(),
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Unauthenticated',
    ], 401);
})->middleware('auth:sanctum');
