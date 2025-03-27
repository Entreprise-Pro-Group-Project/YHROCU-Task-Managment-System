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
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\EmailCheckController;



Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard.redirect') 
        : redirect()->route('login');
});



Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');


// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetLinkController::class, 'edit'])
    ->name('password.reset');
Route::post('/reset-password', [PasswordResetLinkController::class, 'update'])
    ->name('password.update');

// Public User Search Route (if applicable)
Route::get('/users/search', [UserController::class, 'search'])->name('admin.user_management.search');

// Protected routes that require authentication
Route::middleware('auth')->group(function () {

    // Admin-specific routes
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // User management for admin
        Route::get('/users', [UserController::class, 'indexView'])->name('admin.user_management.index');
        Route::post('/users', [UserController::class, 'store'])->name('admin.user_management.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.user_management.show');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.user_management.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.user_management.destroy');
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('admin.user_management.resetPassword');
        Route::get('/users/{id}/reset-password', [UserController::class, 'resetPasswordForm'])->name('admin.user_management.resetPasswordForm');
    });

    // Supervisor-specific routes
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':supervisor')->group(function () {
        Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    });

    // Staff-specific routes
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':staff')->group(function () {
        Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    });

    // Routes accessible to all authenticated users

    // Project Routes
    Route::get('projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('projects/store-data', [ProjectController::class, 'storeProjectData'])->name('projects.store-data');
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Task Routes
    Route::get('tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::put('/tasks/{task}/reassign', [TaskController::class, 'reassign'])->name('tasks.reassign');
    Route::put('/tasks/{task}/update-due-date', [TaskController::class, 'updateDueDate'])->name('tasks.update.due_date');

    // Task Comment Route
    Route::post('/tasks/{task}/comment', [TaskCommentController::class, 'store'])
        ->name('comment');

    // Dashboard Redirect
    Route::get('/dashboard-redirect', [DashboardController::class, 'redirect'])->name('dashboard.redirect');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update-profile-information-form'])->name('profile.update-profile-information-form');
});

// Include additional auth routes if needed
require __DIR__.'/auth.php';

// Test email route
Route::get('/test-email', function() {
    $admin = \App\Models\User::where('role', 'admin')->first();
    
    if (!$admin) {
        return 'No admin users found. Create one first.';
    }
    
    // Log that we're attempting to send an email
    \Illuminate\Support\Facades\Log::info('Attempting to send test email to: ' . $admin->email);
    
    try {
        // Send email directly with Mail facade
        \Illuminate\Support\Facades\Mail::raw('This is a test email to verify mail sending functionality.', function($message) use ($admin) {
            $message->to($admin->email)
                    ->subject('Test Email');
        });
        
        \Illuminate\Support\Facades\Log::info('Test email sent successfully');
        return 'Test email sent to: ' . $admin->email . '. Check your email or logs.';
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error sending test email: ' . $e->getMessage());
        return 'Error sending test email: ' . $e->getMessage();
    }
});

// Route to check if an email exists
Route::post('/api/check-email', [EmailCheckController::class, 'checkEmail'])->name('api.check.email');
