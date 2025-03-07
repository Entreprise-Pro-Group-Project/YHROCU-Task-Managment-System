<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetLinkController::class, 'edit'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetLinkController::class, 'update'])
        ->name('password.update');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management routes
        Route::prefix('users')->name('user_management.')->group(function () {
            Route::get('/', [UserController::class, 'indexView'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

    // Supervisor routes
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');

    // Staff routes
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');

    // Projects Routes
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/store-data', [ProjectController::class, 'storeProjectData'])->name('store-data');
        Route::post('/', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
    });

    // Tasks Routes
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    });

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update-profile-information-form'])->name('update-profile-information-form');
    });
});

require __DIR__.'/auth.php';

