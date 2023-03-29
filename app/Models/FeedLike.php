<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedLike extends Model
{
    use HasFactory;
    protected $fillable = [

        'uuid',

        'feed_uuid',

        'user_uuid',

        'status',

    ];

    protected $attributes = [

        'status' => 0,

    ];

    protected $hidden = [

        'id',

        'status',

        'created_at',

        'updated_at',

    ];

    public function feed()
    {
        return $this->hasOne(Feed::class, 'uuid', 'feed_uuid');
    }
}
