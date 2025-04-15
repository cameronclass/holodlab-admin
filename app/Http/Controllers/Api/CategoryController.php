<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Cache::remember('api_categories', 3600, function () {
            return Category::select(['id', 'name', 'slug', 'image_path'])
                ->get()
                ->map(function ($category) {
                    if ($category->image_path) {
                        $category->image_path = Storage::disk('categories')->url($category->image_path);
                    }
                    return $category;
                });
        });

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}