<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'merchant_uuid',
        'country_uuid',
        'state_uuid',
        'city_uuid',
        'thana_uuid',
        'post_code_uuid',
        'about',
        'company_name',
        'company_logo',
        'company_banner',
        'website',
    ];

    protected $attributes = [
        'thana_uuid' => null,
        'company_banner' => null,
        'website' => null,
        'featured' => 0,
        'status' => 0,
    ];
    protected $hidden = [
        'id',
        'featured',
        'status',
        'created_at',
        'updated_at',
    ];


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
    public function postcode()
    {
        return $this->hasOne(PostCode::class, 'uuid', 'post_code_uuid');
    }
}
