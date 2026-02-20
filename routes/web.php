<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\InvoiceController;
// use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CustomerController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login',[AuthController::class,'showLoginForm'])->name('login');
    Route::post('/login',[AuthController::class,'login']);

});

Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('users/{id}/verify',[App\Http\Controllers\Admin\UserController::class, 'verify'])
        ->name('users.verify');
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('invoices', App\Http\Controllers\Admin\InvoiceController::class);
    Route::get('invoices/{invoice}/print', [App\Http\Controllers\Admin\InvoiceController::class, 'print'])
    ->name('invoices.print');
    Route::resource('kwh-categories', App\Http\Controllers\Admin\KwhCategoryController::class);
    Route::resource('payment-proofs', App\Http\Controllers\Admin\PaymentProofController::class)
        ->only(['index','store','update','destroy']);

    Route::get('reports/harian', [App\Http\Controllers\Admin\ReportController::class, 'harian'])
        ->name('reports.harian');
    Route::get('reports/harian/pdf',[App\Http\Controllers\Admin\ReportController::class, 'harianPdf'])
        ->name('reports.harian.pdf');

    Route::get('reports/bulanan', [App\Http\Controllers\Admin\ReportController::class, 'bulanan'])
        ->name('reports.bulanan');
    Route::get('reports/bulanan/pdf', [App\Http\Controllers\Admin\ReportController::class, 'bulananPdf'])
        ->name('reports.bulanan.pdf');
    Route::get('informasi', [App\Http\Controllers\Admin\InformasiController::class, 'index'])->name('informasi.index');
});

// Form registrasi customer
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// Form lupa password
Route::get('/password/reset', [AuthController::class, 'showResetForm'])->name('password.request');
Route::post('/password/email', [AuthController::class, 'sendResetLink'])->name('password.email');

// Form reset password dari link
Route::get('/password/reset/{token}', [AuthController::class, 'showNewPasswordForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

