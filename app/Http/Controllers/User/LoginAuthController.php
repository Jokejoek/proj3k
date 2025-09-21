<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAuthController extends Controller
{
    public function __construct()
    {
        // ผู้ที่ยังไม่ล็อกอิน (ฝั่ง user) ถึงจะเห็นหน้า login
        $this->middleware('guest:web')->only(['showLoginForm','login']);
        // ต้องล็อกอิน (ฝั่ง user) ถึงจะออกจากระบบได้
        $this->middleware('auth:web')->only(['logout']);
    }

    public function showLoginForm()
    {
        return view('User.Login'); // view ฝั่ง user
    }

    public function login(Request $r)
    {
        $r->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $remember = $r->boolean('remember');
        $login    = $r->input('login');
        $field    = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // เงื่อนไข is_active = 1
        $creds = [
            $field     => $login,
            'password' => $r->password,
            'is_active'=> 1,
        ];

        if (Auth::guard('web')->attempt($creds, $remember)) {
            $r->session()->regenerate();
            return redirect()->intended(route('home'))
                   ->with('success', 'ยินดีต้อนรับกลับมา!');
        }

        return back()
            ->withInput($r->only('login','remember'))
            ->with('error', 'ข้อมูลเข้าสู่ระบบไม่ถูกต้อง หรือบัญชีถูกระงับ');
    }

    public function logout(Request $r)
    {
        Auth::guard('web')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'ออกจากระบบแล้ว');
    }
}
