@extends('layouts.app') @section('content')
    <div class="min-h-screen bg-gray-50 p-6 md:p-12">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h1 class="text-3xl font-black text-gray-800 tracking-tight">
                        {{ $folderName }}
                    </h1>
                    <p class="text-sm text-gray-500 font-bold mt-1">
                        คลังข้อมูลและระบบสืบค้นเอกสาร
                    </p>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center text-sm font-bold text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    กลับหน้าหลัก
                </a>
            </div>

            <div
                class="mb-8 bg-white p-6 rounded-[2.5rem] shadow-sm border-2 border-gray-100 transition-all hover:shadow-md">
                <form action="{{ url()->current() }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-5">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-[0.2em]">
                            Smart Search
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-blue-500 transition-transform group-focus-within:scale-110"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="เลขที่, ชื่อไฟล์, อ้างถึง, สถานที่เก็บ..."
                                class="w-full pl-11 pr-5 py-3.5 bg-gray-50 border-2 border-gray-50 rounded-2xl focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 outline-none transition-all font-bold text-gray-700 placeholder:text-gray-300" />
                        </div>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-[0.2em]">Start
                            Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full border-2 border-gray-50 bg-gray-50 rounded-2xl px-4 py-3.5 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-600" />
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 tracking-[0.2em]">End
                            Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full border-2 border-gray-50 bg-gray-50 rounded-2xl px-4 py-3.5 focus:bg-white focus:border-blue-500 outline-none transition-all font-bold text-gray-600" />
                    </div>

                    <div class="md:col-span-1 flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-blue-600 text-white p-4 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-95 flex items-center justify-center group"
                            title="กดเพื่อค้นหา">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>

                        @if (request()->anyFilled(['search', 'start_date', 'end_date']))
                            <a href="{{ url()->current() }}"
                                class="bg-gray-100 text-gray-400 p-4 rounded-2xl hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center"
                                title="ล้างการค้นหา">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="docTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th
                                    class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    เลขที่ / วันที่เอกสาร
                                </th>
                                <th
                                    class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    ชื่อเอกสาร / อ้างอิง
                                </th>
                                <th
                                    class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    ประเภท
                                </th>
                                <th
                                    class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    สถานที่เก็บ
                                </th>
                                <th
                                    class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100">
                                    หมายเหตุ
                                </th>
                                <th
                                    class="px-6 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-100 text-right">
                                    จัดการ
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($documents as $doc)
                                <tr class="hover:bg-blue-50/30 transition-all duration-200 group">
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="inline-flex w-fit items-center px-2.5 py-1 rounded-md bg-blue-50 border border-blue-100 text-[10px] font-black text-blue-600">
                                                {{ $doc->doc_number ?? '-' }}
                                            </span>
                                            <span class="text-[10px] font-bold text-gray-400 italic ml-1">
                                                ลงวันที่:
                                                {{ $doc->doc_date ? \Carbon\Carbon::parse($doc->doc_date)->format('d/m/Y') : '-' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-black text-gray-700 text-sm tracking-tight leading-tight">{{ $doc->file_name }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 mt-1 line-clamp-1 italic">
                                                อ้างถึง: {{ $doc->doc_reference ?? '-' }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <span
                                            class="text-[10px] font-black {{ $doc->doc_type ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-gray-400 bg-gray-50 border-gray-100' }} px-2 py-1 rounded-md border uppercase">
                                            {{ $doc->doc_type ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-2 text-gray-500">
                                            <svg class="w-3.5 h-3.5 opacity-40" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs font-bold">{{ $doc->storage_location ?? '-' }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="text-[10px] font-medium text-gray-400 italic">
                                            {{ $doc->other_info ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-8 py-5 text-right">
                                        <div class="flex justify-end items-center gap-2">
                                            <button type="button"
                                                @click="$dispatch('open-preview', { url: '{{ route('documents.preview', $doc->id) }}' })"
                                                class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all"
                                                title="ดูตัวอย่าง">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>

                                            <a href="{{ route('documents.download', $doc->id) }}"
                                                class="text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-xl transition font-black text-[10px] uppercase">
                                                ดาวน์โหลด
                                            </a>

                                            @if (Auth::user()->role_id == 1 || Auth::user()->dept_id == $doc->dept_id)
                                                <a href="{{ route('documents.edit', $doc->id) }}"
                                                    class="text-amber-500 hover:bg-amber-50 px-3 py-2 rounded-xl transition font-black text-[10px] uppercase">
                                                    แก้ไข
                                                </a>

                                                <button type="button" onclick="deleteDocument({{ $doc->id }})"
                                                    class="text-red-400 hover:text-red-600 p-2 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>

                                                <form id="delete-form-{{ $doc->id }}"
                                                    action="{{ route('documents.destroy', $doc->id) }}" method="POST"
                                                    class="hidden">
                                                    @csrf @method('DELETE')
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div x-data="{ showModal: false, pdfUrl: '' }" @open-preview.window="showModal = true; pdfUrl = $event.detail.url" x-show="showModal"
        x-cloak class="fixed inset-0 z-[100] overflow-hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showModal = false"></div>

        <div
            class="bg-white w-full max-w-5xl h-[90vh] rounded-[2.5rem] shadow-2xl z-10 flex flex-col overflow-hidden border-4 border-white">
            <div class="px-8 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="font-black text-gray-800 uppercase tracking-tight">
                        Document Preview
                    </h3>
                </div>
                <button @click="showModal = false"
                    class="p-2 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-xl transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 bg-gray-200 relative">
                <iframe :src="pdfUrl" class="w-full h-full border-none" allow="autoplay"></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            function deleteDocument(id) {
                Swal.fire({
                    title: "ยืนยันการลบไฟล์?",
                    text: "ไฟล์จะถูกลบออกจากระบบถาวร ไม่สามารถกู้คืนได้!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#ef4444",
                    cancelButtonColor: "#6b7280",
                    confirmButtonText: "ยืนยัน ลบเลย!",
                    cancelButtonText: "ยกเลิก"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById("delete-form-" + id).submit();
                    }
                });
            }

            window.deleteDocument = deleteDocument;

            $('#docTable').DataTable({
                responsive: true,
                pageLength: 10,
                autoWidth: false,
                dom: '<"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4"lf>rt<"flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-4"ip>',
                language: {
                    search: "",
                    searchPlaceholder: "ค้นหาในตาราง...",
                    lengthMenu: "แสดง _MENU_ รายการ",
                    info: "แสดง _START_ - _END_ จาก _TOTAL_ รายการ",
                    paginate: {
                        previous: "ก่อนหน้า",
                        next: "ถัดไป"
                    },
                    zeroRecords: "ไม่พบข้อมูล"
                }
            });

        });
    </script>
@endpush


<style>
    .dataTables_wrapper {
        padding: 2rem !important;
        font-family: 'Sarabun', sans-serif;
    }

    .dataTables_length,
    .dataTables_filter {
        margin-bottom: 1.25rem;
        font-size: 12px;
        font-weight: 800;
        color: #9ca3af;
    }

    .dataTables_length select,
    .dataTables_filter input {
        border: 2px solid #f3f4f6 !important;
        border-radius: 1rem !important;
        padding: .6rem .9rem !important;
        background: #f9fafb !important;
        outline: none !important;
        font-weight: 700;
    }

    .dataTables_length select {
        height: 44px;
        min-width: 70px;
    }

    .dataTables_filter input {
        width: 280px !important;
        height: 44px;
    }


    .dataTables_filter input:focus {
        border-color: #3b82f6 !important;
        background: white !important;
        box-shadow: 0 0 0 4px #eff6ff !important;
    }

    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
        border: none !important;
    }

    table.dataTable thead th {
        border-bottom: 1px solid #f3f4f6 !important;
        background: #f9fafb !important;
        color: #6b7280 !important;
        font-size: 10px !important;
        font-weight: 900 !important;
        letter-spacing: .18em !important;
        text-transform: uppercase !important;
        padding: 1.25rem 1.5rem !important;
    }

    table.dataTable tbody td {
        border-bottom: 1px solid #f9fafb !important;
        padding: 1rem 1.5rem !important;
        vertical-align: middle !important;
    }

    table.dataTable tbody tr {
        transition: all .18s ease;
    }


    table.dataTable tbody tr:hover {
        background: #eff6ff55 !important;
        transform: scale(1.003);
    }

    .dataTables_info {
        color: #d1d5db !important;
        font-size: 10px !important;
        font-weight: 900 !important;
        letter-spacing: .12em;
        padding-top: 1.25rem !important;
    }

    .dataTables_paginate {
        display: flex;
        align-items: center;
        gap: .4rem;
        padding-top: 1.25rem !important;
    }

    .dataTables_paginate .paginate_button {
        border: none !important;
        border-radius: 999px !important;
        padding: .6rem 1rem !important;
        margin: 0 !important;
        font-size: 12px !important;
        font-weight: 800 !important;
        color: #64748b !important;
        background: #f8fafc !important;
        box-shadow: none !important;
    }

    .dataTables_paginate .paginate_button.current,
    .dataTables_paginate .paginate_button.current:hover {
        background: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 10px 24px rgba(37, 99, 235, .22) !important;
    }

    .dataTables_paginate .paginate_button:hover {
        background: #eff6ff !important;
        color: #2563eb !important;
    }

    .dataTables_paginate .paginate_button.disabled,
    .dataTables_paginate .paginate_button.disabled:hover {
        background: #f9fafb !important;
        color: #cbd5e1 !important;
        cursor: default !important;
    }


    .dataTables_wrapper .dataTables_info {
        margin-top: .8rem;
    }

    .dataTables_wrapper .dataTables_paginate {
        margin-top: .4rem;
        justify-content: flex-end;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        min-width: 42px;
        text-align: center;
    }
</style>
