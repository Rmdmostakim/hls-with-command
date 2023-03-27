<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'merchant_uuid',
        'category_uuid',
        'brand_uuid',
        'name',
        'slug',
        'status',
        'featured',
    ];
    protected $attributes = [
        'hot_deals' => 0,
        'top_selling' => 0,
        'is_active' => 1,
        'status' => 0,
        'featured' => 0,
    ];
    protected $hidden = [
        'id',
        'is_active',
    ];


    public function merchant()
    {
        return $this->hasOne(Merchant::class, 'uuid', 'merchant_uuid');
    }

    public function details()
    {
        return $this->hasOne(ProductDetails::class, 'product_uuid', 'uuid');
    }
    public function category()
    {
        return $this->hasOne(Category::class, 'uuid', 'category_uuid');
    }
    public function brand()
    {
        return $this->hasOne(Brand::class, 'uuid', 'brand_uuid');
    }

    public function variant()
    {
        return $this->hasMany(ProductVariant::class, 'product_uuid', 'uuid');
    }
}
