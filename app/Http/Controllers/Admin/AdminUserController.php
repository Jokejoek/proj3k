<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index() {
        $users = User::with('role')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create() {
        $roles = Role::whereIn('name',['super_admin','content_admin','user'])->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $r) {
        $data = $r->validate([
            'name' => 'required|max:255',
            'email'=> 'required|email|unique:users,email',
            'password'=> 'required|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);
        $u = new User($data);
        $u->password = Hash::make($data['password']);
        $u->save();
        return redirect()->route('admin.users.index')->with('ok','สร้างผู้ใช้สำเร็จ');
    }

    public function edit(User $user) {
        $roles = Role::whereIn('name',['super_admin','content_admin','user'])->get();
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $r, User $user) {
        $data = $r->validate([
            'name'=>'required|max:255',
            'email'=>"required|email|unique:users,email,{$user->id}",
            'role_id'=>'required|exists:roles,id',
        ]);
        $user->update($data);
        return back()->with('ok','อัปเดตข้อมูลแล้ว');
    }

    public function resetPassword(User $user) {
        $new = Str::random(12);
        $user->password = Hash::make($new);
        $user->save();
        // ***สำหรับโปรดักชันควรส่งอีเมลแจ้ง ไม่โชว์รหัสในหน้าเว็บ***
        return back()->with('ok',"รีเซ็ตรหัสแล้ว (ชั่วคราว): $new");
    }

    public function destroy(User $user) {
        $user->delete();
        return back()->with('ok','ลบผู้ใช้แล้ว');
    }
}

