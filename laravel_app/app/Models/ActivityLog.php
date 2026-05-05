<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'description', 'ip_address'];

    public function user()
    {
        // เชื่อมต่อเพื่อให้หน้า Dashboard ดึงชื่อบุคลากรออกมาโชว์ได้
        return $this->belongsTo(User::class, 'user_id');
    }
}