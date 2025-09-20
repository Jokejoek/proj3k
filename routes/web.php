<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Controllers
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\CveController; // << ใช้ชื่อนี้ให้ตรงกับ use
use App\Http\Controllers\User\SignupAuthController;
use App\Http\Controllers\User\LoginAuthController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BackofficeController;

/*
|--------------------------------------------------------------------------
| Public (Guest / User)
|--------------------------------------------------------------------------
*/

// หน้าแรก
Route::get('/', fn () => view('main'))->name('home');

// Auth (User)
Route::get('/signup', [SignupAuthController::class, 'showSignupForm'])->name('signup.form');
Route::post('/signup', [SignupAuthController::class, 'signup'])->name('signup.submit');

Route::get('/login',  [LoginAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginAuthController::class, 'logout'])->name('logout');

// Tools
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{name}', [ToolController::class, 'show'])->name('tools.show');
Route::redirect('/Tools', '/tools', 301);

// Vulnerability
Route::get('/vulnerability', [CveController::class, 'index'])->name('cve.index');
Route::get('/vulnerability/{cve}', [CveController::class, 'show'])->name('cve.show');
Route::redirect('/Vulnerability', '/vulnerability', 301);

// Community (ปุ่มใน navbar ควรชี้ route นี้)
Route::get('/Community', [CommunityController::class, 'index'])->name('community.index');
Route::redirect('/community', '/Community', 301); // กันคนพิมพ์ C ใหญ่

// ต้องล็อกอินก่อน ถึงจะโพสต์/โหวต/คอมเมนต์ได้
Route::middleware('auth')->group(function () {
    Route::post('/posts', [CommunityController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/vote', [CommunityController::class, 'vote'])->name('posts.vote');
    Route::post('/posts/{post}/comments', [CommunityController::class, 'storeComment'])->name('posts.comments.store');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // ยังไม่ล็อกอิน
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // ล็อกอินแล้ว
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backend');

        Route::prefix('backoffice')->name('backend.')->group(function () {
            Route::get('/admin', [BackofficeController::class, 'adminIndex'])->name('admins');
            Route::get('/user',  [BackofficeController::class, 'userIndex'])->name('users');
            Route::get('/cve',   [BackofficeController::class, 'cveIndex'])->name('cves');
            Route::get('/tools', [BackofficeController::class, 'toolIndex'])->name('tools');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Content manage permission (ถ้ามี policy นี้)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:content.manage'])->group(function () {
    Route::resource('cve',   CveController::class)->only(['create','store','edit','update','destroy']);
    Route::resource('tools', ToolController::class)->only(['create','store','edit','update','destroy']);
});

/*
|--------------------------------------------------------------------------
| Debug DB (ชั่วคราว)
|--------------------------------------------------------------------------
*/
Route::get('/_dbcheck', function () {
    return [
        'db'     => DB::selectOne('select database() as db')->db,
        'tables' => DB::select("SHOW TABLES LIKE 'pj\\_%'"),
    ];
});
