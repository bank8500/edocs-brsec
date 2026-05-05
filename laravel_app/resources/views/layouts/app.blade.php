<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>e-Docs | ระบบจัดการเอกสารสถานศึกษา</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('uploads/profiles/Logo_BRSEC_RESIZE.png') }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }

        /* ปรับแต่ง Scrollbar ของ Sidebar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #f1f1f1;
            border-radius: 10px;
        }

        [x-cloak] {
            display: none !important;
        }

        #logoutOverlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(255, 255, 255, .82);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
        }

        .logoutBox {
            width: 320px;
            background: #fff;
            padding: 30px;
            border-radius: 28px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .12);
        }

        .logoutBox img {
            width: 82px;
            margin: auto;
            animation: jump 1.2s infinite;
        }

        .logoutBox .bye {
            font-size: 42px;
            margin: 10px 0;
            animation: run .7s infinite alternate;
        }

        .logoutBox h3 {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
        }

        .logoutBox p {
            color: #6b7280;
            margin-top: 6px;
            font-size: 14px;
        }

        .logoutBar {
            margin-top: 18px;
            height: 10px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .logoutBar span {
            display: block;
            height: 100%;
            width: 45%;
            border-radius: 999px;
            background: linear-gradient(90deg, #f59e0b, #ef4444, #ec4899);
            animation: movebar 1s linear infinite;
        }

        @keyframes movebar {
            from {
                transform: translateX(-120%);
            }

            to {
                transform: translateX(260%);
            }
        }

        @keyframes jump {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes run {
            from {
                transform: translateX(-8px);
            }

            to {
                transform: translateX(8px);
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>



<body class="bg-gray-50" x-data="{ sidebarOpen: false }">

    <div class="lg:hidden bg-white border-b border-gray-100 p-4 flex items-center justify-between sticky top-0 z-[60]">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 p-1.5 rounded-lg shadow-md">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <span class="text-lg font-black text-gray-800">e-Docs</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-500 hover:bg-gray-50 rounded-xl transition">
            <svg x-show="!sidebarOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
            <svg x-show="sidebarOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <div class="flex min-h-screen">
        @auth
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="w-64 bg-white border-r border-gray-100 fixed left-0 top-0 h-full shadow-2xl lg:shadow-sm overflow-y-auto custom-scrollbar z-50 transition-transform duration-300 lg:translate-x-0">
                <div class="p-8 pt-24 lg:pt-8">
                    <div class="hidden lg:flex items-center gap-3 mb-10">
                        <div class="bg-blue-600 p-2 rounded-xl shadow-lg shadow-blue-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-xl font-black text-gray-800 tracking-tight">e-Docs</span>
                    </div>

                    <nav class="space-y-2" x-data="{ openDocs: false }">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">เมนูหลัก</p>


                        @auth
                            @if (Auth::user()->role_id == 1)
                                <div class="mb-8 px-2">
                                    <a href="{{ route('documents.create') }}"
                                        class="flex items-center justify-center gap-3 w-full bg-blue-600 text-white py-4 rounded-[1.5rem] font-black text-sm hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 group">
                                        <svg class="w-5 h-5 text-blue-200 group-hover:text-white transition-colors"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span>อัปโหลดเอกสาร</span>
                                    </a>
                                </div>
                            @endif
                        @endauth


                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }} transition font-bold text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            หน้าแรก
                        </a>
                        {{-- 
                            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-500 hover:bg-gray-50 transition font-bold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                ข้อมูลส่วนตัว
                            </a> --}}

                        <a href="{{ route('documents.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-500 hover:bg-gray-50 transition font-bold text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            ค้นหาเอกสาร
                        </a>

                        <div>
                            <button @click="openDocs = !openDocs"
                                class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-2xl text-gray-500 hover:bg-gray-50 transition font-bold text-sm">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                        </path>
                                    </svg>
                                    ประเภทเอกสาร
                                </div>
                                <svg class="w-4 h-4 transition-transform duration-200"
                                    :class="openDocs ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="openDocs" x-transition.origin.top class="pl-12 mt-2 space-y-1">

                                <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest py-2">Public
                                    Documents</p>
                                @php
                                    $publicDocs = [
                                        101 => 'ทั่วไป (ประกาศ/คำสั่ง)',
                                        102 => 'รายงานการประชุม',
                                        103 => 'ประกาศ ศูนย์..',
                                        104 => 'เอกสารอื่น ๆ',
                                    ];
                                @endphp

                                @foreach ($publicDocs as $id => $name)
                                    <a href="{{ route('folders.show', $id) }}"
                                        class="block py-2 text-xs transition italic
                                        {{ request()->is('folders/' . $id) ? 'text-blue-600 font-black scale-105' : 'text-gray-400 font-bold hover:text-blue-800' }}">
                                        / {{ $name }}
                                    </a>
                                @endforeach

                                {{-- เช็คก่อนว่าล็อกอินไหม ถึงจะแสดงโฟลเดอร์ฝ่าย --}}
                                @auth
                                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest py-2 mt-4">
                                        Internal Departments</p>
                                    @php
                                        $departments = [
                                            1 => 'ฝ่ายวิชาการ',
                                            2 => 'บริหารงานบุคคล',
                                            3 => 'แผนงานและงบประมาณ',
                                            4 => 'บริหารงานอำนวยการ',
                                            5 => 'บริหารงานทั่วไป',
                                        ];
                                    @endphp

                                    @foreach ($departments as $id => $name)
                                        <a href="{{ route('folders.show', $id) }}"
                                            class="block py-2 text-xs transition italic 
                                            {{ request()->is('folders/' . $id) ? 'text-emerald-600 font-black scale-105' : 'text-gray-400 font-bold hover:text-emerald-600' }}">
                                            / {{ $name }}
                                        </a>
                                    @endforeach
                                @endauth
                            </div>
                            @auth
                                @if (Auth::user()->role_id == 1)
                                    <div class="pt-4 border-t border-gray-50 mt-4">
                                        <p class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] mb-4">
                                            ระบบจัดการ</p>
                                        <a href="{{ route('admin.dashboard') }}"
                                            class="flex items-center gap-3 px-4 py-3 rounded-2xl {{ request()->routeIs('admin.dashboard') ? 'bg-amber-50 text-amber-600' : 'text-gray-500 hover:bg-gray-50' }} transition font-bold text-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Dashboard Admin
                                        </a>
                                    </div>
                                @endif
                            @endauth

                            <div class="pt-10">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-400 hover:bg-red-50 transition font-bold text-sm outline-none">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        ออกจากระบบ
                                    </button>
                                </form>
                            </div>
                    </nav>
                </div>
            </aside>
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-40 lg:hidden"></div>
        @endauth
        <main class="flex-1 transition-all duration-300 {{ Auth::check() ? 'lg:ml-64' : 'ml-0' }} min-h-screen">
            @yield('content')
        </main>
    </div>

    <script>
        // แจ้งเตือน SweetAlert จาก Session
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: "{{ session('error') }}",
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-[2rem]'
                }
            });
        @endif

        console.log("==============================================");
        console.log("E-DOCS SYSTEM");
        console.log("พัฒนาเว็บไซต์นี้โดย นางสาววิชชาพร ศรสาลี");
        console.log("ตำแหน่ง ครูชำนาญการ");
        console.log("ศูนย์การศึกษาพิเศษประจำจังหวัดบุรีรัมย์");
        console.log("Developed with dedication for digital education");
        console.log("==============================================");


        document.addEventListener('DOMContentLoaded', function() {

            const logoutForm = document.querySelector('form[action="{{ route('logout') }}"]');
            const logoutOverlay = document.getElementById('logoutOverlay');

            if (logoutForm) {
                logoutForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    logoutOverlay.style.display = 'flex';

                    setTimeout(() => {
                        logoutForm.submit();
                    }, 2000);
                });
            }

        });
    </script>

    <div id="logoutOverlay">
        <div class="logoutBox">
            <img src="{{ asset('uploads/profiles/Logo_BRSEC_RESIZE.png') }}" alt="logo">
            <div class="bye">🐈💨</div>
            <h3>กำลังออกจากระบบ...</h3>
            <p>น้องเอกสารกำลังปิดแฟ้มให้คุณ</p>
            <div class="logoutBar"><span></span></div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>


    @stack('scripts')


</body>

</html>
