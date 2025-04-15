<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Cache::remember('api_brands', 3600, function () {
            return Brand::select(['id', 'name', 'slug', 'image_path'])
                ->get()
                ->map(function ($brand) {
                    if ($brand->image_path) {
                        $brand->image_path = Storage::disk('brands')->url($brand->image_path);
                    }
                    return $brand;
                });
        });

        return response()->json([
            'success' => true,
            'data' => $brands
        ]);
    }
}
