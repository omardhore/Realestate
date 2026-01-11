<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AgentMiddleware;
use Illuminate\Support\Facades\Route;

// Public home page
Route::get('/', function () {
    return view('welcome');
});

// User dashboard, requires authentication and email verification
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile management routes, only for authenticated users
Route::middleware('auth')->group(function () {
    // Show profile edit form
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Update profile information
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Delete user account
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes (login, register, etc.)
require __DIR__ . '/auth.php';

// Admin dashboard, protected by custom AdminMiddleware
Route::get('/admin/dashboard', [AdminController::class, 'adminDashboard'])
    ->middleware(['auth', AdminMiddleware::class])
    ->name('admin.dashboard');

// Agent dashboard, protected by custom AgentMiddleware
Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])
    ->middleware(['auth', AgentMiddleware::class])
    ->name('agent.dashboard');

// Admin logout route
Route::get('/admin/logout', [AdminController::class, 'adminLogout'])
    ->name('admin.logout');

Route::get('/admin/profile', [AdminController::class, 'adminProfile'])
    ->middleware(['auth', AdminMiddleware::class])
    ->name('admin.profile');

Route::post('/admin/profile/store', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');

// Admin login route (GET and POST)
Route::match(['get', 'post'], '/admin/login', [AdminController::class, 'adminLogin'])->name('admin.login');
