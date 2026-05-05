<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // 1. หน้า Dashboard แสดงการ์ดแยกประเภท
public function index()
{
    $user = Auth::user();

    $query = Document::with('uploader');

    // ถ้าไม่ใช่ admin ให้เห็นเฉพาะ public + แผนกตัวเอง
    if ($user->role_id > 3) {
        $query->where(function ($q) use ($user) {
            $q->whereIn('dept_id', [101, 102, 103, 104])
              ->orWhere('dept_id', $user->dept_id);
        });
    }

    $documents = $query->latest()->paginate(20)->withQueryString();

    $folderName = 'เอกสารทั้งหมด';
    $id = null;
    $isPublic = true;

    return view('documents.index', compact('documents', 'id', 'folderName', 'isPublic'));
}

    // 1. หน้าแสดงรายการไฟล์ (รองรับทั้งกลุ่มส่วนกลาง และฝ่ายงาน)
    public function showFolder(Request $request, $id)
    {
        $user = Auth::user();

        $publicFolders = [
            101 => 'คำสั่งราชการ', 102 => 'รายงานการประชุม',
            103 => 'ประกาศ ศูนย์..', 104 => 'งบทดลอง'
        ];

        $departments = [
            1 => 'ฝ่ายวิชาการ', 2 => 'บริหารงานบุคคล', 
            3 => 'แผนงานและงบประมาณ', 4 => 'บริหารงานอำนวยการ', 5 => 'บริหารงานทั่วไป'
        ];

        // ตรวจสอบสิทธิ์การเข้าถึง
        if (array_key_exists($id, $publicFolders)) {
            $folderName = $publicFolders[$id];
            $isPublic = true;
        } else {
            if ($user->role_id > 3 && $user->dept_id != $id) {
                abort(403, 'คุณไม่มีสิทธิ์เข้าถึงเอกสารภายในของฝ่ายนี้');
            }
            $folderName = $departments[$id] ?? 'ไม่พบโฟลเดอร์';
            $isPublic = false;
        }

        // เริ่มต้น Query โดยล็อคเฉพาะ ID ของหมวดนั้นๆ (Dept ID)
        $query = Document::where('dept_id', $id)->with('uploader');

        // 💡 ระบบค้นหาทุกคีย์เวิร์ด (Search ทุกฟิลด์ที่มี)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                ->orWhere('doc_number', 'like', "%{$search}%")
                ->orWhere('doc_reference', 'like', "%{$search}%")
                ->orWhere('doc_type', 'like', "%{$search}%")
                ->orWhere('storage_location', 'like', "%{$search}%");
            });
        }

        // กรองตามช่วงวันที่
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // ค้นหาจากชื่อผู้โพสต์ (Uploader)
        if ($request->filled('uploader_name')) {
            $query->whereHas('uploader', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->uploader_name . '%');
            });
        }

        // ดึงข้อมูลและทำ Pagination
        $documents = $query->latest()->paginate(20)->withQueryString();

        return view('documents.index', compact('documents', 'id', 'folderName', 'isPublic'));
    }
    
    // 3. ฟังก์ชันอัปโหลดไฟล์ (รักษาชื่อเดิม และแยกโฟลเดอร์ตาม ID)
    public function upload(Request $request)
    {
        // 1. Validation: เพิ่ม doc_number ให้เป็น required แบบเข้มงวด
        $request->validate([
            'file'             => 'required|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'dept_id'          => 'required',
            'doc_number'       => 'required|string|max:255', // ต้องกรอกเท่านั้น
            'file_name'        => 'required|string|max:255',
            'doc_date'         => 'required|date',
            'storage_location' => 'required|string',
            'doc_type'         => 'required|string',
        ], [
            'doc_number.required'       => '🛑 แจ้งเตือน: คุณยังไม่ได้ระบุเลขที่เอกสาร!',
            'file.required'             => 'กรุณาเลือกไฟล์เอกสาร',
            'storage_location.required' => 'กรุณาระบุสถานที่เก็บเอกสาร',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // รับค่าจากฟอร์มโดยตรง ไม่มีการเจนเองแล้ว
            $docNumber = $request->doc_number;

            // การจัดการไฟล์
            $safeName = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('documents/' . $request->dept_id, $safeName, 'public');

            // บันทึก
            Document::create([
                'doc_number'       => $docNumber,
                'doc_date'         => $request->doc_date,
                'doc_reference'    => $request->doc_reference,
                'file_name'        => $request->file_name,
                'storage_location' => $request->storage_location,
                'doc_type'         => $request->doc_type,
                'other_info'       => $request->other_info,
                'file_path'        => $path,
                'dept_id'          => $request->dept_id,
                'user_id'          => Auth::id()
            ]);

            return redirect()->route('folders.show', $request->dept_id)
                            ->with('success', 'บันทึกเอกสารเลขที่ ' . $docNumber . ' เรียบร้อย');
        }

        return back()->with('error', 'เกิดข้อผิดพลาดในการอัปโหลด');
    }


    public function download($id)
    {
        $doc = Document::findOrFail($id);
        $path = storage_path('app/public/' . $doc->file_path);

        // 1. ตรวจสอบว่ามีไฟล์อยู่จริงไหม
        if (!file_exists($path)) {
            return back()->with('error', 'ไม่พบไฟล์เอกสารในระบบ');
        }

        // 2. ดึงนามสกุลไฟล์จริง (.pdf, .docx ฯลฯ)
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        // 3. ตั้งชื่อไฟล์ใหม่ตอนโหลด (เอาชื่อเอกสารที่คุณตั้งในฟอร์ม + นามสกุลเดิม)
        // ใช้ clean name เพื่อป้องกันอักขระพิเศษมีปัญหา
        $downloadName = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $doc->file_name) . '.' . $extension;

        // 4. ส่งไฟล์ออกไปพร้อม Header ที่ระบุประเภทไฟล์ชัดเจน
        return response()->download($path, $downloadName, [
            'Content-Type' => $this->getMimeType($extension),
        ]);
    }

    // ฟังก์ชันเสริมสำหรับระบุประเภทไฟล์ (MimeType)
    private function getMimeType($ext) {
        $mimes = [
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];
        return $mimes[strtolower($ext)] ?? 'application/octet-stream';
    }

    // 5. ฟังก์ชันลบเอกสาร
    public function destroy($id)
    {
        $doc = Document::findOrFail($id);
        $user = Auth::user();

        // เช็คสิทธิ์: ต้องเป็น Admin (Role 1) หรือ เป็นพนักงานในฝ่ายนั้นๆ (dept_id ตรงกัน)
        if ($user->role_id == 1 || $user->dept_id == $doc->dept_id) {
            
            // ลบไฟล์จริงออกจาก Storage
            if (Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            $doc->delete();
            return back()->with('success', 'ลบเอกสารเรียบร้อยแล้ว');
        }

        return back()->with('error', 'คุณไม่มีสิทธิ์ลบเอกสารของฝ่ายอื่น');
    }


    public function store(Request $request) {
        $document = new Document();
        $document->doc_name = $request->doc_name;
        
        // Hard Code: เช็คว่ากรอกเลขที่เอกสารมาไหม
        if ($request->filled('doc_number')) {
            // ถ้ากรอกมา ให้ใช้ค่านั้นเลย
            $document->doc_number = $request->doc_number;
        } else {
            // ถ้าไม่กรอก ให้เจนอัตโนมัติ: รูปแบบ DOC-YYYYMMDD-ลำดับ
            $today = date('Ymd');
            $count = Document::whereDate('created_at', date('Y-m-d'))->count() + 1;
            $document->doc_number = "DOC-" . $today . "-" . str_pad($count, 3, '0', STR_PAD_LEFT);
        }
        
        $document->uploaded_by = auth()->id(); // เก็บชื่อผู้อัปโหลดอัตโนมัติ
        // ... code ส่วนการเก็บไฟล์และบันทึก ...
    }

    public function exportCsv(Request $request, $id)
    {
        // 1. ดึงข้อมูลโดยใช้ Filter เดียวกับหน้า Index เพื่อให้ได้ไฟล์ตามที่ผู้ใช้เลือกไว้
        $query = Document::where('dept_id', $id)->with('uploader');

        // Filter: ค้นหาชื่อไฟล์/เลขที่เอกสาร
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('file_name', 'like', '%' . $request->search . '%')
                ->orWhere('doc_number', 'like', '%' . $request->search . '%');
            });
        }

        // Filter: ช่วงวันที่
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Filter: ชื่อผู้โพสต์
        if ($request->filled('uploader')) {
            $query->whereHas('uploader', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->uploader . '%');
            });
        }

        $documents = $query->get();

        // 2. ตั้งชื่อไฟล์รายงาน
        $fileName = 'Report_Folder_' . $id . '_' . date('Ymd_His') . '.csv';

        // 3. กำหนด Header สำหรับการ Download
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // 4. สร้าง Callback เพื่อ Stream ข้อมูล (ประหยัด RAM เซิร์ฟเวอร์)
        $callback = function() use($documents) {
            $file = fopen('php://output', 'w');
            
            // --- จุดสำคัญ: ใส่ BOM เพื่อให้ Excel อ่านภาษาไทยออก ---
            fputs($file, "\xEF\xBB\xBF"); 

            // หัวตาราง
            fputcsv($file, ['ลำดับ', 'เลขที่เอกสาร', 'ชื่อไฟล์/เอกสาร', 'ผู้โพสต์', 'วันที่อัปโหลด']);

            // เนื้อหาข้อมูล
            foreach ($documents as $key => $doc) {
                fputcsv($file, [
                    $key + 1,
                    $doc->doc_number ?? 'N/A',
                    $doc->file_name,
                    $doc->uploader->name ?? 'ไม่ระบุ',
                    $doc->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }    

    // 1. หน้าสร้างเอกสารใหม่ (แยกออกมา)
    public function create()
    {
        $user = Auth::user();
        
        // รายชื่อแผนกทั้งหมด (Hard Code ตามโครงสร้างเดิม)
        $all_depts = [
            1 => 'ฝ่ายวิชาการ', 2 => 'ฝ่ายบริหารงานบุคคล', 
            3 => 'แผนงานและงบประมาณ', 4 => 'บริหารงานอำนวยการ', 5 => 'บริหารงานทั่วไป',
            101 => 'คำสั่งราชการ', 102 => 'รายงานการประชุม', 103 => 'ประกาศ ศูนย์..', 104 => 'งบทดลอง'
        ];

        // เช็คสิทธิ์: Admin (Role 1) เลือกได้หมด / User ทั่วไป เห็นเฉพาะแผนกตัวเอง
        if ($user->role_id == 1) {
            $available_depts = $all_depts;
        } else {
            // กรองเอาเฉพาะแผนกที่ตรงกับ dept_id ของ User
            $available_depts = array_intersect_key($all_depts, [$user->dept_id => '']);
        }

        return view('documents.create', compact('available_depts'));
    }

    // 2. หน้าแก้ไขข้อมูลเอกสาร
    public function edit($id)
    {
        $doc = Document::findOrFail($id);
        $user = Auth::user();

        // เช็คสิทธิ์ก่อนแก้: ต้องเป็น Admin หรือเจ้าของฝ่ายเท่านั้น
        if ($user->role_id != 1 && $user->dept_id != $doc->dept_id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขเอกสารนี้');
        }

        return view('documents.edit', compact('doc'));
    }

    public function update(Request $request, $id)
    {
        // 1. ค้นหาเอกสาร
        $doc = Document::findOrFail($id);
        
        // 2. เช็คสิทธิ์ (Admin แก้ได้หมด หรือ User แก้ได้เฉพาะแผนกตัวเอง)
        if (Auth::user()->role_id != 1 && Auth::user()->dept_id != $doc->dept_id) {
            return back()->with('error', 'คุณไม่มีสิทธิ์แก้ไขเอกสารชิ้นนี้');
        }

        // 3. Validation ข้อมูลที่ส่งมาแก้ไข
        $request->validate([
            'file_name' => 'required|string|max:255',
            'doc_date' => 'required|date',
            'storage_location' => 'required|string',
        ]);

        // 4. บันทึกข้อมูลที่แก้ไข (ไม่รวมการเปลี่ยนไฟล์ในขั้นตอนนี้)
        $doc->update([
            'doc_number'       => $request->doc_number,
            'doc_date'         => $request->doc_date,
            'doc_reference'    => $request->doc_reference,
            'file_name'        => $request->file_name,
            'storage_location' => $request->storage_location,
            'doc_type'         => $request->doc_type,
            'other_info'       => $request->other_info,
        ]);

        // 5. กลับไปยังหน้าหมวดหมู่เดิมพร้อมข้อความสำเร็จ
        return redirect()->route('folders.show', $doc->dept_id)
                        ->with('success', 'อัปเดตข้อมูลเอกสารเรียบร้อยแล้ว');
    }

    public function preview($id)
    {
        $doc = \App\Models\Document::findOrFail($id);
        
        // ตรวจสอบตำแหน่งไฟล์จริงในเครื่อง
        $path = storage_path('app/public/' . $doc->file_path);

        if (!file_exists($path)) {
            abort(404, 'ไม่พบไฟล์เอกสารในระบบ');
        }

        // ส่งไฟล์ออกไปโดยกำหนด Content-Type เป็น PDF เพื่อให้แสดงผลใน iframe
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$doc->file_name.'"'
        ]);
    }


    public function globalSearch(Request $request)
    {
        $user = Auth::user();
        
        // 🛡️ กรองสิทธิ์: Admin เห็นหมด / User เห็นแค่ Public (101-104) + แผนกตัวเอง
        $query = Document::with('uploader');

        if ($user->role_id > 3) {
            $query->where(function($q) use ($user) {
                $q->whereIn('dept_id', [101, 102, 103, 104]) 
                ->orWhere('dept_id', $user->dept_id);
            });
        }

        // 🔍 ค้นหาครอบคลุมทุกฟิลด์
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('file_name', 'like', "%{$search}%")
                ->orWhere('doc_number', 'like', "%{$search}%")
                ->orWhere('doc_reference', 'like', "%{$search}%")
                ->orWhere('doc_type', 'like', "%{$search}%")
                ->orWhere('storage_location', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()->paginate(20)->withQueryString();
        
        // ตั้งค่าตัวแปรเพื่อให้หน้า View แสดงผลได้ถูกต้อง
        $folderName = "ผลการค้นหาที่คุณเข้าถึงได้";
        $id = null; 
        $isPublic = true; 

        return view('documents.index', compact('documents', 'id', 'folderName', 'isPublic'));
    }
}