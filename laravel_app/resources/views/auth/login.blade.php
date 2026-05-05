@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-10 rounded-3xl shadow-xl w-full max-w-md border border-gray-100">
            <div class="text-center">
                <div class="mx-auto flex justify-center">
                    <img src="{{ asset('uploads/profiles/Logo_BRSEC_RESIZE.png') }}" alt="BRSEC Logo"
                        class="h-28 w-auto object-contain">
                </div>
                <h2 class="text-3xl font-bold text-gray-800">เข้าสู่ระบบ e-Docs</h2>
                <p class="text-gray-400 mt-2">จัดการเอกสาร 5 ฝ่ายงาน</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">อีเมลผู้ใช้งาน</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                        placeholder="example@test.com" required>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่าน</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none"
                        placeholder="••••••••" required>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 hover:shadow-lg shadow-blue-200 transition duration-300">
                    เข้าสู่ระบบ
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-400">
                <p> พัฒนาโดย กลุ่มบริหารงานอำนวยการ <br /> ศูนย์การศึกษาพิเศษประจำจังหวัดบุรีรัมย์</p>
                <p>Version <span id="year"></span> Edition.</p>
            </div>
        </div>
    </div>

    <div id="loadingOverlay">
        <div class="loaderBox">
            <img src="{{ asset('uploads/profiles/Logo_BRSEC_RESIZE.png') }}" alt="BRSEC Logo">
            <div class="cat">🐈‍⬛💨</div>
            <h3>กำลังเข้าสู่ระบบ...</h3>
            <p>น้องเอกสารกำลังเปิดแฟ้มให้คุณ</p>
            <div class="bar"><span></span></div>
        </div>
    </div>

    <style>
        #loadingOverlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(248, 250, 252, .86);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
        }

        .loaderBox {
            width: 320px;
            padding: 32px 28px;
            background: white;
            border-radius: 28px;
            text-align: center;
            box-shadow: 0 25px 60px rgba(15, 23, 42, .18);
            border: 1px solid rgba(226, 232, 240, .9);
        }

        .loaderBox img {
            width: 86px;
            height: auto;
            margin: 0 auto 12px;
            animation: logoBounce 1.4s ease-in-out infinite;
        }

        .loaderBox .cat {
            font-size: 42px;
            margin: 8px 0;
            animation: catRun .7s ease-in-out infinite alternate;
        }

        .loaderBox h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 8px;
        }

        .loaderBox p {
            color: #64748b;
            margin-top: 6px;
            font-size: 14px;
        }

        .bar {
            margin-top: 20px;
            width: 100%;
            height: 10px;
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .bar span {
            display: block;
            width: 45%;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
            animation: loadingBar 1.1s ease-in-out infinite;
        }

        @keyframes loadingBar {
            0% {
                transform: translateX(-120%);
            }

            100% {
                transform: translateX(260%);
            }
        }

        @keyframes logoBounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        @keyframes catRun {
            from {
                transform: translateX(-8px) rotate(-3deg);
            }

            to {
                transform: translateX(8px) rotate(3deg);
            }
        }
    </style>

    {{-- เรียก Library และรัน Script ไว้ใน Section เดียวกันเลยครับ --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
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

        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
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

            const form = document.querySelector('form');
            const loadingOverlay = document.getElementById('loadingOverlay');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // หยุด submit ก่อน

                loadingOverlay.style.display = 'flex';

                setTimeout(() => {
                    form.submit(); // ส่งจริงหลัง 3 วิ
                }, 3000);
            });

        });

        document.getElementById('year').innerText = new Date().getFullYear();
    </script>
@endsection
