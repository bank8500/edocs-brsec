@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-10 rounded-3xl shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center">
            <div class="mx-auto flex justify-center">
                <img src="{{ asset('uploads/profiles/Logo_BRSEC_RESIZE.png') }}"
                    alt="BRSEC Logo"
                    class="h-28 w-auto object-contain">
            </div>
            <h2 class="text-3xl font-bold text-gray-800">เข้าสู่ระบบ e-Docs</h2>
            <p class="text-gray-400 mt-2">จัดการเอกสาร 5 ฝ่ายงาน</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">อีเมลผู้ใช้งาน</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none" placeholder="example@test.com" required>
            </div>
            
            <div class="mb-8">
                <label class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่าน</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 hover:shadow-lg shadow-blue-200 transition duration-300">
                เข้าสู่ระบบ
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-gray-400">
            <p>พัฒนาโดย กลุ่มบริหารงานอำนวยการ ศูนย์การศึกษาพิเศษประจำจังหวัดบุรีรัมย์</p>
        </div>
    </div>
</div>

{{-- เรียก Library และรัน Script ไว้ใน Section เดียวกันเลยครับ --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'เข้าสู่ระบบไม่สำเร็จ',
                text: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'ตกลง',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        @endif
    });
</script>
@endsection