<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'sku',
        'price',
        'old_price',
        'characteristics',
        'brand_id',
        'category_id',
        'images',
        'is_hit',
        'availability',
        'status',
    ];

    protected $casts = [
        'characteristics' => 'array',
        'images' => 'array',
        'is_hit' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
