<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin User Management Routes
Route::prefix('admin/user-management')->name('admin.user_management.')->group(function () {
    Route::get('/users', [App\Http\Controllers\Admin\UserManagement\UserController::class, 'indexView'])->name('users.index');
    Route::post('/users', [App\Http\Controllers\Admin\UserManagement\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserManagement\UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserManagement\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserManagement\UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
