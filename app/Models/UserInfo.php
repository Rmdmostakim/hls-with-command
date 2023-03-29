<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_uuid',
        'full_name',
        'user_name',
        'country_uuid',
        'state_uuid',
        'city_uuid',
        'thana_uuid',
        'post_code_uuid',
        'status',
    ];

    protected $attributes = [
        'full_name' => null,
        'user_name' => null,
        'country_uuid' => null,
        'state_uuid' => null,
        'city_uuid' => null,
        'thana_uuid' => null,
        'post_code_uuid' => null,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'user_uuid');
    }
}
