<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\InstituteController as AdminInstituteController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;

Route::get('/', fn() => view('landing-page'));

// Only guests (not logged in) can open these
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'view'])->name('register');
    Route::get('/login', [LoginController::class, 'view'])->name('login');
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [LoginController::class, 'authenticate']);
});

// Logout is now POST (the sidebar uses a form with @csrf)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Any logged-in user
Route::middleware('auth')->group(function () {
    Route::get('/main', [MainController::class, 'view'])->name('main');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// One group per role: auth first, then role check
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Admin user management (routes are named admin.users.*)
    Route::resource('users', AdminUserController::class)->except('show');
    Route::patch('users/{user}/toggle', [AdminUserController::class, 'toggle'])->name('users.toggle');

    // Institutes and programs (Query Builder CRUD)
    Route::resource('institutes', AdminInstituteController::class)->except('show');
    Route::resource('programs', AdminProgramController::class)->except('show');
});

Route::middleware(['auth', 'role:Officer'])->prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'officer'])->name('dashboard');
});

Route::middleware(['auth', 'role:Adviser'])->prefix('adviser')->name('adviser.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adviser'])->name('dashboard');
});

Route::middleware(['auth', 'role:Student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');
});
