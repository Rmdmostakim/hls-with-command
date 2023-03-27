<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantAccessToken extends Model
{
    use HasFactory;
    protected $fillable = [
        'merchant_uuid',
        'os',
        'browser',
        'ip_address',
        'mac_address',
        'token',
    ];
    protected $hidden = [
        'id',
        'mac_address',
        'ip_address',
    ];
}
