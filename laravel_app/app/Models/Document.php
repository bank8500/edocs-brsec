<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    // กำหนดฟิลด์ที่อนุญาตให้บันทึกข้อมูลได้
    protected $fillable = [
        'doc_number',       // เลขที่เอกสาร
        'doc_date',         // วันที่ลงทะเบียน
        'doc_reference',    // อ้างอิงเอกสาร
        'file_name',        // ชื่อเอกสาร (ชื่อที่คุณตั้งในฟอร์ม)
        'storage_location', // สถานที่เก็บเอกสาร
        'doc_type',         // ประเภทเอกสาร
        'other_info',       // ข้อมูลอื่นๆ
        'file_path',        // ที่อยู่ไฟล์จริงบน server
        'dept_id',          // รหัสฝ่าย
        'user_id'           // รหัสคนโพสต์
    ];

    /**
     * นิยามความสัมพันธ์: เอกสารนี้ถูกอัปโหลดโดยใคร
     * เชื่อม user_id ในตาราง documents เข้ากับ id ในตาราง users
     */
    public function uploader()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}