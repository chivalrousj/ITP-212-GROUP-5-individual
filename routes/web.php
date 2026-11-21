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
use App\Http\Controllers\Officer\StudentController as OfficerStudentController;
use App\Http\Controllers\Officer\EventController as OfficerEventController;
use App\Http\Controllers\Officer\SessionController as OfficerSessionController;
use App\Http\Controllers\Student\AttendanceController as StudentAttendanceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Officer\AttendanceController as OfficerAttendanceController;

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

// Event list and event details: Officer, Adviser and Student
Route::middleware(['auth', 'role:Officer,Adviser,Student'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
});

// Attendance records of an event: Officer and Adviser (the Adviser only views)
Route::middleware(['auth', 'role:Officer,Adviser'])->group(function () {
    Route::get('/events/{event}/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
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

    // Officer student management
    Route::resource('students', OfficerStudentController::class)->except('show');

    // Officer event management (index and show are the shared routes above)
    Route::resource('events', OfficerEventController::class)->except(['index', 'show']);

    // Open or close check-in and check-out of a manual session
    Route::patch('sessions/{session}/toggle', [OfficerSessionController::class, 'toggle'])->name('sessions.toggle');

    // Officer attendance management (named officer.attendance.store, .update and .destroy)
    Route::post('events/{event}/attendance', [OfficerAttendanceController::class, 'store'])->name('attendance.store');
    Route::put('attendance/{attendance}', [OfficerAttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance/{attendance}', [OfficerAttendanceController::class, 'destroy'])->name('attendance.destroy');
});

Route::middleware(['auth', 'role:Adviser'])->prefix('adviser')->name('adviser.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adviser'])->name('dashboard');
});

Route::middleware(['auth', 'role:Student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'student'])->name('dashboard');

    // Student check-in and check-out (named student.attendance.check-in and student.attendance.check-out)
    Route::post('sessions/{session}/check-in', [StudentAttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('sessions/{session}/check-out', [StudentAttendanceController::class, 'checkOut'])->name('attendance.check-out');
});
