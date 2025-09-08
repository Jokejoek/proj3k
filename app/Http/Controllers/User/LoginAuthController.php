<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('User.Login');
    }

    public function login(Request $r)
    {
        $r->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $remember = (bool) $r->boolean('remember');
        $login = $r->input('login');

        // เดาว่าเป็นอีเมลหรือยูสเซอร์เนม
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // เพิ่มเงื่อนไข is_active = 1
        $creds = [
            $field     => $login,
            'password' => $r->password,
            'is_active'=> 1,
        ];

        if (Auth::attempt($creds, $remember)) {
            $r->session()->regenerate();
            return redirect()->intended('/')->with('success', 'ยินดีต้อนรับกลับมา!');
        }

        return back()->withInput($r->only('login', 'remember'))
                     ->with('error', 'ข้อมูลเข้าสู่ระบบไม่ถูกต้อง หรือบัญชีถูกระงับ');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/')->with('success', 'ออกจากระบบแล้ว');
    }
}
