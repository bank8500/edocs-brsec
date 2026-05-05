<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email', // ฟิลด์ที่ใช้เป็น Username
        'password',
        'dept_id',
        'role_id',
        'phone',          // 💡 เพิ่มใหม่
        'profile_photo',  // 💡 เพิ่มใหม่
        'bio',            // 💡 เพิ่มใหม่
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

// เพิ่มภายใน class User
public function department()
{
    // สมมติว่าคุณมี Model ชื่อ Department
    return $this->belongsTo(Department::class, 'dept_id');
}

}
