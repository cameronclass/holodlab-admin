<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Только активные товары с брендом и категорией
        $products = Product::with(['brand', 'category'])
            ->where('status', 'active')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'old_price' => $product->old_price,
                    'characteristics' => $product->characteristics,
                    'brand' => $product->brand ? $product->brand->name : null,
                    'category' => $product->category ? $product->category->name : null,
                    'images' => collect($product->images)->map(function ($image) {
                        return $image ? url('storage/' . ltrim($image, '/')) : null;
                    }),
                    'is_hit' => $product->is_hit,
                    'availability' => $product->availability,
                    'status' => $product->status,
                    'created_at' => $product->created_at,
                    'updated_at' => $product->updated_at,
                ];
            });
        return response()->json($products);
    }
}
