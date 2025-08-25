<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm() { return view('admin.login'); }

    public function login(Request $r)
    {
      $r->validate(['email'=>'required|email','password'=>'required|min:8']);
      if (Auth::guard('admin')->attempt($r->only('email','password'), true)) {
        return redirect()->intended('/admin/dashboard');
      }
      return back()->withErrors(['email'=>'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
    }
}

