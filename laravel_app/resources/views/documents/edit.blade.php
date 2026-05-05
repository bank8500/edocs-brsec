@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 md:p-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white p-8 rounded-[2rem] shadow-xl border-2 border-gray-100">
            <h2 class="text-2xl font-black text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-edit text-amber-500"></i> แก้ไขข้อมูลเอกสาร
            </h2>

            <form action="{{ route('documents.update', $doc->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div class="space-y-2">
                        <label class="text-sm font-black text-gray-700">เลขที่เอกสาร</label>
                        <input type="text" name="doc_number" value="{{ $doc->doc_number }}" 
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-2xl p-4 focus:bg-white focus:border-blue-500 outline-none transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-black text-gray-700">วันที่ลงทะเบียนเอกสาร *</label>
                        <input type="date" name="doc_date" value="{{ $doc->doc_date }}" 
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-2xl p-4 outline-none transition-all" required>
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-black text-gray-700">อ้างอิงเอกสาร</label>
                        <textarea name="doc_reference" rows="2" 
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-2xl p-4 outline-none transition-all">{{ $doc->doc_reference }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-black text-gray-700">ชื่อเอกสาร *</label>
                        <input type="text" name="file_name" value="{{ $doc->file_name }}" 
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-2xl p-4 outline-none transition-all" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-black text-gray-700">สถานที่เก็บเอกสาร *</label>
                        <input type="text" name="storage_location" value="{{ $doc->storage_location }}" 
                            class="w-full bg-gray-50 border-2 border-gray-200 rounded-2xl p-4 outline-none transition-all" required>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black hover:bg-blue-700 shadow-lg transition-all">
                        บันทึกการแก้ไข
                    </button>
                    <a href="{{ url()->previous() }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold hover:bg-gray-200 transition-all text-center">
                        ยกเลิก
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection