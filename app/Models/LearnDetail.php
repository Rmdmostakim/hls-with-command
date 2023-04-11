<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnDetail extends Model
{
    use HasFactory;
    protected $fillable = [


        'learn_uuid',
        'price',
        'discount',
        'discount_type',
        'discount_duration',
        'cover',
        'promo',

    ];

    protected $attributes = [];

    protected $hidden = [
        'id',
    ];

    protected $casts = [];
}
