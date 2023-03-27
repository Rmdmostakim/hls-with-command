<?php

namespace App\Models;

use App\Http\Controllers\InstructorController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'phone',
        'email',
        'email_verification_code',
        'otp',
        'type',
    ];
    protected $attributes = [
        'email' => null,
        'type' => 0,
        'email_verification_code' => null,
        'otp' => null,
        'is_verified' => 0,
        'is_active' => 0,
        'is_banned' => 0,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
        'otp',
        'email_verification_code',
        'is_verified',
        'is_active',
        'is_banned',

    ];

    public function info()
    {
        return $this->hasOne(InstructorInfo::class, 'instructor_uuid', 'uuid');
    }
    public function details()
    {
        return $this->hasOne(IntstructorDetail::class, 'instructor_uuid', 'uuid');
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'uuid');
    }
}
