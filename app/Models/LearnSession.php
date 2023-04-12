<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearnSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'learn_uuid',
        'title',
        'duration',
        'schedule',
    ];

    protected $attributes = [
        'schedule' => null,
    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [];

    public function lession()
    {
        return $this->hasMany(LearnLession::class, 'session_uuid', 'uuid');
    }
}
