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



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Admin dashboard
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Supervisor dashboard
Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');

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

//SUPERVISOR ROUTES

// Projects Routes
Route::get('projects/{project}', [SupervisorController::class, 'showProject'])->name('projects.show');
Route::get('projects/{project}/edit', [SupervisorController::class, 'editProject'])->name('projects.edit');
Route::put('projects/{project}', [SupervisorController::class, 'updateProject'])->name('projects.update');

// Tasks Routes
Route::get('tasks/{task}', [SupervisorController::class, 'showTask'])->name('tasks.show');
Route::get('tasks/{task}/edit', [SupervisorController::class, 'editTask'])->name('tasks.edit');
Route::put('tasks/{task}', [SupervisorController::class, 'updateTask'])->name('tasks.update');

//Route for Staff tasks

Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');


//User Management routes
Route::get('/users', [App\Http\Controllers\UserController::class, 'indexView'])->name('admin.user_management.index');
Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('admin.user_management.store');
Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('admin.user_management.show');
Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.user_management.update');
Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.user_management.destroy');

// Supervisor dashboard
Route::get('/supervisor/dashboard', function () {
    return view('supervisor.dashboard');
})->name('supervisor.dashboard');




Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.email');

Route::get('/reset-password/{token}', [PasswordResetLinkController::class, 'edit'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetLinkController::class, 'update'])
    ->name('password.update');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/admin/user_management', function () {
    return view('admin.user_management.index');
})->name('admin.user_management.index');

// Admin User Management Routes
Route::prefix('admin/user_management')->name('admin.user_management.')->group(function () {
    Route::get('/users', [App\Http\Controllers\UserController::class, 'indexView'])->name('admin.user_management.index');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('admin.user_management.store');
    Route::get('/users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('admin.user_management.show');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.user_management.update');
    Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('admin.user_management.destroy');
});