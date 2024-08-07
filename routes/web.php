<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/post', function () {
    return view('post');
});

// Authentication routes
Route::group(['middleware' => 'guest'], function () {
    // Show login form
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    
    // Handle login form submission
    Route::post('login', [LoginController::class, 'login']);
    
    // Registration routes (if needed)
    // Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    // Route::post('register', [RegisterController::class, 'register']);
    
    // Password reset routes (if needed)
    // Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    // Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    // Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated routes
Route::group(['middleware' => 'auth'], function () {
    // Dashboard route
    Route::get('/dashboard', [HomeController::class, 'index'])->name('home');

    // Handle logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
