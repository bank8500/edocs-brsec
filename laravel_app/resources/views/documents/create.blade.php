@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-4 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-6 md:p-10 rounded-[2.5rem] shadow-xl border-2 border-gray-100">
            <div class="flex items-center gap-4 mb-8">
                <div class="bg-blue-600 p-3 rounded-2xl shadow-lg shadow-blue-100">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-gray-800">เพิ่มข้อมูลเอกสารเข้าสู่คลัง</h2>
            </div>

            @if ($errors->has('doc_number'))
                <div class="mb-6 p-6 bg-red-600 border-4 border-red-800 rounded-[2rem] shadow-2xl animate-pulse">
                    <div class="flex items-center gap-4 text-white">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div>
                            <h3 class="text-2xl font-black uppercase">หยุด! ลืมกรอกเลขที่เอกสาร</h3>
                            <p class="font-bold opacity-90">กรุณากรอกเลขที่เอกสารให้เรียบร้อยก่อนทำการบันทึกข้อมูลเข้าสู่คลัง</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-8 space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">ลงในหมวดหมู่ / ฝ่ายงาน *</label>
                    <select name="dept_id" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all cursor-pointer font-bold text-gray-700" required>
                        <option value="">-- เลือกโฟลเดอร์ปลายทาง --</option>
                        @foreach($available_depts as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-red-500 uppercase tracking-widest ml-1">
                            เลขที่เอกสาร * (ห้ามว่าง)
                        </label>
                        <input type="text" name="doc_number" 
                            class="w-full bg-red-50/50 border-2 border-red-100 rounded-2xl p-4 focus:bg-white focus:border-red-500 focus:ring-4 focus:ring-red-50 outline-none transition-all font-bold placeholder:text-red-300" 
                            placeholder="ระบุเลขที่เอกสารที่นี่เท่านั้น..." 
                            required>
                        @error('doc_number')
                            <p class="text-red-600 text-xs font-black mt-1 animate-bounce">
                                *** กรุณาระบุเลขที่เอกสารก่อนบันทึก!
                            </p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">วันที่ลงทะเบียนเอกสาร *</label>
                        <input type="date" name="doc_date" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all" required>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">อ้างอิงเอกสาร</label>
                        <textarea name="doc_reference" rows="2" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all" placeholder="ระบุรายละเอียดอ้างอิง..."></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">ชื่อเอกสาร *</label>
                        <input type="text" name="file_name" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all" placeholder="ตั้งชื่อเอกสารให้สืบค้นง่าย..." required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">สถานที่เก็บเอกสาร *</label>
                        <input type="text" name="storage_location" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all" placeholder="ระบุตู้/ชั้น/กล่อง..." required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">ประเภทเอกสาร *</label>
                        <select name="doc_type" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all" required>
                            <option value="">[กรุณาเลือก]</option>
                            <option value="คำสั่ง">คำสั่ง</option>
                            <option value="ประกาศ">ประกาศ</option>
                            <option value="หนังสือรับ">หนังสือรับ</option>
                            <option value="หนังสือส่ง">หนังสือส่ง</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">ข้อมูลอื่นๆ</label>
                        <input type="text" name="other_info" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all" placeholder="หมายเหตุเพิ่มเติม...">
                    </div>
                </div>

                <div class="bg-blue-50/50 p-6 rounded-[2rem] border-2 border-dashed border-blue-200 mb-8">
                    <label class="block text-sm font-black text-blue-800 mb-3 ml-1 uppercase">เลือกไฟล์เอกสารที่ต้องการอัปโหลด *</label>
                    <input type="file" name="file" accept=".pdf, .doc, .docx, .xls, .xlsx" class="w-full bg-white border-2 border-gray-100 rounded-xl p-2 file:mr-4 file:py-2 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer" required>
                    <p class="mt-3 text-[10px] text-blue-400 font-bold tracking-widest ml-1 uppercase">สูงสุด 10MB (PDF, WORD, EXCEL)</p>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-5 rounded-3xl font-black text-lg hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all duration-300 transform hover:-translate-y-1 active:scale-95">
                    บันทึกข้อมูลและอัปโหลดเข้าคลัง
                </button>
            </form>
        </div>
    </div>
</div>
@endsection