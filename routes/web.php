<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;

// Login and Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Register
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Forgot Password
Route::get('forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password.form');
Route::post('send-reset-link', [AuthController::class, 'sendResetLinkEmail'])->name('send.reset.link');

// Reset Password
Route::get('password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.form');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('reset.password');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// User Management
Route::resource('users', UserController::class);
