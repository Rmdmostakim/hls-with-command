<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_uuid',
        'path',
    ];

    protected $hidden = [
        'id',
        'status',
        'created_at',
        'updated_at',
    ];
}
