<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\StudentController;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Lecturer;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

// Public landing page with a few live totals
Route::get('/', function () {
    return view('home', ['stats' => [
        'students' => Student::count(),
        'courses' => Course::count(),
        'lecturers' => Lecturer::count(),
        'registrations' => CourseRegistration::count(),
    ]]);
})->name('home');

// Guests only: the login form
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1');
});

// Logged-in users only
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Each action is authorised in the controller through its policy
    Route::resource('students', StudentController::class);
    Route::resource('courses', CourseController::class);
    Route::resource('registrations', RegistrationController::class)->only(['index', 'create', 'store', 'destroy']);
});
