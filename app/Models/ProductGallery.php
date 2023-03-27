<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductGallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'merchant_uuid',
        'path',
    ];
    protected $attributes = [
        'status' => 1,
    ];
    protected $hidden = [
        'id',
        'merchant_uuid',
        'created_at',
        'updated_at',
        'status'
    ];
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
    public function details()
    {
        return $this->hasManyJson(ProductDetails::class, 'images');
    }
}
