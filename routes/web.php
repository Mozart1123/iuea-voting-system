<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/verify-otp', [App\Http\Controllers\OtpController::class, 'show'])->name('otp.verify');
    Route::post('/verify-otp', [App\Http\Controllers\OtpController::class, 'verify']);
    Route::post('/resend-otp', [App\Http\Controllers\OtpController::class, 'resend'])->name('otp.resend');

    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Student Dashboard
    Route::prefix('dashboard')->name('dashboard')->group(function () {
        Route::get('/', [StudentController::class, 'index'])->name('.index');
        Route::get('/elections', [StudentController::class, 'elections'])->name('.elections');
        Route::post('/vote', [StudentController::class, 'vote'])->name('.vote')->middleware('throttle:voting');
        Route::get('/receipts', [StudentController::class, 'receipts'])->name('.receipts');

        Route::middleware('nomination.access')->group(function () {
            Route::get('/nomination', [StudentController::class, 'nomination'])->name('.nomination');
            Route::post('/nomination', [StudentController::class, 'submitNomination'])->name('.nomination.submit');
        });
    });

    // Admin Command Center (Protection handled in Controller)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        
        // Super Admin Specific Routes
        Route::prefix('super-admin')->name('super-admin.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdminController::class, 'index'])->name('index');
            Route::get('/candidates/{category}', [App\Http\Controllers\SuperAdminController::class, 'getCandidates'])->name('candidates');
            Route::post('/adjust-votes', [App\Http\Controllers\SuperAdminController::class, 'adjustVotes'])->name('adjust-votes');
            
            // Admin Management
            Route::post('/admins', [App\Http\Controllers\SuperAdminController::class, 'storeAdmin'])->name('admins.store');
            Route::post('/admins/{user}', [App\Http\Controllers\SuperAdminController::class, 'updateAdmin'])->name('admins.update');
            Route::delete('/admins/{user}', [App\Http\Controllers\SuperAdminController::class, 'deleteAdmin'])->name('admins.delete');
        });

        Route::get('/elections', [AdminController::class, 'elections'])->name('elections');
        Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates');
        Route::get('/voters', [AdminController::class, 'voters'])->name('voters');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
        Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('candidates.store');
        Route::post('/categories/{category}/status', [AdminController::class, 'updateCategoryStatus'])->name('categories.status');
        Route::post('/candidates/{candidate}/status', [AdminController::class, 'updateCandidateStatus'])->name('candidates.status');
        Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');

        // Notifications
        Route::get('/notifications', [AdminController::class, 'getNotifications'])->name('notifications.index');
        Route::post('/notifications/read', [AdminController::class, 'markNotificationsRead'])->name('notifications.read');
    });
Route::get('/api/live-stats', [App\Http\Controllers\ApiController::class, 'liveStats'])->name('api.live-stats');

});
