<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class IntstructorDetail extends Model

{

    use HasFactory;

    protected $fillable = [

        'uuid',

        'instructor_uuid',

        'dp_category_uuid',

        'frequency',

        'class_type',

        'area_of_expertice',

        'certification',

        'status',

    ];

    protected $attributes = [

        'status' => 0,

    ];

    protected $hidden = [

        'id',

    ];

    protected $casts = [

        'certification' => 'array',

    ];

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_uuid', 'instructor_uuid');
    }
    public function dpCategory()
    {
        return $this->hasOne(DpCategory::class, 'uuid', 'dp_category_uuid');
    }
}
