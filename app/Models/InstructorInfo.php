<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstructorInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'instructor_uuid',
        'full_name',
        'user_name',
        'about_me',
        'media_name',
        'media_link',
        'country_uuid',
        'state_uuid',
        'city_uuid',
        'thana_uuid',
        'post_code_uuid',
        'address',
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
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'instructor_uuid');
    }
    public function country()
    {
        return $this->hasOne(Country::class, 'uuid', 'country_uuid');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'uuid', 'state_uuid');
    }
    public function city()
    {
        return $this->hasOne(City::class, 'uuid', 'city_uuid');
    }
    public function thana()
    {
        return $this->hasOne(Thana::class, 'uuid', 'thana_uuid');
    }
    public function postCode()
    {
        return $this->hasOne(PostCode::class, 'uuid', 'post_code_uuid');
    }
}
