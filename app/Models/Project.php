<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_path',
        'images'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($project) {
            if ($project->images) {
                foreach ($project->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        });
    }
}