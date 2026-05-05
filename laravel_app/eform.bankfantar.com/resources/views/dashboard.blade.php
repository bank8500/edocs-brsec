@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6 md:p-12 text-sm">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">สวัสดี, {{ Auth::user()->name }}</h1>
                <p class="text-gray-500 mt-1 italic">ระบบบริหารจัดการเอกสาร <span class="text-blue-600 font-bold uppercase">e-Docs</span></p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-1">Authorization Status</p>
                    <span class="px-3 py-1 bg-white border border-blue-100 text-blue-600 rounded-full text-xs font-bold shadow-sm">
                        @if(Auth::user()->role_id == 1) ผู้ดูแลระบบ (Admin)
                        @elseif(Auth::user()->role_id == 2) บุคคลากร
                        @elseif(Auth::user()->role_id == 3) ผู้อำนวยการ / รองผู้อำนวยการ
                        @else แบงค์คนสวย @endif
                    </span>
                </div>

                @if(Auth::user()->role_id == 1)
                    <a href="{{ route('admin.dashboard') }}" 
                    class="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition shadow-lg shadow-amber-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        จัดการระบบ (Admin)
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-3 bg-white border border-gray-100 rounded-2xl text-gray-400 hover:text-red-500 hover:bg-red-50 transition duration-300 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>


    <div class="max-w-4xl mx-auto mb-16 mt-8">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-black text-gray-800 tracking-tighter mb-2">ค้นหาเอกสารด่วน</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-[0.3em]">Search across all departments</p>
        </div>

        <form action="{{ route('global.search') }}" method="GET" class="relative group">
            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                <svg class="w-6 h-6 text-blue-500 transition-transform group-focus-within:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="พิมพ์เลขที่เอกสาร, ชื่อไฟล์, อ้างถึง หรือสถานที่เก็บ..." 
                class="w-full pl-16 pr-40 py-6 bg-white border-2 border-transparent focus:border-blue-500 rounded-[2.5rem] shadow-2xl shadow-blue-100/50 outline-none transition-all text-lg font-bold text-gray-700 placeholder:text-gray-300">
            
            <button type="submit" class="absolute right-3 top-3 bottom-3 bg-blue-600 text-white px-10 rounded-[2rem] font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 active:scale-95 flex items-center gap-2">
                <span>ค้นหาตอนนี้</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </form>

        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-2 py-2">Quick Search:</span>
            @php
                $tags = ['คำสั่ง', 'ประกาศ', 'รายงานการประชุม', 'ว.'];
            @endphp
            @foreach($tags as $tag)
                <a href="{{ route('global.search', ['search' => $tag]) }}" 
                class="px-4 py-1.5 bg-white border border-gray-100 rounded-full text-[10px] font-bold text-gray-500 hover:border-blue-500 hover:text-blue-600 transition-all shadow-sm">
                # {{ $tag }}
                </a>
            @endforeach
        </div>
    </div>


        <div class="mb-6">
            <h2 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                <span class="w-2 h-2 bg-blue-500 rounded-full mr-3 shadow-[0_0_10px_rgba(59,130,246,0.5)]"></span> Public Documents
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $publicDocs = [
                        ['id' => 101, 'name' => 'คำสั่งราชการ', 'color' => 'bg-red-500', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['id' => 102, 'name' => 'รายงานการประชุม', 'color' => 'bg-indigo-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['id' => 103, 'name' => 'ประกาศ ศูนย์..', 'color' => 'bg-cyan-500', 'icon' => 'M11 5.882V19.297A1.707 1.707 0 019.293 21H4a2 2 0 01-2-2V5a2 2 0 012-2h11m1-1.118L21 4.882V18.12l-4 1.118V2.764z'],
                        ['id' => 104, 'name' => 'งบทดลอง', 'color' => 'bg-rose-500', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ];
                @endphp

                
                @foreach($publicDocs as $doc)
                    <a href="{{ route('folders.show', $doc['id']) }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-2xl transition duration-500">
                        <div class="w-12 h-12 {{ $doc['color'] }} rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-gray-100 group-hover:scale-110 transition duration-300 text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $doc['icon'] }}"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $doc['name'] }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Shared files</p>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="py-12">
            <div class="w-full h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent"></div>
        </div>

        <div>
            <h2 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.3em] mb-8 flex items-center">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-3 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span> Internal Departments
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @php
                    $depts = [
                        1 => ['name' => 'ฝ่ายวิชาการ', 'color' => 'bg-blue-500'],
                        2 => ['name' => 'บริหารงานบุคคล', 'color' => 'bg-emerald-500'],
                        3 => ['name' => 'แผนงานและงบประมาณ', 'color' => 'bg-amber-500'],
                        4 => ['name' => 'บริหารงานอำนวยการ', 'color' => 'bg-indigo-500'],
                        5 => ['name' => 'บริหารงานทั่วไป', 'color' => 'bg-slate-500'],
                    ];
                @endphp
                @foreach($depts as $id => $dept)
                    @if(Auth::user()->role_id <= 3 || Auth::user()->dept_id == $id)
                        <a href="{{ route('folders.show', $id) }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 hover:shadow-2xl transition duration-500">
                            <div class="w-12 h-12 {{ $dept['color'] }} rounded-2xl flex items-center justify-center mb-4 shadow-lg shadow-gray-100 group-hover:scale-110 transition duration-300 text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">{{ $dept['name'] }}</h3>
                            <p class="text-xs text-gray-400 mt-1 italic uppercase tracking-widest font-bold">Confidential</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection