// ตัวอย่างการบันทึกเมื่อ Login สำเร็จ
protected function authenticated(Request $request, $user)
{
    ActivityLog::create([
        'user_id' => $user->id,
        'action' => 'Login',
        'description' => 'เข้าสู่ระบบ e-Docs',
        'ip_address' => $request->ip()
    ]);
}