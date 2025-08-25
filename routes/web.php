<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;


Route::get('/', function () {
    return view('main');   // เรียก main.blade.php
});
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');