<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\CVEController;             
use App\Http\Controllers\ToolController;
use App\Http\Controllers\User\SignupAuthController;
use App\Http\Controllers\User\LoginAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BackofficeController;
use App\Http\Controllers\crud\AdminController;
use App\Http\Controllers\crud\UserController;
use App\Http\Controllers\crud\CveController as AdminCveController;
use App\Http\Controllers\crud\ToolController as AdminToolController;
use Illuminate\Support\Facades\DB;
use App\Models\Cve;

// =============== User&Guest ===================
Route::get('/', function () {
    $toolsChart = DB::table('pj_tools')
        ->select('name', 'popularity_score')
        ->orderByDesc('popularity_score')
        ->get();

        $recentCves = Cve::recent(8)->get();

    return view('main', compact('toolsChart','recentCves')); // ส่งตัวแปรเข้าไป
})->name('home');
Route::get('/signup', [SignupAuthController::class, 'showSignupForm'])->name('signup.form');
Route::post('/signup', [SignupAuthController::class, 'signup'])->name('signup.submit');

Route::get('/login',  [LoginAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');
Route::post('/', [LoginAuthController::class, 'logout'])->name('logout');

Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{name}', [ToolController::class, 'show'])->name('tools.show');
Route::redirect('/Tools', '/tools', 301);

// ใช้ CveController ให้ตรงกับ use ด้านบน
Route::get('/vulnerability', [CVEController::class, 'index'])->name('cve.index');
Route::get('/vulnerability/{cve}', [CVEController::class, 'show'])->name('cve.show');
Route::redirect('/Vulnerability', '/vulnerability', 301);

Route::view('/about', 'about')->name('about');

Route::get('/admin/login',  [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');

// ============== Admin Login ========================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('can:is-admin')  // can ต้องอยู่หลัง auth:admin
            ->name('dashboard');

        // Back Office landing
        Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backend');

        // เมนูย่อย Back Office
        Route::prefix('backoffice')->name('backend.')->group(function () {
            // Admins CRUD
            Route::get('/admin',                [AdminController::class, 'index'])->name('admins.index');
            Route::get('/admin/adding',         [AdminController::class, 'adding'])->name('admins.add');
            Route::post('/admin',               [AdminController::class, 'create'])->name('admins.store');
            Route::get('/admin/{id}',           [AdminController::class, 'edit'])->name('admins.edit');
            Route::put('/admin/{id}',           [AdminController::class, 'update'])->name('admins.update');
            Route::delete('/admin/remove/{id}', [AdminController::class, 'remove'])->name('admins.remove');
            Route::get('/admin/reset/{id}',     [AdminController::class, 'reset'])->name('admins.reset');
            Route::put('/admin/reset/{id}',     [AdminController::class, 'resetPassword'])->name('admins.reset.update');
            
            // User CRUD
            Route::get('/user',                [UserController::class, 'index'])->name('users');  
            Route::get('/user/{id}',           [UserController::class, 'edit'])->name('users.edit');        
            Route::put('/user/{id}',           [UserController::class, 'update'])->name('users.update');     
            Route::delete('/user/remove/{id}', [UserController::class, 'remove'])->name('users.remove');     
            Route::get('/user/reset/{id}',     [UserController::class, 'reset'])->name('users.reset');      
            Route::put('/user/reset/{id}',     [UserController::class, 'resetPassword'])->name('users.reset.update');

            // Other sections
            
            Route::get   ('/cve',       [AdminCveController::class, 'index'])->name('cve.index');
            Route::get ('/cve/create', [AdminCveController::class, 'create'])->name('cve.create');
            Route::post('/cve',        [AdminCveController::class, 'store'])->name('cve.store');
            Route::get('/cve/{cve}/edit', [AdminCveController::class, 'edit'])
                ->where('cve', 'CVE-\d{4}-\d+')
                ->name('cve.edit');
            Route::put('/cve/{cve}', [AdminCveController::class, 'update'])
                ->where('cve', 'CVE-\d{4}-\d+')
                ->name('cve.update');
            Route::delete('/cve/{cve}', [AdminCveController::class, 'destroy'])
                ->where('cve', 'CVE-\d{4}-\d+')          
                ->name('cve.destroy');

            Route::prefix('tools')->name('tools.')->group(function () {
            Route::get('/',           [AdminToolController::class, 'index'])->name('index');     
            Route::get('/create',     [AdminToolController::class, 'create'])->name('create');  
            Route::post('/',          [AdminToolController::class, 'store'])->name('store');    

            Route::get('/{tool}/edit', [AdminToolController::class, 'edit'])
                ->whereNumber('tool')
                ->name('edit');                                                                  

            Route::put('/{tool}',     [AdminToolController::class, 'update'])
                ->whereNumber('tool')
                ->name('update');                                                                

            Route::delete('/{tool}',  [AdminToolController::class, 'destroy'])
                ->whereNumber('tool')
                ->name('destroy');                                                              
        });
        });

        // Logout (ชื่อเต็มจะเป็น admin.logout จากการ prefix name('admin.'))
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});