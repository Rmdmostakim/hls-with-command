<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnLession extends Model
{
    use HasFactory;
	protected $fillable = [

   
        'session_uuid',
        'title',
        'stream_path',
        'thumbnail',
    ];

    protected $attributes = [

        'thumbnail' => null,

    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [];
}
