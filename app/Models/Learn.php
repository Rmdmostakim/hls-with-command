<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Learn extends Model
{
    use HasFactory;
    protected $fillable = [

        'uuid',
        'instructor_uuid',
        'dp_category',
        'title',
        'overview',
        'slot',
        'type',
        'level',
        'language',
        'certification',
        'status',

    ];

    protected $attributes = [

        'status' => 0,

    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [];


    public function details()
    {
        return $this->hasOne(LearnDetail::class, 'learn_uuid', 'uuid');
    }
    public function session()
    {
        return $this->hasMany(LearnSession::class, 'learn_uuid', 'uuid');
    }
}
