<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // เพิ่มบรรทัดนี้เพื่อใช้ Hash::make
use Illuminate\Support\Str;          // เพิ่มบรรทัดนี้เพื่อใช้ Str::random

class AdminController extends Controller
{
    public function index()
    {
        // เช็คสิทธิ์ Admin
        if (Auth::user()->role_id != 1) {
            abort(403);
        }

        $users = User::all();
        
        // ดึงประวัติกิจกรรมล่าสุด 50 รายการ
        $logs = ActivityLog::with('user')->latest()->take(50)->get();
        
        $departments = [
            1 => 'ฝ่ายวิชาการ', 2 => 'ฝ่ายบริหารงานบุคคล', 
            3 => 'แผนงานและงบประมาณ', 4 => 'บริหารงานอำนวยการ', 5 => 'บริหารงานทั่วไป'
        ];

        return view('admin.dashboard', compact('users', 'logs', 'departments'));
    }

    public function storeUser(Request $request)
    {
        // 1. ตรวจสอบข้อมูล
        $request->validate([
            'email'   => 'required|email|unique:users,email',
            'fname'   => 'required',
            'lname'   => 'required',
            'role_id' => 'required', 
            // 🔥 แก้ตรงนี้: บังคับเลือกฝ่ายเฉพาะเมื่อ role_id เป็น 4 (บุคลากรทั่วไป)
            'dept_id' => 'required_if:role_id,4', 
        ], [
            'email.unique' => 'อีเมลนี้มีในระบบแล้วค่ะหญิง',
            'dept_id.required_if' => 'ถ้าเป็นบุคลากรทั่วไป หญิงต้องเลือกฝ่ายให้เขาด้วยนะ!',
        ]);

        // 2. สุ่มรหัสผ่าน
        $plainPassword = Str::random(8);
        $roleId = $request->role_id;

        // 3. Logic การจัดการค่า dept_id 
        // ✅ แก้ตรงนี้: ถ้าเป็นบุคลากรทั่วไป (ID 4) ให้เซฟฝ่าย ถ้าเป็นตำแหน่งอื่น (1,2,3) ให้เป็น null
        $deptId = ($roleId == 4) ? $request->dept_id : null;
        
        // ตั้งชื่อตำแหน่งสำหรับ Log ตาม ID จริง
        $roles = [
            1 => 'Admin',
            2 => 'บุคลากรทั่วไป',
            3 => 'ผู้อำนวยการ / รองผู้อำนวยการ',
            4 => 'บุคลากรทั่วไป'
        ];
        $roleName = $roles[$roleId] ?? 'ไม่ระบุตำแหน่ง';

        // 4. บันทึกลงฐานข้อมูล
        $user = User::create([
            'name'     => $request->fname . ' ' . $request->lname,
            'email'    => $request->email,
            'dept_id'  => $deptId, 
            'role_id'  => $roleId,
            'password' => Hash::make($plainPassword),
        ]);

        // 5. บันทึกประวัติกิจกรรม
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => "เพิ่มบุคลากรใหม่: {$user->name} ตำแหน่ง: {$roleName}",
        ]);

        return response()->json([
            'success'  => true,
            'password' => $plainPassword
        ]);
    }


    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        
        // บันทึกกิจกรรม: เพิ่มฟิลด์ 'action' เข้าไปเช่นกัน
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete', // เพิ่มบรรทัดนี้ครับ
            'description' => "ลบบุคลากร: " . $user->name,
        ]);

        $user->delete();

        return redirect()->back()->with('success', 'ลบบุคลากรเรียบร้อยแล้ว');
    }

}