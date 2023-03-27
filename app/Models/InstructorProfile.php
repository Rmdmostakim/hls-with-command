<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorProfile extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'instructor_uuid',
        'profile',
        'cover',
    ];
    protected $attributes = [
        'profile' => null,
        'cover' => null,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
}
