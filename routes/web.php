<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes - University On-Site Voting System
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Unified Admin Dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.index');
    Route::get('/admin/feed', [AdminController::class, 'activityFeed'])->name('admin.feed');
    Route::get('/admin/ballot-preview', [AdminController::class, 'ballotPreview'])->name('admin.ballot.preview');

    // Super Admin Protected Routes
    Route::middleware(['role:super_admin'])->prefix('admin/manage')->name('admin.manage.')->group(function () {
        Route::get('/categories', [SuperAdminController::class, 'categories'])->name('categories');
        Route::post('/categories', [SuperAdminController::class, 'storeCategory'])->name('categories.store');
        
        Route::get('/candidates', [SuperAdminController::class, 'candidates'])->name('candidates');
        Route::post('/candidates', [SuperAdminController::class, 'storeCandidate'])->name('candidates.store');
        
        Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
        Route::post('/users', [SuperAdminController::class, 'storeUser'])->name('users.store');

        Route::get('/audit-logs', [SuperAdminController::class, 'auditLogs'])->name('audit');
        Route::get('/settings', [SuperAdminController::class, 'settings'])->name('settings');
        Route::post('/settings', [SuperAdminController::class, 'updateSettings'])->name('settings.update');
    });
});

// PUBLIC LIVE RESULTS (Projector/TV)
Route::get('/live-results', function() {
    $categories = \App\Models\Category::where('is_active', true)->withCount('votes')->get();
    return view('public.live-results', compact('categories'));
})->name('public.results');

// VOTER FLOW (On-Site Kiosk)
// No role required for physical PC, but supervisor must be logged in or PC must be authorized.
// For this system, we'll assume the browser is open at the station.
Route::prefix('voter')->name('voter.')->group(function () {
    Route::get('/entry', [VoterController::class, 'showEntry'])->name('entry');
    Route::post('/entry', [VoterController::class, 'processEntry'])->middleware(['voter.check'])->name('process');
    
    // Ballot protection
    Route::middleware(['prevent.back'])->group(function () {
        Route::get('/ballot', [VoterController::class, 'showBallot'])->name('ballot');
        Route::post('/vote', [VoterController::class, 'submitVote'])->name('submit');
        Route::get('/success', [VoterController::class, 'showSuccess'])->name('success');
    });
});
