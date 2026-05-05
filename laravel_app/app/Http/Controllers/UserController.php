<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // 1. แสดงรายชื่อบุคลากร (Admin)
    public function index()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // 2. เพิ่มบุคลากรใหม่ (Admin)
public function store(Request $request)
    {
        try {
            // A. ตรวจสอบข้อมูล
            $validator = Validator::make($request->all(), [
                'fname'   => 'required',
                'lname'   => 'required',
                'email'   => 'required|email|unique:users,email', 
                'role_id' => 'required',
                'dept_id' => 'nullable', 
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation Failed',
                    'debug'   => $validator->errors() // ส่งรายการที่กรอกผิดไปโชว์
                ], 422);
            }

            // จัดการค่าว่างให้เป็น NULL
            $deptId = empty($request->dept_id) ? null : $request->dept_id;
            $generatedPassword = Str::random(8);

            // B. บันทึกข้อมูล
            $user = User::create([
                'name'     => $request->fname . ' ' . $request->lname,
                'email'    => $request->email,
                'dept_id'  => $deptId,
                'password' => Hash::make($generatedPassword),
                'role_id'  => $request->role_id, 
            ]);

            return response()->json([
                'success'  => true,
                'password' => $generatedPassword
            ]);

        } catch (\Exception $e) {
            // 💡 ถ้าพัง (500) ให้ส่ง Message ของ Error ไปให้ SweetAlert ปริ้นท์ออกมา
            return response()->json([
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดที่ระบบ (PHP Error)',
                'debug'   => $e->getMessage(), // สาเหตุจริง เช่น SQL Error
                'line'    => $e->getLine(),
                'file'    => $e->getFile()
            ], 500);
        }
    }

    // 3. หน้าโปรไฟล์ (User)
    public function profile()
    {
        $user = Auth::user();
        $departments = [
            1 => 'ฝ่ายวิชาการ', 2 => 'บริหารงานบุคคล', 
            3 => 'แผนงานและงบประมาณ', 4 => 'บริหารงานอำนวยการ', 5 => 'บริหารงานทั่วไป'
        ];
        return view('profile.index', compact('user', 'departments'));
    }

    // 4. อัปเดตโปรไฟล์ (User)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->bio = $request->bio;

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $fileName = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profiles'), $fileName);
            $user->profile_photo = $fileName;
        }

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        return redirect()->back()->with('success', 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }

    public function dashboard()
    {
        return view('dashboard'); // <-- หน้า dashboard ข้างนอกของแก
    }

}