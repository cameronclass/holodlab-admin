<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image_path',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (!$brand->slug) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if ($brand->isDirty('name') && !$brand->isDirty('slug')) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::saved(function () {
            Cache::forget('api_brands');
        });

        static::deleted(function () {
            Cache::forget('api_brands');
        });

        static::saved(function () {
            Cache::forget('api_brands');
        });

        static::deleted(function () {
            Cache::forget('api_brands');
        });
    }
}
