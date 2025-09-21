<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// User Controllers
use App\Http\Controllers\MainController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\CveController;
use App\Http\Controllers\User\SignupAuthController;
use App\Http\Controllers\User\LoginAuthController;
use App\Http\Controllers\VoteCommentController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BackofficeController;

// Admin CRUD Controllers
use App\Http\Controllers\crud\AdminController;
use App\Http\Controllers\crud\UserController;
use App\Http\Controllers\crud\CveController as AdminCveController;
use App\Http\Controllers\crud\ToolController as AdminToolController;

// Models
use App\Models\Cve;

/*
|--------------------------------------------------------------------------
| Public (Guest / User)
|--------------------------------------------------------------------------
*/

// หน้าแรก (main.blade)
Route::get('/', [MainController::class, 'index'])->name('home');

/* ---------- Auth (User) ---------- */
Route::get('/signup', [SignupAuthController::class, 'showSignupForm'])->name('signup.form');
Route::post('/signup', [SignupAuthController::class, 'signup'])->name('signup.submit');

Route::get('/login',  [LoginAuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginAuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginAuthController::class, 'logout'])->name('logout');

/* ---------- Tools ---------- */
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{name}', [ToolController::class, 'show'])->name('tools.show');
Route::redirect('/Tools', '/tools', 301);

/* ---------- Vulnerability ---------- */
Route::get('/vulnerability', [CveController::class, 'index'])->name('cve.index');
Route::get('/vulnerability/{cve}', [CveController::class, 'show'])->name('cve.show');
Route::redirect('/Vulnerability', '/vulnerability', 301);

/* ---------- Community ---------- */
Route::get('/Community', [CommunityController::class, 'index'])->name('community.index');
Route::redirect('/community', '/Community', 301);

// ต้องล็อกอินก่อน ถึงจะโพสต์/โหวต/คอมเมนต์ได้
Route::middleware('auth:web')->group(function () {
    Route::post('/posts', [CommunityController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/vote', [CommunityController::class, 'vote'])->name('posts.vote');
    Route::post('/posts/{post}/comments', [CommunityController::class, 'storeComment'])->name('posts.comments.store');
    Route::get('/posts/{post}', [CommunityController::class, 'show'])->name('posts.show');
});

/* ---------- About ---------- */
Route::view('/about', 'about')->name('about');

/* ---------- Profile (ต้องล็อกอิน) ---------- */
Route::middleware('auth:web')->group(function () {
    Route::get('/profile', fn() => view('profile', ['user' => Auth::guard('web')->user()]))->name('profile');
    Route::get('/editprofile', [EditProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/editprofile', [EditProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth:web')->group(function () {
    Route::post('/comments/{comment}/reply', [VoteCommentController::class, 'reply'])->name('comments.reply');
    Route::post('/comments/{comment}/vote',  [VoteCommentController::class, 'vote'])->name('comments.vote');
});
/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest (ยังไม่ login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Auth (login แล้ว)
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('can:is-admin')
            ->name('dashboard');

        // Backoffice
        Route::get('/backoffice', [BackofficeController::class, 'index'])->name('backend');

        Route::prefix('backoffice')->name('backend.')->group(function () {
            // Admin CRUD
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

            // CVE CRUD
            Route::get('/cve',            [AdminCveController::class, 'index'])->name('cve.index');
            Route::get('/cve/create',     [AdminCveController::class, 'create'])->name('cve.create');
            Route::post('/cve',           [AdminCveController::class, 'store'])->name('cve.store');
            Route::get('/cve/{cve}/edit', [AdminCveController::class, 'edit'])
                ->where('cve', 'CVE-\d{4}-\d+')->name('cve.edit');
            Route::put('/cve/{cve}',      [AdminCveController::class, 'update'])
                ->where('cve', 'CVE-\d{4}-\d+')->name('cve.update');
            Route::delete('/cve/{cve}',   [AdminCveController::class, 'destroy'])
                ->where('cve', 'CVE-\d{4}-\d+')->name('cve.destroy');

            // Tools CRUD
            Route::prefix('tools')->name('tools.')->group(function () {
                Route::get('/',            [AdminToolController::class, 'index'])->name('index');
                Route::get('/create',      [AdminToolController::class, 'create'])->name('create');
                Route::post('/',           [AdminToolController::class, 'store'])->name('store');
                Route::get('/{tool}/edit', [AdminToolController::class, 'edit'])->whereNumber('tool')->name('edit');
                Route::put('/{tool}',      [AdminToolController::class, 'update'])->whereNumber('tool')->name('update');
                Route::delete('/{tool}',   [AdminToolController::class, 'destroy'])->whereNumber('tool')->name('destroy');
            });
        });

        // Admin logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Content Manage (policy-based)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'can:content.manage'])->group(function () {
    Route::resource('cve',   CveController::class)->only(['create','store','edit','update','destroy']);
    Route::resource('tools', ToolController::class)->only(['create','store','edit','update','destroy']);
});

/*
|--------------------------------------------------------------------------
| Debug DB (optional)
|--------------------------------------------------------------------------
*/
Route::get('/_dbcheck', function () {
    return [
        'db'     => DB::selectOne('select database() as db')->db,
        'tables' => DB::select("SHOW TABLES LIKE 'pj\\_%'"),
    ];
});
