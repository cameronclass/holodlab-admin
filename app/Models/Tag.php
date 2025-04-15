<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug'
    ];

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = $tag->slug ?? Str::slug($tag->name);
        });

        static::updating(function ($tag) {
            $tag->slug = $tag->slug ?? Str::slug($tag->name);
        });
    }
}
