<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_uuid',
        'stock',
        'tags',
        'price',
        'details',
        'cover',
        'images',
        'discount',
        'discount_type',
        'discount_duration',
    ];
    protected $attributes = [
        'tags' => null,
        'discount' => null,
        'discount_type' => null,
        'discount_duration' => null,
    ];
    protected $casts = [
        'images' => 'array',
    ];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    public function gallery()
    {
        return $this->belongsToJson(ProductGallery::class, 'images');
    }
    public function cover()
    {
        return $this->hasOne(ProductGallery::class, 'uuid', 'cover');
    }

    public function variants()
    {
        return $this->belongsTo(ProductVariant::class, 'product_uuid', 'product_uuid');
    }
}
