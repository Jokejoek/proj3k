<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use Throwable;

class SignupAuthController extends Controller
{
    public function showSignupForm()
    {
        return view('User.Signup');
    }

    public function signup(Request $r)
    {
        $data = $r->validate([
            'username' => 'required|string|min:3|max:50|unique:pj_users,username',
            'email'    => 'required|email:rfc,dns|max:255|unique:pj_users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = DB::transaction(function () use ($data) {
                // สร้าง/ดึง role 'user'
                $role = Role::firstOrCreate(['name' => 'user']);
                $roleId = $role->getKey(); // = role_id (อ้างตาม $primaryKey ของโมเดล)

                return User::create([
                    'username'  => $data['username'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                    'role_id'   => $roleId,
                    'is_active' => 1, // ถ้ามีคอลัมน์นี้
                ]);
            });

            Auth::login($user);
            $r->session()->regenerate();

            return redirect()->intended('/')->with('success', 'สมัครสมาชิกสำเร็จ!');
        } catch (Throwable $e) {
            report($e);
            return back()
                ->withInput($r->except('password','password_confirmation'))
                ->withErrors(['signup' => 'สมัครสมาชิกไม่สำเร็จ: '.$e->getMessage()]);
        }
    }
}
