<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'full_name',
        'phone',
        'email',
        'email_verification_code',
        'is_verified',
        'is_banned',
        'user_name',
        'password',
    ];
    protected $attributes = [
        'email_verification_code' => null,
        'is_verified' => 0,
        'is_banned' => 0,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
        'password',
        'email_verification_code',
        'is_verified',
        'is_banned',
        'status',
        'created_at',
        'updated_at',
    ];

    public function info()
    {
        return $this->hasOne(MerchantInfo::class, 'merchant_uuid', 'uuid');
    }
    public function profile()
    {
        return $this->hasOne(MerchantProfile::class, 'merchant_uuid', 'uuid');
    }
}
