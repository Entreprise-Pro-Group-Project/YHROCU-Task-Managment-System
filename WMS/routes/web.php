<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Password reset routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetLinkController::class, 'edit'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetLinkController::class, 'update'])
    ->name('password.update');

// Protected routes that require authentication
Route::middleware('auth')->group(function () {
    // Admin dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Supervisor dashboard
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    
    // Staff dashboard
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    
    // User management routes
    Route::get('/users', [UserController::class, 'indexView'])->name('admin.user_management.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.user_management.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.user_management.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.user_management.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.user_management.destroy');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.user_management.reset-password');
    
    // Projects Routes
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects/store-data', [ProjectController::class, 'storeProjectData'])->name('projects.store-data');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    
    // Tasks Routes
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    
    // Dashboard redirect
    Route::get('/dashboard-redirect', [DashboardController::class, 'redirect'])->name('dashboard.redirect');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update-profile-information-form'])->name('profile.update-profile-information-form');
});

require __DIR__.'/auth.php';