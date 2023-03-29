<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'feed_uuid',
        'user_uuid',
        'parent_uuid',
        'comment',
        'attachment',
        'status',
    ];
    protected $attributes = [
        'status' => 0,
        'attachment' => null,
        'feed_uuid' => null,
    ];
    protected $hidden = [
        'id',
        'status',
    ];
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_uuid', 'user_uuid');
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'user_uuid');
    }
    public function reply()
    {
        return $this->hasMany(FeedComment::class, 'parent_uuid', 'uuid');
    }
}
