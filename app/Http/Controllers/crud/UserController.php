<?php

namespace App\Http\Controllers\crud;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin','can:is-admin']);
    }

    public function index()
    {
        Paginator::useBootstrap();

        $UserList = User::user()
        ->with('role')           // เปิดเพื่อหลีกเลี่ยง N+1
        ->orderByDesc('user_id')
        ->paginate(10);

        return view('admin.user.list', compact('UserList'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'username' => ['required','string','min:3','unique:pj_users,username'],
            'email'    => ['required','email','unique:pj_users,email'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        User::create([
            'username' => trim($data['username']),
            'email'    => strtolower(trim($data['email'])),
            'password' => Hash::make($data['password']),
            'role_id'  => 2,
        ]);
        Alert::success('เพิ่มผู้ใช้สำเร็จ');
        return redirect()->route('admin.backend.users');
    }

    public function edit(int $id)
    {
        $u = User::user()->findOrFail($id);

        return view('admin.user.edit', [
            'user_id'  => $u->user_id,
            'username' => $u->username,
            'email'    => $u->email,
        ]);
    }

    public function update(Request $r, int $id)
    {
        $messages = [
            'email.required'    => 'กรุณากรอกข้อมูล',
            'email.email'       => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'      => 'อีเมลนี้ถูกใช้แล้ว',
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'username.min'      => 'อย่างน้อย :min ตัวอักษร',
        ];

        // ใช้ $r แทน $request และอ้าง unique โดย ignore ตามคีย์ user_id
        $validator = Validator::make($r->all(), [
            'username' => ['required','string','min:3', Rule::unique('pj_users','username')->ignore($id,'user_id')],
            'email'    => ['required','email',          Rule::unique('pj_users','email')->ignore($id,'user_id')],
        ], $messages);

        if ($validator->fails()) {
            // กลับไปหน้าแก้ของ "users" ไม่ใช่ admins
            return redirect()
                ->route('admin.backend.users.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // ใช้โมเดล User (scope user) ไม่ใช่ AdminUser
        $user = User::user()->findOrFail($id);

        $user->update([
            'username' => trim($r->input('username')),
            'email'    => strtolower(trim($r->input('email'))), // normalize
        ]);

        Alert::success('ปรับปรุงข้อมูลสำเร็จ');
        return redirect()->route('admin.backend.users');
    }

    public function reset(int $id)
    {
        $u = User::user()->findOrFail($id);

        return view('admin.user.editPassword', [
            'user_id'  => $u->user_id,
            'username' => $u->username,
            'email'    => $u->email,
        ]);
    }

    public function resetPassword(Request $r, int $id)
    {
        $validated = $r->validate([
            'password' => ['required','min:8','confirmed'],
        ]);

        $u = User::user()->findOrFail($id);

        $u->forceFill([
            'password' => Hash::make($validated['password']),
            // ลบ remember_token ออก
        ])->save();

        Alert::success('แก้ไขรหัสผ่านเรียบร้อย');
        return redirect()->route('admin.backend.users');
    }
    public function remove($id)
    {
        try {
            $user = \App\Models\User::user()->findOrFail($id);
            $user->delete();
            \RealRashid\SweetAlert\Facades\Alert::success('ลบข้อมูลสำเร็จ');
            return redirect()->route('admin.backend.users');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }
}
