<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\CveController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\User\SignupAuthController;
use App\Http\Controllers\User\LoginAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BackofficeController;


// =============== User&Guest ===================
Route::get('/', function () {
    return view('main');   // เรียก main.blade.php
});
Route::get('/signup', [SignupAuthController::class, 'showSignupForm'])->name('signup.form');
Route::post('/signup', [SignupAuthController::class, 'signup'])->name('signup.submit');

Route::get('/login',  [LoginAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');
Route::post('/', [LoginAuthController::class, 'logout'])->name('logout');

Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{name}', [ToolController::class, 'show'])->name('tools.show');
// กันคนพิมพ์ T ใหญ่
Route::redirect('/Tools', '/tools', 301);

Route::get('/vulnerability', [CVEController::class, 'index'])->name('cve.index');
Route::get('/vulnerability/{cve}', [CVEController::class, 'show'])->name('cve.show');
Route::redirect('/Vulnerability', '/vulnerability', 301);

// ============== Admin Login ========================
Route::prefix('admin')->name('admin.')->group(function () {

    // -------- Guest (ยังไม่ login) --------
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // -------- Auth (login แล้ว) --------
    Route::middleware('auth:admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Back Office landing
        Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backend');

        // เมนูย่อย Back Office
        Route::prefix('backoffice')->name('backend.')->group(function () {
            Route::get('/admin',  [BackofficeController::class, 'adminIndex'])->name('admins'); // admin.users ก็ได้
            Route::get('/user',   [BackofficeController::class, 'userIndex'])->name('users');
            Route::get('/cve',    [BackofficeController::class, 'cveIndex'])->name('cves');
            Route::get('/tools',  [BackofficeController::class, 'toolIndex'])->name('tools');
        });

        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// -------- Content write permission (CVE / Tools) --------
Route::middleware(['auth', 'can:content.manage'])->group(function () {
    Route::resource('cve',   CVEController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('tools', ToolController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
}); // <-- ปิดกลุ่ม content.manage
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
