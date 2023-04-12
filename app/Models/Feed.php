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
        'views',
        'share',
    ];
    protected $attributes = [
        'product_uuid' => null,
        'course_uuid' => null,
        'workshop_uuid' => null,
        'thumbnail' => null,
        'src' => null,
        'is_active' => 0,
        'status' => 0,
        'views' => 0,
        'share' => 0,
    ];
    protected $hidden = [
        'id',
    ];
    protected $casts = [
        'product_uuid' => 'array',
        'src' => 'array',
    ];
    public function merchant()
    {
        return $this->hasOne(Merchant::class, 'uuid', 'user_uuid');
    }
    public function instructor()
    {
        return $this->hasOne(Instructor::class, 'uuid', 'user_uuid');
    }

    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    public function product()
    {
        return $this->belongsToJson(Product::class, 'product_uuid');
    }
    public function workshop()
    {
        return $this->belongsToJson(Workshop::class, 'workshop_uuid');
    }

    public function like()
    {
        return $this->hasMany(FeedLike::class, 'feed_uuid', 'uuid');
    }
    public function comment()
    {
        return $this->hasMany(FeedComment::class, 'feed_uuid', 'uuid');
    }
}
