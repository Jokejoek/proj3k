<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        // ใช้ guard:admin ให้ตรงกับหน้าแอดมิน
        $this->middleware('guest:admin')->only(['showLoginForm','login']);
        $this->middleware('auth:admin')->only(['logout']);
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $r)
    {
        $data = $r->validate([
            'login'    => ['required','string'], // username หรือ email
            'password' => ['required','string','min:8'],
        ]);
        $remember = $r->boolean('remember', false);

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // 🔐 ใช้ guard('admin')
        if (! Auth::guard('admin')->attempt([$field => $data['login'], 'password' => $data['password']], $remember)) {
            throw ValidationException::withMessages([
                'login' => 'อีเมล/ยูสเซอร์เนม หรือรหัสผ่านไม่ถูกต้อง',
            ]);
        }

        $r->session()->regenerate();

        // (ถ้าจะตรวจ role เพิ่มเติม)
        $user = Auth::guard('admin')->user();
        if (! in_array((int)$user->role_id, [1,2], true)) {
            Auth::guard('admin')->logout();
            $r->session()->invalidate();
            $r->session()->regenerateToken();
            throw ValidationException::withMessages([
                'login' => 'บัญชีนี้ไม่มีสิทธิ์ผู้ดูแลระบบ',
            ]);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $r)
    {
        Auth::guard('admin')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
