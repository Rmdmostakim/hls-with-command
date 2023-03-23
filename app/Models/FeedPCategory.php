<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedPCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
        'g_category_uuid',
        'is_active',
        'status',
    ];
    protected $attributes = [
        'is_active' => 1,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
    ];
}
