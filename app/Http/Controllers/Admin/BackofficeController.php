<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class BackofficeController extends Controller
{
    public function index()
    {
        // หน้าโครง/landing ของ Back Office
        return view('layout.backend');
    }

    public function adminIndex()
    {
        // รายการผู้ดูแลระบบ
        return view('backend.admins.index');
    }

    public function userIndex()
    {
        // รายการผู้ใช้
        return view('backend.users.index');
    }

    public function cveIndex()
    {
        // รายการ CVE
        return view('backend.cves.index');
    }

    public function toolIndex()
    {
        // รายการ Tools
        return view('backend.tools.index');
    }
}
