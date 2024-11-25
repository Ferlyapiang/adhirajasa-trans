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
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\BarangKeluarController;
use App\Http\Controllers\BongkarMuatController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GroupMenuController;
use App\Http\Controllers\JenisMobilController;
use App\Http\Controllers\InvoiceBarangMasukController;
use App\Http\Controllers\InvoiceBarangKeluarController;
use App\Http\Controllers\InvoiceGeneratedController;
use App\Http\Controllers\InvoiceReportingController;

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


Route::get('/management-user/users/{user}/change-password', [UserController::class, 'showChangePasswordForm'])->name('management-user.users.change-password');
Route::post('/management-user/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('management-user.users.update-password');


// Report Log
Route::get('/log/reports-log', [ReportLogController::class, 'index'])->name('reports.index');
Route::get('logs', [ReportLogController::class, 'index'])->name('logs.index');

// Customer
// Route::resource('/master-data/customers', CustomerController::class)->names([
//     'index' => 'master-data.customers.index',
// ]);

Route::resource('/master-data/customers', CustomerController::class)->names([
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

// Warehouse
Route::get('master-data/warehouses', [WarehouseController::class, 'index'])->name('master-data.warehouses.index');
Route::get('master-data/warehouses/create', [WarehouseController::class, 'create'])->name('master-data.warehouses.create');
Route::post('master-data/warehouses', [WarehouseController::class, 'store'])->name('master-data.warehouses.store');
Route::get('master-data/warehouses/{warehouse}/edit', [WarehouseController::class, 'edit'])->name('master-data.warehouses.edit');
Route::put('master-data/warehouses/{warehouse}', [WarehouseController::class, 'update'])->name('master-data.warehouses.update');
Route::delete('master-data/warehouses/{warehouse}', [WarehouseController::class, 'destroy'])->name('master-data.warehouses.destroy');

// Data Barang
Route::get('master-data/barang', [BarangController::class, 'index'])->name('master-data.barang.index');
Route::get('master-data/barang/create', [BarangController::class, 'create'])->name('master-data.barang.create');
Route::post('master-data/barang', [BarangController::class, 'store'])->name('master-data.barang.store');
Route::get('master-data/barang/{barang}/edit', [BarangController::class, 'edit'])->name('master-data.barang.edit');
Route::put('master-data/barang/{barang}', [BarangController::class, 'update'])->name('master-data.barang.update');
Route::delete('master-data/barang/{barang}', [BarangController::class, 'destroy'])->name('master-data.barang.destroy');
Route::get('/check-barang-exists', [BarangController::class, 'checkBarangExists'])->name('check-barang-exists');

// Barang Masuk
Route::get('data-gudang/barang-masuk', [BarangMasukController::class, 'index'])->name('data-gudang.barang-masuk.index');
Route::get('data-gudang/barang-masuk/create', [BarangMasukController::class, 'create'])->name('data-gudang.barang-masuk.create');
Route::post('data-gudang/barang-masuk', [BarangMasukController::class, 'store'])->name('data-gudang.barang-masuk.store');
Route::get('data-gudang/barang-masuk/{barangMasuk}/edit', [BarangMasukController::class, 'edit'])->name('data-gudang.barang-masuk.edit');
Route::put('data-gudang/barang-masuk/{barangMasuk}', [BarangMasukController::class, 'update'])->name('data-gudang.barang-masuk.update');
Route::delete('data-gudang/barang-masuk/{barangMasuk}', [BarangMasukController::class, 'destroy'])->name('data-gudang.barang-masuk.destroy');
Route::get('data-gudang/items-by-owner', [BarangMasukController::class, 'itemsByOwner'])->name('data-gudang.items-by-owner');
Route::get('data-gudang/barang-masuk/{id}/detail', [BarangMasukController::class, 'showDetail'])->name('data-gudang.barang-masuk.detail');


// Route::resource('data-gudang/barang-masuk', BarangMasukController::class);


// Route for listing Barang Keluar
Route::get('data-gudang/barang-keluar', [BarangKeluarController::class, 'index'])->name('data-gudang.barang-keluar.index');
Route::get('data-gudang/barang-keluar/create', [BarangKeluarController::class, 'create'])->name('data-gudang.barang-keluar.create');
Route::post('data-gudang/barang-keluar', [BarangKeluarController::class, 'store'])->name('data-gudang.barang-keluar.store');
Route::get('data-gudang/barang-keluar/{barangKeluar}', [BarangKeluarController::class, 'show'])->name('data-gudang.barang-keluar.show');
Route::get('data-gudang/barang-keluar/{barangKeluar}/edit', [BarangKeluarController::class, 'edit'])->name('data-gudang.barang-keluar.edit');
Route::put('data-gudang/barang-keluar/{barangKeluar}', [BarangKeluarController::class, 'update'])->name('data-gudang.barang-keluar.update');
Route::delete('data-gudang/barang-keluar/{barangKeluar}', [BarangKeluarController::class, 'destroy'])->name('data-gudang.barang-keluar.destroy');
Route::get('data-gudang/barang-keluar/showSuratJalan/{barangKeluar}', [BarangKeluarController::class, 'showSuratJalan'])->name('data-gudang.barang-keluar.showSuratJalan');


Route::get('/api/items/{customerId}/{warehouseId}', [BarangKeluarController::class, 'getItemsByCustomer']);
Route::get('/api/items/container/{customerId}/{warehouseId}', [BarangKeluarController::class, 'getItemsByCustomerByContainer']);
Route::get('/api/customers/{warehouseId}', [BarangKeluarController::class, 'getCustomersByWarehouse']);

Route::get('/download-pdf/{id}', [PDFController::class, 'BarangKeluar_download_pdf'])->name('pdf.invoice-barang-keluar');
Route::get('/download-pdf/pajak/{id}', [PDFController::class, 'BarangKeluar_pajak_download_pdf'])->name('pdf.invoice-barang-keluar-pajak');
Route::get('/surat-jalan/download/{id}', [PDFController::class, 'downloadSuratJalanPDF'])->name('surat-jalan.download');


Route::resource('management-menu/menus', MenuController::class)->names([
    'index' => 'management-menu.menus.index',
    'create' => 'management-menu.menus.create',
    'store' => 'management-menu.menus.store',
    'show' => 'management-menu.menus.show',
    'edit' => 'management-menu.menus.edit',
    'update' => 'management-menu.menus.update',
    'destroy' => 'management-menu.menus.destroy',
]);

Route::resource('management-menu/group_menu', GroupMenuController::class);


Route::resource('management-menu/group_menu', GroupMenuController::class)->names([
    'index' => 'management-menu.group_menu.index',
    'create' => 'management-menu.group_menu.create',
    'store' => 'management-menu.group_menu.store',
    'destroy' => 'management-menu.group_menu.destroy',
]);

Route::resource('master-data/jenis-mobil', JenisMobilController::class)->names([
    'index' => 'master-data.jenis-mobil.index',
    'create' => 'master-data.jenis-mobil.create',
    'store' => 'master-data.jenis-mobil.store',
    'show' => 'master-data.jenis-mobil.show',
    'edit' => 'master-data.jenis-mobil.edit',
    'update' => 'master-data.jenis-mobil.update',
    'destroy' => 'master-data.jenis-mobil.destroy',
]);

//Invoice Barang Masuk
Route::get('/data-invoice/invoice-masuk', [InvoiceBarangMasukController::class, 'index'])->name('data-invoice.invoice-masuk.index');
Route::post('/invoice/barang-masuk/update-status', [InvoiceBarangMasukController::class, 'updateStatus'])->name('invoice.barang.masuk.update.status');

//Invoice Barang Keluar
Route::get('/data-invoice/invoice-keluar', [InvoiceBarangKeluarController::class, 'index'])->name('data-invoice.invoice-keluar.index');
Route::post('/invoice/barang-keluar/update-status', [InvoiceBarangKeluarController::class, 'updateStatus'])->name('invoice.barang.keluar.update.status');

//Invoice Master
Route::get('/data-invoice/invoice-master', [InvoiceGeneratedController::class, 'index'])->name('data-invoice.invoice-master.index');
Route::post('/invoices/generate', [InvoiceGeneratedController::class, 'generateInvoice'])->name('invoice.generate');
Route::post('/invoices', [InvoiceGeneratedController::class, 'show'])->name('invoices.show');
Route::get('/data-invoice/invoice-master/display', [InvoiceGeneratedController::class, 'display'])->name('data-invoice.invoice-master.display');
Route::get('/invoice/download/{id}', [InvoiceGeneratedController::class, 'download'])->name('invoice.download');
Route::get('/filter-tanggal-tagihans', [InvoiceGeneratedController::class, 'filterTanggalTagihans']);


Route::get('/data-reporting-invoice/invoice-reporting', [InvoiceReportingController::class, 'index'])->name('data-invoice.invoice-reporting.index');
Route::post('/data-reporting-invoice', [InvoiceReportingController::class, 'show'])->name('invoices-report.show');
Route::get('/data-reporting-invoice/invoice-reporting/display', [InvoiceReportingController::class, 'display'])->name('data-invoice.invoice-reporting.display');
Route::get('/data-reporting-invoice/invoice-reporting/download/{id}', [InvoiceReportingController::class, 'download'])->name('invoice-report.download');
Route::put('/data-invoice/invoice-reporting/{id}', [InvoiceReportingController::class, 'update'])->name('data-invoice.invoice-reporting.update');
Route::post('/data-invoice/invoice-reporting/add-discount-and-note', [InvoiceReportingController::class, 'addDiscountAndNote'])->name('data-invoice.invoice-reporting.addDiscountAndNote');
Route::delete('/data-invoice/invoice-reporting/delete/{id}', [InvoiceReportingController::class, 'deleteDiscount'])->name('data-invoice.invoice-reporting.deleteDiscount');
Route::post('/invoice-reporting/update-invoice', [InvoiceReportingController::class, 'updateInvoice'])->name('data-invoice.invoice-reporting.updateInvoice');
Route::post('/data-invoice/invoice-reporting/add-rokok-and-note', [InvoiceReportingController::class, 'addRokokAndNote'])->name('data-invoice.invoice-reporting.addRokokAndNote');
Route::post('/data-invoice/invoice-reporting/deleteRokokAndNote', [InvoiceReportingController::class, 'deleteRokokAndNote'])->name('data-invoice.invoice-reporting.deleteRokokAndNote');
Route::post('/data-invoice/invoice-reporting/deleteAllRokokAndNote', [InvoiceReportingController::class, 'deleteAllRokokAndNote'])->name('data-invoice.invoice-reporting.deleteAllRokokAndNote');
Route::delete('/data-invoice/invoice-reporting', [InvoiceReportingController::class, 'destroy'])
    ->middleware('auth')
    ->name('data-invoice.invoice-reporting.delete');



// Route::get('/data-bongkar-muat/reporting', [BongkarMuatController::class, 'index'])->name('data-bongkar-muat.bongkar-muat.index');
Route::get('/data-bongkar-muat/reporting-data', [BongkarMuatController::class, 'index'])->name('data-bongkar-muat.getData');

