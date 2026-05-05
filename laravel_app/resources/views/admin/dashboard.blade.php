@extends('layouts.app')

@section('content')
{{-- ส่วนเนื้อหาหลัก: ใช้ Padding (p-6 ถึง p-10) แทนการใช้ Margin เพราะ Layout หลักดันระยะไว้ให้แล้ว --}}
<div class="p-6 md:p-10">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight">ระบบบริหารจัดการ</h1>
            <p class="text-gray-400 text-sm italic font-bold">Admin Dashboard / บุคลากรสถานศึกษา</p>
        </div>
        
        {{-- สถิติแบบย่อ --}}
        <div class="flex gap-4">
            <div class="bg-blue-50 px-6 py-3 rounded-2xl border border-blue-100 shadow-sm">
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">บุคลากรทั้งหมด</p>
                <p class="text-2xl font-black text-blue-600 leading-none mt-1">{{ $users->count() }} <span class="text-xs font-bold text-blue-300">ท่าน</span></p>
            </div>
        </div>
    </div>

    {{-- Grid System: แบ่ง 2 ฝั่ง (ตารางบุคลากร และ กิจกรรม) --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <div class="xl:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 h-fit">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <h2 class="font-black text-xl text-gray-700 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    บัญชีบุคลากร
                </h2>
                {{-- <button onclick="showAddUserModal()" class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-2xl text-sm font-bold hover:bg-blue-700 transition shadow-xl shadow-blue-100 flex items-center justify-center">
                    <span class="mr-2">+</span> เพิ่มบุคลากรใหม่
                </button> --}}
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-gray-400 text-[11px] uppercase font-black tracking-[0.2em]">
                            <th class="px-4 pb-4">ชื่อ-นามสกุล</th>
                            <th class="px-4 pb-4">อีเมล</th>
                            <th class="px-4 pb-4">ฝ่ายงาน</th>
                            {{-- <th class="px-4 pb-4 text-center">จัดการ</th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($users as $user)
                        <tr class="group hover:bg-gray-50 transition-all duration-300">
                            <td class="px-4 py-5 font-bold text-gray-800 rounded-l-2xl border-y border-l border-transparent group-hover:border-gray-100">
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-5 text-gray-500 border-y border-transparent group-hover:border-gray-100 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-5 border-y border-transparent group-hover:border-gray-100">
                                <span class="bg-blue-50 text-blue-600 px-4 py-1.5 rounded-xl text-[10px] font-black uppercase">
                                    {{ $departments[$user->dept_id] ?? 'ส่วนกลาง' }}
                                </span>
                            </td>
                            {{-- <td class="px-4 py-5 text-center rounded-r-2xl border-y border-r border-transparent group-hover:border-gray-100">
                                <button onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')" class="text-red-400 hover:text-red-600 transition p-2 hover:bg-red-50 rounded-xl">
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 h-fit">
            <h2 class="font-black text-xl text-gray-700 mb-8 flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-3 shadow-lg shadow-blue-100 animate-pulse"></span> 
                กิจกรรมล่าสุด
            </h2>
            <div class="space-y-8 max-h-[600px] overflow-y-auto pr-4 custom-scrollbar">
                @forelse($logs as $log)
                <div class="relative pl-8 border-l-2 border-blue-50 pb-2">
                    <div class="absolute -left-[11px] top-0 w-5 h-5 bg-white border-4 border-blue-500 rounded-full shadow-sm"></div>
                    <p class="text-[9px] text-gray-400 font-black uppercase mb-1">
                        {{ $log->created_at->diffForHumans() }}
                    </p>
                    <p class="text-sm font-black text-gray-800 leading-tight">
                        {{ $log->user->name ?? 'ระบบอัตโนมัติ' }}
                    </p>
                    <p class="text-xs text-gray-500 leading-relaxed mt-1">
                        {{ $log->description }}
                    </p>
                </div>
                @empty
                <div class="text-center py-20 text-gray-300">
                    <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="italic text-sm font-bold">ยังไม่มีข้อมูลกิจกรรมในขณะนี้</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ส่วน JavaScript สำหรับ SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDeleteUser(id, name) {
            document.body.style.overflow = 'auto'; 
            Swal.fire({
                title: '<span class="text-xl font-black text-gray-800">ยืนยันการลบบุคลากร?</span>',
                html: `<p class="text-sm text-gray-500">คุณกำลังจะลบ <b>${name}</b> ออกจากระบบ</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน, ลบเลย',
                cancelButtonText: 'ยกเลิก',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2.5rem] p-8',
                    confirmButton: 'bg-red-500 text-white rounded-2xl px-8 py-4 font-bold mx-2',
                    cancelButton: 'bg-gray-100 text-gray-500 rounded-2xl px-8 py-4 font-bold mx-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/admin/users/delete/${id}`;
                }
            });
        }

        function showAddUserModal() {
            Swal.fire({
                title: '<span class="text-2xl font-black text-gray-800">เพิ่มบุคลากรใหม่</span>',
                html: `
                    <div class="text-left space-y-4 px-2 pb-2 mt-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">ชื่อจริง</label>
                                <input id="swal-fname" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm focus:ring-2 focus:ring-blue-100" placeholder="ชื่อ">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-1">นามสกุล</label>
                                <input id="swal-lname" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm focus:ring-2 focus:ring-blue-100" placeholder="นามสกุล">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">อีเมลบุคลากร</label>
                            <input id="swal-email" type="email" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm focus:ring-2 focus:ring-blue-100" placeholder="example@school.com">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">ระดับตำแหน่ง</label>
                            <select id="swal-role" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm bg-white" onchange="toggleDeptDropdown(this.value)">
                                <option value="" disabled selected>--- เลือกตำแหน่ง ---</option>
                                <option value="1">ADMIN</option>
                                <option value="3">ผู้อำนวยการ / รองผู้อำนวยการ</option>
                                <option value="2">ผู้ใช้งานทั่วไป</option>
                            </select>
                        </div>
                        <div id="dept-container" class="space-y-1" style="display: none;">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-1">ฝ่ายงานที่สังกัด</label>
                            <select id="swal-dept" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm bg-white">
                                <option value="" disabled selected>--- เลือกฝ่ายงาน ---</option>
                                <option value="1">ฝ่ายวิชาการ</option>
                                <option value="2">ฝ่ายบริหารงานบุคคล</option>
                                <option value="3">ฝ่ายแผนงานและงบประมาณ</option>
                                <option value="4">ฝ่ายบริหารงานอำนวยการ</option>
                                <option value="5">ฝ่ายบริหารงานทั่วไป</option>
                            </select>
                        </div>
                    </div>
                `,
                confirmButtonText: 'บันทึกและเจนรหัสผ่าน',
                showCancelButton: true,
                cancelButtonText: 'ยกเลิก',
                buttonsStyling: false,
                customClass: {
                    popup: 'rounded-[2.5rem] p-6',
                    confirmButton: 'bg-blue-600 text-white rounded-2xl px-8 py-4 font-bold mx-2',
                    cancelButton: 'bg-gray-100 text-gray-500 rounded-2xl px-8 py-4 font-bold mx-2'
                },
                preConfirm: () => {
                    const fname = document.getElementById('swal-fname').value;
                    const lname = document.getElementById('swal-lname').value;
                    const email = document.getElementById('swal-email').value;
                    const role_id = document.getElementById('swal-role').value;
                    const dept_id = document.getElementById('swal-dept').value;

                    if (!fname || !lname || !email || !role_id) {
                        Swal.showValidationMessage('กรุณากรอกข้อมูลหลักให้ครบถ้วน');
                        return false;
                    }
                    // ถ้าเลือกบุคลากรทั่วไป (4) ต้องมีฝ่ายงาน
                    if (role_id == "4" && !dept_id) {
                        Swal.showValidationMessage('กรุณาเลือกฝ่ายงานสำหรับบุคลากร');
                        return false;
                    }
                    return { fname, lname, email, role_id, dept_id };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    executeStoreUser(result.value);
                }
            });
        }

        function toggleDeptDropdown(roleId) {
                const deptContainer = document.getElementById('dept-container');
                const deptSelect = document.getElementById('swal-dept');
                
                if (roleId == "4") { 
                    // ถ้าเป็นบุคลากรทั่วไป (ID 4) ให้แสดงช่องเลือกฝ่าย
                    deptContainer.style.display = 'block';
                } else {
                    // ถ้าเป็น Admin(1), ผอ.(2), รองฯ(3) ให้ซ่อนและล้างค่า
                    deptContainer.style.display = 'none';
                    if (deptSelect) deptSelect.value = ""; 
                }
            }


    function executeStoreUser(userData) {
        Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch("{{ route('admin.users.store') }}" , {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify(userData)
        })
        .then(async response => {
            // 💡 เพิ่มตรงนี้: ถ้าไม่ใช่สถานะ 200 (สำเร็จ) ให้ดึงข้อความ Error มาดู
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Server Error');
            }
            return response.json();
        })
        .then(res => {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'เพิ่มบุคลากรสำเร็จ!',
                    html: `<div class="p-6 bg-blue-50 rounded-[2rem] border border-blue-100 mt-4 text-center">
                        <p class="text-xs text-blue-400 font-black uppercase">รหัสผ่านสำหรับเข้าใช้งาน:</p>
                        <p class="text-4xl font-mono font-black text-blue-600 mt-2">${res.password}</p>
                    </div>`,
                    confirmButtonText: 'ตกลง',
                    customClass: { popup: 'rounded-[2.5rem]' }
                }).then(() => window.location.reload());
            } else {
                Swal.fire('ผิดพลาด', res.message || 'อีเมลนี้อาจมีในระบบแล้ว', 'error');
            }
        })
        .catch(err => {
            // 💡 แสดง Error จริงๆ ที่เกิดขึ้นแทนข้อความ "เชื่อมต่อผิดพลาด" แบบเดิม
            Swal.fire('ผิดพลาด', 'ข้อความจากระบบ: ' + err.message, 'error');
        });
    }


    function saveUserToDatabase(data) {
    fetch("{{ route('admin.users.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(res => {
        if (res.success) {
            Swal.fire({
                icon: 'success',
                title: 'บันทึกสำเร็จ!',
                html: `
                    <div class="p-6 bg-blue-50 rounded-[2rem] border border-blue-100 mt-4">
                        <p class="text-xs text-blue-400 font-black uppercase tracking-widest">รหัสผ่านชั่วคราว:</p>
                        <p class="text-4xl font-mono font-black text-blue-600 mt-2">${res.password}</p>
                        <p class="text-[11px] text-gray-400 mt-4 font-bold italic">** กรุณาแจ้งรหัสผ่านนี้ให้บุคลากรทราบ **</p>
                    </div>
                `,
                confirmButtonText: 'รับทราบ',
                customClass: { popup: 'rounded-[2.5rem] p-10' }
            }).then(() => window.location.reload());
        }
    })
    .catch(err => Swal.fire('เกิดข้อผิดพลาด', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error'));
    }


</script>

<style>
    /* ปรับแต่ง Scrollbar ให้ดู Minimal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #3b82f6;
    }
</style>
@endsection