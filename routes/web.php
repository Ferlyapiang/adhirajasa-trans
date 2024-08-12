<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemTypeController;

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
Route::resource('management-user/users', UserController::class)->names([
    'index' => 'management-user.users.index',
    'create' => 'management-user.users.create',
    'store' => 'management-user.users.store',
    'show' => 'management-user.users.show',
    'edit' => 'management-user.users.edit',
    'update' => 'management-user.users.update',
    'destroy' => 'management-user.users.destroy',
]);
Route::post('/check-email', [UserController::class, 'checkEmail'])->name('check-email');


// Report Log
Route::get('/log/reports-log', [ReportLogController::class, 'index'])->name('reports.index');
Route::get('logs', [ReportLogController::class, 'index'])->name('logs.index');

// Customer
// Route::resource('/master-data/customers', CustomerController::class)->names([
//     'index' => 'master-data.customers.index',
// ]);

Route::resource('master-data/customers', CustomerController::class)->names([
    'index' => 'master-data.customers.index',
    'create' => 'master-data.customers.create',
    'store' => 'master-data.customers.store',
    'show' => 'master-data.customers.show',
    'edit' => 'master-data.customers.edit',
    'update' => 'master-data.customers.update',
    'destroy' => 'master-data.customers.destroy',
]);

// Item Type
Route::resource('master-data/item-types', ItemTypeController::class)->names([
    'index' => 'master-data.item-types.index',
    'create' => 'master-data.item-types.create',
    'store' => 'master-data.item-types.store',
    'show' => 'master-data.item-types.show',
    'edit' => 'master-data.item-types.edit',
    'update' => 'master-data.item-types.update',
    'destroy' => 'master-data.item-types.destroy',
]);

// Bank Item
Route::get('master-data/bank-data', [BankDataController::class, 'index'])->name('master-data.bank-data.index');
Route::get('master-data/bank-data/create', [BankDataController::class, 'create'])->name('master-data.bank-data.create');
Route::post('master-data/bank-data', [BankDataController::class, 'store'])->name('master-data.bank-data.store');
Route::get('master-data/bank-data/{bankData}/edit', [BankDataController::class, 'edit'])->name('master-data.bank-data.edit');
Route::put('master-data/bank-data/{bankData}', [BankDataController::class, 'update'])->name('master-data.bank-data.update');
Route::delete('master-data/bank-data/{bankData}', [BankDataController::class, 'destroy'])->name('master-data.bank-data.destroy');