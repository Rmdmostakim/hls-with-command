<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'caption',
        'user_uuid',
        'user_type',
        'feed_p_category_uuid',
        'product_uuid',
        'course_uuid',
        'workshop_uuid',
        'type',
        'src',
        'thumbnail',
        'is_active',
        'status',
    ];
    protected $attributes = [
        'product_uuid' => null,
        'course_uuid' => null,
        'workshop_uuid' => null,
        'thumbnail' => null,
        'src' => null,
        'is_active' => 0,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
    ];
}
