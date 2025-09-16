<?php

namespace App\Http\Controllers\crud;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema; // << เพิ่ม use นี้

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin','can:is-admin']);
    }
    public function index()
    {
        try {
            Paginator::useBootstrap();
            // ถ้าหน้า list แสดง role_name ด้วย ให้ eager load
            $AdminList = AdminUser::admin()
                ->with(['role'])         // <-- มี relation role() ใน Model
                ->orderByDesc('user_id')
                ->paginate(5);

            return view('admin.admins.list', compact('AdminList'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function adding()
    {
        // ถ้ามีตาราง pj_roles
        $roles = DB::table('pj_roles')->select('role_id','name')->orderBy('role_id')->get();
        return view('admin.admins.create', compact('roles'));
    }

    public function create(Request $request)
    {
        $messages = [
            'email.required'    => 'กรุณากรอกข้อมูล',
            'email.email'       => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'      => 'อีเมลนี้ถูกใช้แล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min'      => 'อย่างน้อย :min ตัวอักษร',
            'password.confirmed'=> 'รหัสผ่านไม่ตรงกัน',
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'username.min'      => 'อย่างน้อย :min ตัวอักษร',
            'role_id.integer'   => 'รูปแบบสิทธิ์ไม่ถูกต้อง',
        ];

        $validator = Validator::make($request->all(), [
            'username' => 'required|min:3',
            'email'    => 'required|email|unique:pj_users,email',
            'password' => 'required|min:8|confirmed',      // <-- ต้องมี password_confirmation
            'role_id'  => 'nullable|integer',              // ถ้ามีตาราง roles จริงๆ จะใช้: exists:pj_roles,role_id
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('admin.backend.admins.add')
                             ->withErrors($validator)->withInput();
        }

        try {
            AdminUser::create([
                'username' => strip_tags($request->input('username')),
                'email'    => strip_tags($request->input('email')),
                'password' => $request->input('password'), // mutator จะ hash ให้
                'role_id'  => $request->input('role_id', AdminUser::ADMIN_ROLE), // default ถ้าไม่ส่งมา
            ]);

            Alert::success('เพิ่มผู้ดูแลระบบสำเร็จ');
            return redirect()->route('admin.backend.admins.index');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $admin = AdminUser::admin()->findOrFail($id);
            $roles = DB::table('pj_roles')->select('role_id','name')->orderBy('role_id')->get();
            return view('admin.admins.edit', [
                'user_id'  => $admin->user_id,
                'email'    => $admin->email,
                'username' => $admin->username,
                'role_id'  => $admin->role_id,
                'roles'    => $roles,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        $messages = [
            'email.required'  => 'กรุณากรอกข้อมูล',
            'email.email'     => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'    => 'อีเมลนี้ถูกใช้แล้ว',
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'username.min'      => 'อย่างน้อย :min ตัวอักษร',
            'role_id.exists'    => 'สิทธิ์ที่เลือกไม่ถูกต้อง',
        ];

        $data = $request->validate([
            'username' => ['required','min:3', Rule::unique('pj_users','username')->ignore($id,'user_id')],
            'email'    => ['required','email', Rule::unique('pj_users','email')->ignore($id,'user_id')],
            'role_id'  => ['nullable','integer','exists:pj_roles,role_id'],
        ], $messages);

        // sanitize เบา ๆ
        $data['username'] = trim($data['username']);
        $data['email']    = trim($data['email']);

        try {
            $admin = AdminUser::admin()->findOrFail($id);

            // กันลดสิทธิ์จนไม่เหลือแอดมิน
            if (
                isset($data['role_id']) &&
                (int)$data['role_id'] !== (int)$admin->role_id &&
                AdminUser::admin()->count() <= 1
            ) {
                return back()->withErrors(['global' => 'ต้องมีผู้ดูแลอย่างน้อย 1 บัญชี'])->withInput();
            }

            $admin->update($data);

            Alert::success('ปรับปรุงข้อมูลสำเร็จ');
            return redirect()->route('admin.backend.admins.index');
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    public function remove($id)
    {
        try {
            $admin = AdminUser::admin()->findOrFail($id);
            $admin->delete();
            Alert::success('ลบข้อมูลสำเร็จ');
            return redirect()->route('admin.backend.admins.index');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    public function reset(int $id)
    {
        try {
            $admin = AdminUser::admin()->findOrFail($id);

            return view('admin.admins.editPassword', [
                'user_id'  => $admin->user_id,
                'username' => $admin->username,
                'email'    => $admin->email,
            ]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    

    public function resetPassword(Request $request, int $id)
    {
        $messages = [
            'password.required'  => 'กรุณากรอกรหัสผ่าน',
            'password.min'       => 'อย่างน้อย :min ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
        ];

        $validated = $request->validate([
            'password' => ['required','min:8','confirmed'],
        ], $messages);

        try {
            $admin = AdminUser::admin()->findOrFail($id);

            // เตรียมฟิลด์ที่จะอัปเดต
            $updates = [
                'password' => Hash::make($validated['password']),
            ];

            // อัปเดต remember_token เฉพาะเมื่อมีคอลัมน์นี้จริง
            if (Schema::hasColumn('pj_users', 'remember_token')) {
                $updates['remember_token'] = Str::random(60);
            }

            $admin->forceFill($updates)->save();

            \RealRashid\SweetAlert\Facades\Alert::success('แก้ไขรหัสผ่านเรียบร้อย');
            return redirect()->route('admin.backend.admins.index');
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }
}
