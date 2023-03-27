<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedGCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'name',
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

    public function pCat()
    {
        return $this->hasMany(FeedPCategory::class, 'g_category_uuid', 'uuid');
    }
}
