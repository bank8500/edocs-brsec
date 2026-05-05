@extends('layouts.app')

@section('content')
<div class="p-6 md:p-10 max-w-6xl mx-auto">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">ข้อมูลส่วนตัว</h1>
            <p class="text-gray-400 text-sm italic font-bold">จัดการข้อมูลสมาชิกและระบบความปลอดภัย</p>
        </div>
        <div class="text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] bg-blue-50 px-4 py-2 rounded-full border border-blue-100">
            สถานะบัญชี: Active
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[3rem] p-8 shadow-sm border border-gray-100 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-400 to-blue-600"></div>
                
                <div class="relative inline-block mb-6">
                    <img id="avatar-preview" 
                         src="{{ Auth::user()->profile_photo ? asset('uploads/profiles/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=eff6ff&color=2563eb&bold=true&size=200' }}" 
                         class="w-40 h-40 rounded-[3rem] object-cover border-8 border-gray-50 shadow-inner">
                </div>

                <h2 class="text-2xl font-black text-gray-800 leading-tight">{{ Auth::user()->name }}</h2>
                <p class="text-[11px] font-black text-blue-500 uppercase tracking-widest mt-2 bg-blue-50 inline-block px-3 py-1 rounded-lg">
                    @php
                        $depts = [1=>'ฝ่ายวิชาการ', 2=>'บริหารงานบุคคล', 3=>'แผนงานและงบประมาณ', 4=>'บริหารงานอำนวยการ', 5=>'บริหารงานทั่วไป'];
                    @endphp
                    {{ $depts[Auth::user()->dept_id] ?? 'ส่วนกลาง / ผู้บริหาร' }}
                </p>

                <div class="mt-8 pt-8 border-t border-gray-50 space-y-4 text-left">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Username</span>
                        <span class="text-sm font-bold text-gray-700">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">เบอร์มือถือ</span>
                        <span class="text-sm font-bold text-gray-700">{{ Auth::user()->phone ?? 'ไม่ได้ระบุ' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">แนะนำตัว (Bio)</h3>
                <p class="text-sm text-gray-500 leading-relaxed italic">
                    {{ Auth::user()->bio ?? 'ยังไม่มีข้อมูลแนะนำตัว...' }}
                </p>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[3rem] p-10 shadow-sm border border-gray-100">
                @csrf
                <h3 class="font-black text-xl text-gray-800 mb-8 flex items-center">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mr-3 text-sm">1</span>
                    ข้อมูลพื้นฐาน
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1 tracking-widest">อัปโหลดรูปโปรไฟล์</label>
                        <input type="file" name="profile_photo" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1 tracking-widest">ชื่อ-นามสกุลจริง</label>
                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:border-blue-500 outline-none font-bold transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1 tracking-widest">เบอร์มือถือ</label>
                        <input type="text" name="phone" value="{{ Auth::user()->phone }}" placeholder="0xx-xxx-xxxx" class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:border-blue-500 outline-none font-bold transition-all">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1 tracking-widest">แนะนำตัวสั้นๆ</label>
                        <textarea name="bio" rows="3" class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:border-blue-500 outline-none font-bold transition-all">{{ Auth::user()->bio }}</textarea>
                    </div>
                </div>

                <hr class="my-10 border-gray-50">

                <h3 class="font-black text-xl text-gray-800 mb-8 flex items-center">
                    <span class="w-8 h-8 bg-red-100 text-red-600 rounded-xl flex items-center justify-center mr-3 text-sm">2</span>
                    เปลี่ยนรหัสผ่านใหม่
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1 tracking-widest">รหัสผ่านใหม่</label>
                        <input type="password" name="new_password" class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:border-blue-500 outline-none font-bold transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase ml-1 tracking-widest">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" name="new_password_confirmation" class="w-full px-6 py-4 rounded-2xl border-2 border-gray-50 bg-gray-50 focus:bg-white focus:border-blue-500 outline-none font-bold transition-all">
                    </div>
                </div>

                <div class="mt-12 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-sm hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all active:scale-95">
                        บันทึกการเปลี่ยนแปลงทั้งหมด
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection